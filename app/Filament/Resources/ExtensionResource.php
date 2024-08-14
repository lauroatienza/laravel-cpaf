<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExtensionResource\Pages;
use App\Filament\Resources\ExtensionResource\RelationManagers;
use App\Models\Extension;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExtensionResource extends Resource
{
    protected static ?string $model = Extension::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Programs';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('contributing_unit')->label('Contributing unit')
                ->options([
                    'CSPPS' => 'CSPPS',
                    'CISC' => 'CISC',
                    'CPAf' => 'CPAf',
                    'IGRD' => 'IGRD',
                ])->required()->default('CPAf'),
                TextInput::make('title')->label('Title')->required(),

                Select::make('faculty_id')
                     ->relationship('faculty','first_name')->label('Project Lead'),

                DatePicker::make('start_date')->label('Start Date')
                    ->format('Y/m/d')->required(),
                DatePicker::make('end_date')->label('End Date')
                    ->format('Y/m/d')->required(),

                DatePicker::make('extension_date')->label('Extension Date')
                    ->format('Y/m/d')->nullable(),
                TextInput::make('event_highlight')->label('Event highlight'),
                Select::make('has_gender_component')->label('Has gender component')
                ->options([
                    'yes' => 'Yes',
                    'no' => 'No',
                ])->required()->default('no'),
                
                Select::make('status')->label('Status')
                ->options([
                    'Completed' => 'Completed',
                    'On-going' => 'On-going',
                ])->required()->default('On-going'),

                TextInput::make('objectives'),
                TextInput::make('expected_output'),
                TextInput::make('no_months_orig_timeframe')->default('N/A')->label('Months No. from original timeframe'),
                TextInput::make('name_of_researchers')->required()->placeholder('Use comma to separate names'),

                TextInput::make('source_funding')->required(),
                Select::make('category_source_funding')->label('Source of Funding Category')
                ->options([
                    'UP Entity' => 'UP Entity',
                    'RP Gov' => 'RP Government Entity or Public Sector Entity',
                    'RP Priv' => 'RP Private Sector Entity',
                    'Foreign Non-Dom' => 'Foreign or Non-Domestic Entity',
                ])->required(),

                TextInput::make('budget')->numeric()->label('Budget (in Philippine Peso)'),
                Select::make('type_funding')->label('Type of Funding')
                ->options([
                    'Externally Funded' => 'Externally Funded',
                    'UPLB Basic Research' => 'UPLB Basic Research',
                    'UP System' => 'UP System',
                    'In-house' => 'In-house',
                ])->required(),

                TextInput::make('pdf_image_1')->default('N/A'),
                DatePicker::make('completed_date')->label('Completed Date')
                    ->format('Y/m/d')->nullable(),

                TextInput::make('sdg_theme')->default('N/A')->label('SDG Theme'),
                TextInput::make('agora_theme')->default('N/A')->label('AGORA Theme'),

                Select::make('climate_ccam_initiative')->label('Climate Initiative')
                ->options([
                    'yes' => 'Yes',
                    'no' => 'No',
                ])->required()->default("no"),

                Select::make('disaster_risk_reduction')->label('Disaster Risk Reduction')
                ->options([
                    'yes' => 'Yes',
                    'no' => 'No',
                ])->required()->default("no"),

                Select::make('flagship_theme')->label('UP Flagship Theme')
                ->options([
                    'FP1' => 'FP1: Academic Excellence',
                    'FP2' => 'FP2: Inclusive University Admissions',
                    'FP3' => 'FP3: Innovation Hubs, S&T Parks',
                    'FP4' => 'FP4: ODeL',
                    'FP5' => 'FP5: Archipelagic & Ocean Virtual University',
                    'FP6' => 'FP6: Active and Collaborative Partnerships',
                    'FP7' => 'FP7: Arts and Culture',
                    'FP8' => 'FP8: Expansion of Public Service',
                    'FP9' => 'FP9: QMSQA',
                    'FP10' => 'FP10: Digital Transformation',
                ])->required()->nullable(),

                Select::make('pbms_upload_status')->label('PBMS Upload Status')
                ->options([
                    'uploaded' => 'Uploaded',
                    'pending' => 'Pending',
                ])->required()->default("pending"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
}
