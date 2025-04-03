<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CreateUserResource\Pages;
use App\Filament\Resources\CreateUserResource\RelationManagers;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;

use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rules\Password;
class CreateUserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Users';

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $navigationGroup = 'User Management';

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }
    public static function getNavigationBadgeColor(): string
{
    return 'secondary'; 
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
                        ->dehydrated(fn ($state) => filled($state))
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
                    'BS' => 'BS',
                    'MS' => 'MS',
                    'PhD' => 'PhD',
                ])
                ->required(),
            Select::make('staff')
                ->options([
                    'admin' => 'Admin',
                    'faculty' => 'Faculty',
                    'representative' => 'Representative',
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
                        $record->syncRoles([$state]); // ✅ Sync Spatie role
                    }
                }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
           
                
            
                  

                TextColumn::make('name')
                ->label('Name')
                ->sortable()
                ->searchable(),
                TextColumn::make('last_name')
                ->label('Last Name')
                ->sortable()
                ->searchable(),
                TextColumn::make('unit')
                ->label('Unit')
                ->sortable()
                ->searchable(),
                BadgeColumn::make('staff')
                ->label('Position')
                ->sortable()
                ->searchable()
                ->formatStateUsing(fn (string $state): string => ucfirst(strtolower($state))),
                TextColumn::make('fulltime_partime')
                ->label('Employment Type')
                ->sortable()
                ->searchable(),
                BadgeColumn::make('ms_phd')
                ->label('Highest Degree Attained')
                ->sortable()
                ->color('secondary')
                ->searchable(),
                BadgeColumn::make('systemrole')
                ->label('User Role')
                ->sortable()
                ->color('secondary')
                ->searchable(),
                TextColumn::make('email')
                ->label('Contact')
                ->sortable()
                ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->color('secondary'),
                
                
            ])
            ->headerActions([
                
                Tables\Actions\CreateAction::make()->label('Create New User')
                    ->color('secondary') ->icon('heroicon-o-pencil-square'),
                Action::make('Export')
                    ->form([
                        Forms\Components\Select::make('role')
                            ->label('Export Type')
                            ->options([
                                'admin' => 'Admin',
                                'faculty' => 'Faculty',
                                'representative' => 'Representative',
                            ])
                            ->required(),
                    ])
                    ->modalButton('Download')
                    ->color('gray')
                    ->action(function (array $data) {
                        $users = User::where('staff', $data['role'])->get();
                        $pdf = Pdf::loadView('exports.faculty', compact('users'));
            
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            "{$data['role']}_list.pdf"
                        );
                    }),
                    
                
            ])
            ->searchable()
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
   //CREATED BY JULIUS ASHER P. AUSTRIA HARD CODED NO GPT
}