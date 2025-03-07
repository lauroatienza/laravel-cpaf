<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChapterInBookResource\Pages;
use App\Models\ChapterInBook;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\EditAction;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;



class ChapterInBookResource extends Resource
{
    protected static ?string $model = ChapterInBook::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Programs'; 
    protected static ?string $navigationLabel = 'Chapters in Book'; 
    protected static ?string $slug = 'chapters-in-book'; 

public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
{
    return parent::getEloquentQuery()->where('user_id', Auth::id());
}
    // Navigation Badge - Counts number of records
    public static function getNavigationBadge(): ?string
    {
        return static::$model::where('user_id', Auth::id())->count();
    }
    
    public static function resolveQueryUsing($query)
    {
        return $query->where('user_id', Auth::id()); // Only fetch records belonging to the logged-in user
    }

    public static function form(Forms\Form $form): Forms\Form
{
    return $form
        ->schema([
            // Hidden field to store the logged-in user's ID
            Hidden::make('user_id')
                ->default(Auth::id()),

            TextInput::make('full_name')
                ->label('Full Name')
                ->default(Auth::user()->name . ' ' . Auth::user()->last_name) // Auto-fill full name
                ->disabled() // Makes it visible but uneditable
                ->required(),

            TextInput::make('title')
                ->label('Title')
                ->required(),

            TextInput::make('co-authors')
                ->label('Co-Authors')
                ->nullable(),

            DatePicker::make('date_publication')
                ->label('Publication Date')
                ->nullable(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
{
    return $table
        ->columns([
            TextColumn::make('user.name')
                ->label('Full Name')
                ->sortable()
                ->searchable()
                ->getStateUsing(function ($record) {
                    return $record->user->name . ' ' . $record->user->last_name;
                }),

            TextColumn::make('title')
                ->sortable()
                ->searchable(),

            TextColumn::make('co-authors')
                ->sortable()
                ->searchable(),

            TextColumn::make('date_publication')
                ->sortable()
                ->date('Y-m-d'),
            ])
            ->filters([
                Filter::make('Recent')
                    ->query(fn ($query) => $query->orderBy('created_at', 'desc')),
            ])
            ->actions([
                EditAction::make()->label('Edit'),
                DeleteAction::make()->label('Delete'),
            ])
            ->bulkActions([
                BulkAction::make('Delete Selected')
                    ->action(fn ($records) => $records->each->delete()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChapterInBooks::route('/'),
            'create' => Pages\CreateChapterInBook::route('/create'),
            'edit' => Pages\EditChapterInBook::route('/{record}/edit'),
        ];
    }
}
