<?php


namespace App\Filament\Resources;




use App\Filament\Resources\InternationalPublicationAwardResource\Pages;
use App\Filament\Resources\InternationalPublicationAwardResource\RelationManagers;
use App\Models\InternationalPublicationAward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class InternationalPublicationAwardResource extends Resource
{
    protected static ?string $model = InternationalPublicationAward::class;


    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'Awards';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadgeColor(): string
    {
        return 'secondary'; 
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                ->label('Title')
                ->required()
                ->maxLength(255),


            Forms\Components\DatePicker::make('date_published')
                ->label('Date Published')
                ->required(),


            Forms\Components\DatePicker::make('date_awarded')
                ->label('Date Awarded')
                ->nullable(),

            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                ->label('Title')
                ->sortable()
                ->searchable(),


            Tables\Columns\TextColumn::make('date_published')
                ->label('Date Published')
                ->sortable()
                ->date(),


            Tables\Columns\TextColumn::make('date_awarded')
                ->label('Date Awarded')
                ->sortable()
                ->date()
                ->placeholder('N/A'),
            ])
            ->filters([
                Tables\Filters\Filter::make('date_published')
                ->form([
                    Forms\Components\DatePicker::make('date_published')
                        ->label('Filter by Date Published')
                ])
                ->query(fn (Builder $query, array $data) =>
                    isset($data['date_published'])
                        ? $query->whereDate('date_published', $data['date_published'])
                        : $query
                ),


            Tables\Filters\Filter::make('date_awarded')
                ->form([
                    Forms\Components\DatePicker::make('date_awarded')
                        ->label('Filter by Date Awarded')
                ])
                ->query(fn (Builder $query, array $data) =>
                    isset($data['date_awarded'])
                        ? $query->whereDate('date_awarded', $data['date_awarded'])
                        : $query
                ),
            ])
            ->actions([
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
            'index' => Pages\ListInternationalPublicationAwards::route('/'),
            'create' => Pages\CreateInternationalPublicationAward::route('/create'),
            'edit' => Pages\EditInternationalPublicationAward::route('/{record}/edit'),
        ];
    }
}
