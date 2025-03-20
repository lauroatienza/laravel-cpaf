<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JournalArticleResource\Pages;
use App\Models\JournalArticle;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Facades\Auth;

class JournalArticleResource extends Resource
{
    protected static ?string $model = JournalArticle::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Programs';
    protected static ?string $navigationLabel = 'Journal Articles';
    protected static ?string $slug = 'journal-articles';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::id());
    }

    public static function getNavigationBadge(): ?string
    {
        return static::$model::where('user_id', Auth::id())->count();
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'secondary';
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Hidden::make('user_id')
                    ->default(Auth::id()),

                TextInput::make('full_name')
                    ->label('Full Name')
                    ->default(Auth::user()->name . ' ' . Auth::user()->last_name)
                    ->disabled()
                    ->required(),

                TextInput::make('authors')
                    ->label('Authors')
                    ->required(),

                TextInput::make('article_title')
                    ->label('Article Title')
                    ->required(),

                TextInput::make('journal_name')
                    ->label('Journal Name')
                    ->required(),

                DatePicker::make('date_published')
                    ->label('Date Published')
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Name')
                    ->sortable()
                    ->searchable()
                    ->getStateUsing(fn ($record) => $record->user->name . ' ' . $record->user->last_name),

                TextColumn::make('authors')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('article_title')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('journal_name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('date_published')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Filter::make('Recent')
                    ->query(fn ($query) => $query->orderBy('date_published', 'desc')),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('Delete Selected')
                    ->action(fn ($records) => $records->each->delete()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJournalArticles::route('/'),
            'create' => Pages\CreateJournalArticle::route('/create'),
            'edit' => Pages\EditJournalArticle::route('/{record}/edit'),
        ];
    }
}
