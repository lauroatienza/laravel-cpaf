<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentsResource\Pages;
use App\Filament\Resources\StudentsResource\RelationManagers;
use App\Models\Students;
use App\Models\Sections;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentsResource extends Resource
{
    protected static ?string $model = Students::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Academic Group';
    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                TextInput::make('email'),
                Select::make('class_id')->live()->label('Class')
                ->relationship(name: 'class', titleAttribute: 'name'),

                Select::make('section_id')->label('Section')
                ->options(function (Get $get){
                    $classId = $get('class_id');

                   if ($classId){
                       return Sections::where ('class_id', $classId)->get()->pluck('name','id');  
                   }
                })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Name'),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('class_id')->label('Class')->badge(),
                TextColumn::make('section_id')->label('Section')->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudents::route('/create'),
            'edit' => Pages\EditStudents::route('/{record}/edit'),
        ];
    }
}
