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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Validation\Rules\Password;
class CreateUserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Add Users';

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $navigationGroup = 'User Management';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Primary Information')
                ->description('Fill up the following:')
                ->schema([
                TextInput::make('name') 
                -> label('First Name')
                ->required(),
                TextInput::make('email') -> label('Email')
            ->email()
            ->required(),
              
                TextInput::make('last_name') 
                -> label('Last Name')
                ->required(),
                
                TextInput::make('password')
                ->password()
                -> revealable()
                
                ->minLength(6)
                ->same('password_confirmation') // 👈 Ensure password matches
                ->dehydrated(fn ($state) => filled($state)) // Avoid saving empty passwords when editing
                ->rule(Password::default()),
                
           TextInput::make('middle_name') 
                -> label('Middle Name'),
            TextInput::make('password_confirmation')
            ->password()
            -> revealable()
            
            ->label('Confirm Password'),
                
            
            ])->columns(2),
            
            TextInput::make('employment_status') 
            -> label('Employment Status'),
            TextInput::make('designation') 
            -> label('Designation'),
            Select::make('unit')->label('Unit')
            ->options([
                'DO' => 'DO',
                'KMO' => 'KMO',
                'IGRD' => 'IGRD',
                'CISC' => 'CISC',
                'CSPPS' => 'CSPPS',
            ])->required(),
            Select::make('fulltime_partime')->label('Employment Type')
            ->options([
                'Full Time' => 'Full Time',
                'Part Time' => 'Part Time',
            ])->required(),
            Select::make('ms_phd')->label('Highest Degree Attained')
            ->options([
                'BS' => 'BS',
                'MS' => 'MS',
                'PhD' => 'PhD',
            ])->required(),
            Select::make('staff')
            ->options([
                'admin' => 'Admin',
                'faculty' => 'Faculty',
                'representative' => 'Representative',
            ])
            ->default('faculty')
            ->required(),

            Select::make('systemrole')
            ->options([
                'admin' => 'Admin',
                'user' => 'User',
                
            ])
            ->default('user')
            ->required(),

            ])->columns(3);
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
                TextColumn::make('fulltime_partime')
                ->label('Employment Type')
                ->sortable()
                ->searchable(),
                TextColumn::make('ms_phd')
                ->label('Highest Degree Attained')
                ->sortable()
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\CreateAction::make(), // ✅ Add this line
            ])
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
        ];
    }
}
