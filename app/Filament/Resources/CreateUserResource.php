<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CreateUserResource\Pages;
use App\Filament\Resources\CreateUserResource\RelationManagers;
use Illuminate\Support\Collection;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Password;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;

class CreateUserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Users';

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $navigationGroup = 'User Management';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->last_name;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }
    public static function getNavigationBadgeColor(): string
    {
        return 'primary';
    }
    public static function form(Form $form): Form
    {


        return $form
            ->schema([
                Section::make('Primary Information')
                    ->description('Fill up the following:')
                    ->schema([
                        TextInput::make('name')->label('First Name')->required(),
                        TextInput::make('email')->label('Email')->email()->required(),
                        TextInput::make('last_name')->label('Last Name')->required(),
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->minLength(6)
                            ->same('password_confirmation')
                            ->dehydrated(fn($state) => filled($state))
                            ->rule(Password::default()),
                        TextInput::make('middle_name')->label('Middle Name'),
                        TextInput::make('password_confirmation')
                            ->password()
                            ->revealable()
                            ->label('Confirm Password'),
                    ])->columns(2),

                Select::make('employment_status')->label('Employment Status')
                    ->options([
                        'Part-Time' => 'Part-Time',
                        'Temporary' => 'Temporary',
                        'Full Time' => 'Full Time',
                    ])
                    ->required(),
                TextInput::make('designation')->label('Designation/Position')
                    ->required(),
                Select::make('unit')
                    ->label('Unit')
                    ->options([
                        'DO' => 'DO',
                        'KMO' => 'KMO',
                        'IGRD' => 'IGRD',
                        'CISC' => 'CISC',
                        'CSPPS' => 'CSPPS',
                    ])
                    ->required(),
                Select::make('ms_phd')
                    ->label('Highest Degree Attained')
                    ->options([
                        "Bachelor's Degree" => "Bachelor's Degree",
                        "Master's Degree" => "Master's Degree",
                        'Doctoral Degree' => 'Doctoral Degree',
                        'Vocational/Technical' => 'Vocational/Technical',
                        'High School Diploma' => 'High School Diploma',
                        'N/A' => 'N/A',
                    ])
                    ->required(),
                Select::make('staff')
                    ->label('Staff/Classification')
                    ->options([
                        'admin' => 'Admin',
                        'faculty' => 'Faculty',
                        'REPS' => 'REPS',
                    ])
                    ->default('faculty')
                    ->required(),


                Select::make('systemrole')
                    ->label('User Role')
                    ->options([
                        'admin' => 'Admin',
                        'super-admin' => 'Super Admin',
                        'user' => 'User',
                        'secretary' => 'Secretary',
                    ])
                    ->default('user')
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set, $get, $record) {
                        if ($record) {
                            $record->update(['systemrole' => $state]);
                            $record->syncRoles([$state]);
                        }
                    }),

                TextInput::make('research_interests')->label('Research Interests'),
                TextInput::make('fields_of_specialization')->label('Fields of Specialization'),
                TextInput::make('rank_')->label('Rank'),
                TextInput::make('sg')->label('SG'),
                TextInput::make('s')->label('S'),
                TextInput::make('item_no')->label('Item Number'),
                DatePicker::make('birthday')->label('Birthday')
                    ->format('Y/m/d')->required(),
                TextInput::make('yr_grad')->label('Year Graduated'),
                TextInput::make('date_hired')->label('Date Hired in CPAf'),
                TextInput::make('contact_no')->label('Contact Number'),
            ]);


    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([


                TextColumn::make('name')
                    ->label('Full Name')
                    ->getStateUsing(fn($record) => "{$record->name} {$record->last_name}")
                    ->searchable(['name', 'last_name'])
                    ->sortable(),

                BadgeColumn::make('unit')
                    ->label('Unit')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),

                BadgeColumn::make('staff')
                    ->label('Classification')
                    ->sortable()
                    ->searchable()
                    ->alignCenter()
                    ->colors([
                        'info' => 'faculty',
                        'success' => 'admin',
                        'warning' => 'REPS',
                    ])->formatStateUsing(fn(string $state): string => ucfirst(strtoupper($state))),

                TextColumn::make('ms_phd')
                    ->label('Highest Degree Attained')
                    ->sortable()
                    ->searchable()
                    ->limit(20)
                    ->tooltip(fn($state) => $state),

                BadgeColumn::make('systemrole')
                    ->label('User Role')
                    ->sortable()
                    ->colors([
                        'primary' => 'admin',
                        'warning' => 'super-admin',
                        'secondary' => 'user',
                        'danger' => 'secretary',
                    ])
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Contact')
                    ->sortable()
                    ->searchable(),
                BadgeColumn::make('rank_')
                    ->label('Rank')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('research_interests')
                    ->label('Research Interests')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('fields_of_specialization')
                    ->label('Fields of Specialization')
                    ->sortable()
                    ->searchable(),

            ])
            ->filters([
                SelectFilter::make('staff')
                    ->label('User Classification')
                    ->options([
                        'admin' => 'Admin',
                        'faculty' => 'Faculty',
                        'REPS' => 'REPS',
                    ])
                    ->query(function ($query, $data) {
                        if ($data['value'] === 'admin') {
                            return $query->where('staff', 'admin');
                        } elseif ($data['value'] === 'faculty') {
                            return $query->where('staff', 'faculty');
                        } elseif ($data['value'] === 'REPS') {
                            return $query->where('staff', 'REPS');
                        }
                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->color('secondary'),

                Tables\Actions\DeleteAction::make()
                    ->action(function (Model $record) {
                        $record->forceDelete();
                    })
                    ->requiresConfirmation()
                    ->color('danger')
                    ->label('Delete Permanently'),

                //Tables\Actions\EditAction::make()
                //->color('secondary'),


            ])

            ->headerActions([

                Tables\Actions\CreateAction::make()->label('Create New User')
                    ->color('secondary')->icon('heroicon-o-pencil-square'),


                Action::make('Export')
                    ->form([
                        Forms\Components\Select::make('role')
                            ->label('Export Type')
                            ->options([
                                'admin' => 'Admin',
                                'faculty' => 'Faculty',
                                'REPS' => 'REPS',
                            ])
                            ->required(),
                    ])
                    ->modalButton('Download')
                    ->color('primary')
                    ->action(function (array $data) {
                        $users = User::where('staff', $data['role'])->get();
                        $title = ucfirst($data['role']) . ' List';
                        $pdf = Pdf::loadView('exports.faculty', compact('users', 'title'));


                        return response()->streamDownload(
                            fn() => print($pdf->output()),
                            "{$data['role']}_list.pdf"
                        );
                    }),




            ])
            ->searchable()
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(fn(Collection $records) => $records->each->forceDelete())
                    ->label('Delete Permanently')
                    ->requiresConfirmation(),
            ]);


    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCreateUsers::route('/'),
            'create' => Pages\CreateCreateUser::route('/create'),
            'edit' => Pages\EditCreateUser::route('/{record}/edit'),
            'view' => Pages\ViewCreateUser::route('/{record}'),
        ];
    }


}
