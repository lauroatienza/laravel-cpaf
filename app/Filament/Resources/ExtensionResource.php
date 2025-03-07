<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExtensionResource\Pages;
use App\Filament\Resources\ExtensionResource\RelationManagers;
use App\Models\Extensionnew;
use App\Models\Users;
use Doctrine\DBAL\Schema\Column;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Auth;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;


class ExtensionResource extends Resource
{
    protected static ?string $model = Extensionnew::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Programs';

    protected static ?string $navigationLabel = 'Extension Involvements';    protected static ?int $navigationSort = 3;
    protected static ?string $pluralLabel = 'Extension Involvements';
    public static function getNavigationBadge(): ?string
    {
        return static::$model::where('user_id', Auth::id())->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Full Name')
                    ->default(Auth::user()->name. ' ' . Auth::user()->last_name) // Gets logged-in user's name
                    ->hidden()
                    ->required(),

                Select::make('extension_involvement')
                ->label('Type of Extension Involvement')
                ->options([
                    'Resource Person' => 'Resource Person',
                    'Seminar Speaker' => 'Seminar Speaker',
                    'Reviewer' => 'Reviewer',
                    'Evaluator' => 'Evaluator',
                    'Moderator' => 'Moderator',
                    'Session Chair' => 'Session Chair',
                    'Editor' => 'Editor',
                    'Examiner' => 'Examiner',
                    'Other' => 'Other (Specify)', // Adds "Other" as an option
                ])
                ->reactive(), // Allows dynamic updates based on selection
                
                Select::make('location')
                ->label('Type of Extension')
                ->options([
                    'Training' => 'Training',
                    'Conference' => 'Conference',
                    'Editorial Team/Board' => 'Editorial Team/Board',
                    'Workshop' => 'Workshop',
                    'Other' => 'Other (Specify)', // Adds "Other" as an option
                ])
                ->reactive(), // Allows dynamic updates based on selection

            TextInput::make('custom_involvement')
                ->label('Specify Other')
                ->hidden(fn ($get) => $get('type_of_involvement') !== 'Other') // Show only if "Other" is selected
                ->maxLength(255),

                TextInput::make('event_title')
                ->label("Event Title"),

                TextInput::make('venue')
                ->label("Venue and Location"),
                
                DatePicker::make('activity_date')
                ->label('Activity Date'),

            ])->columns(1);
    }  

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                /*TextColumn::make('contributing_unit')->label('Contributing Unit')
                ->sortable()->searchable(),
                TextColumn::make('title')->label('Title')
                ->sortable()->searchable(),
                TextColumn::make('faculty.first_name')->label("Project Leader")
                ->sortable()->searchable(),
                TextColumn::make('start_date')
                ->sortable()->searchable(),
                TextColumn::make('end_date')
                ->sortable()->searchable(),
                */
               // IconColumn::make('pbms_upload_status')
               // ->icon(fn (string $state): string => match ($state) {
                   //   'uploaded' => 'heroicon-o-check-badge',
                   //  'pending' => 'heroicon-o-clock',
                
                  //  })
                TextColumn::make('extension_involvement')->label('Type of Extension Involvement')
                ->sortable()->searchable(),
                TextColumn::make('location')->label('Type of Extension')
                ->sortable()->searchable(),
                TextColumn::make('event_title')->label('Event Title')
                ->sortable()->searchable(),
                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListExtensions::route('/'),
            'create' => Pages\CreateExtension::route('/create'),
            'view' => Pages\ViewExtension::route('/{record}'),
            'edit' => Pages\EditExtension::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->where('user_id', Auth::user()->id);
}

}
