<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FSRorRSRResource\Pages;
use App\Models\FSRorRSR;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\EditAction;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;

class FSRorRSRResource extends Resource
{
    protected static ?string $model = FSRorRSR::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Programs'; 
    protected static ?string $navigationLabel = 'FSR or RSR'; 
    protected static ?string $slug = 'fsr-or-rsr'; 

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

    public static function resolveQueryUsing($query)
    {
        return $query->where('user_id', Auth::id());
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

                TextInput::make('year')
                    ->label('Year')
                    ->required(),

                Select::make('sem')
                    ->label('Semester')
                    ->options([
                        '1st' => '1st Semester',
                        '2nd' => '2nd Semester',
                    ])
                    ->required(),

                FileUpload::make('file_upload')
                    ->label('Upload File')
                    ->directory('fsr_rsr_files')
                    ->required(),
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
                    ->getStateUsing(fn ($record) => $record->user->name . ' ' . $record->user->last_name),

                TextColumn::make('year')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('sem')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('file_upload')
                    ->label('Uploaded File')
                    ->sortable(),
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
            'index' => Pages\ListFSRorRSRs::route('/'),
            'create' => Pages\CreateFSRorRSR::route('/create'),
            'edit' => Pages\EditFSRorRSR::route('/{record}/edit'),
        ];
    }
}
