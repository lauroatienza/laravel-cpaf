<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacultyResource\Pages;
use App\Filament\Resources\FacultyResource\RelationManagers;
use App\Models\Faculty;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FacultyResource extends Resource
{
    protected static ?string $model = Faculty::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Faculty and REPS';
    protected static ?string $navigationGroup = 'Staff and Faculty';

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')->label('First Name')->required(),
                TextInput::make('last_name')->label('Last Name')->required(),
                TextInput::make('middle_name')->label('Middle Name')->required(),
                TextInput::make('designation')->label('Designation')->required(),

                Select::make('employee_category')->label('Employee Category')
                ->options([
                    'Faculty' => 'Faculty',
                    'REPS' => 'REPS',
                ])->required(),

                Select::make('fulltime_partime')->label('Full time/Part time')
                ->options([
                    'Full time' => 'Full time',
                    'Part time' => 'Part time',
                ])->required(),

                Select::make('employment_status')->label('Employment Status')
                ->options([
                    'Permanent' => 'Permanent',
                    'Temporary' => 'Temporary',
                ])->required(),

                Select::make('unit')->label('Unit')
                ->options([
                    'DO' => 'DO',
                    'KMO' => 'KMO',
                    'IGRD' => 'IGRD',
                    'CISC' => 'CISC',
                    'CSPPS' => 'CSPPS',
                ])->required(),

                Select::make('ms_phd')->label('Highest Degree Attained')
                ->options([
                    'BS' => 'BS',
                    'MS' => 'MS',
                    'PhD' => 'PhD',
                ])->required(),

                Repeater::make('internationalPublicationAwards')
                    ->relationship()
                    ->columnSpanFull()
                    ->label('International Publication Awards')
                    ->schema([
                        TextInput::make('title')->nullable()->columnSpanFull(),
                        DatePicker::make('date_published')->label('Date Published')
                          ->format('Y/m/d')->nullable(),
                        DatePicker::make('date_awarded')->label('Date Awarded')
                          ->format('Y/m/d')->nullable(),
                    ])
                    ->defaultItems(0)
                    ->columns(2),

                Repeater::make('otherNotableAwards')
                    ->relationship()
                    ->columnSpanFull()
                    ->label('Other Notable Awards')
                    ->schema([
                        TextInput::make('award_title')->nullable()->columnSpanFull(),
                        TextInput::make('award_desc')->nullable()->columnSpanFull(),
                        DatePicker::make('date_awarded')->label('Date Awarded')
                          ->format('Y/m/d')->nullable(), 
                    ])
                    ->defaultItems(0)
                    ->columns(2),

                    Repeater::make('journalArticles')
                    ->relationship()
                    ->columnSpanFull()
                    ->label('Journal Articles')
                    ->schema([
                        TextInput::make('authors')->nullable()->columnSpanFull(),
                        TextInput::make('article_title')->nullable()->columnSpanFull(),
                        TextInput::make('journal_name')->nullable()->columnSpanFull(),
                        DatePicker::make('date_published')->label('Date Published')
                          ->format('Y/m/d')->nullable(), 
                    ])
                    ->defaultItems(0)
                    ->columns(2),

                    Repeater::make('paperPresented')
                    ->relationship()
                    ->columnSpanFull()
                    ->label('Paper/Poster Presented')
                    ->schema([
                        TextInput::make('paper_title')->nullable()->columnSpanFull()->label('Title'),
                        DatePicker::make('date_presented')->label('Date Published')
                          ->format('Y/m/d')->nullable(),
                    ])
                    ->defaultItems(0)
                    ->columns(2),

                    Repeater::make('chapterBook')
                    ->relationship()
                    ->columnSpanFull()
                    ->label('Chapter in Book')
                    ->schema([
                        TextInput::make('title')->nullable()->columnSpanFull()->label('Title'),
                        TextInput::make('co-authors')->nullable()->columnSpanFull()->label('Co-authors'),
                        DatePicker::make('date_publication')->label('Date Published')
                          ->format('Y/m/d')->nullable(),
                    ])
                    ->defaultItems(0)
                    ->columns(2),

                    Repeater::make('trainingAttended')
                    ->relationship()
                    ->columnSpanFull()
                    ->label('Training Attended')
                    ->schema([
                        TextInput::make('training_title')->nullable()->columnSpanFull(),
                        TextInput::make('venue')->nullable()->columnSpanFull(),
                        TextInput::make('num_hours')->nullable()->columnSpanFull(),
                        DatePicker::make('start_date')->label('Start Date')
                          ->format('Y/m/d')->nullable(),
                          DatePicker::make('end_date')->label('End Date')
                          ->format('Y/m/d')->nullable(), 
                    ])
                    ->defaultItems(0)
                    ->columns(2),

                    Repeater::make('fsrorrsr')
                    ->relationship()
                    ->columnSpanFull()
                    ->label('FSR/RSR Attachment')
                    ->schema([
                        FileUpload::make('file_upload')->preserveFilenames()->columnSpan('full')->downloadable()->previewable(),
                        TextInput::make('year')->nullable()->columnSpanFull(),
                        Select::make('sem')->label('Coverage')
                            ->options([
                            'Jan-Jun' => 'Jan-Jun',
                            'Jul-Dec' => 'Jul-Dec',
                         ])->nullable()->columnSpanFull(),
                    ])
                    ->defaultItems(0)
                    ->columns(2),
              //  $table->string('fulltime_partime');
             //   $table->string('rating')->nullable();
              //  $table->string('citations')->nullable();
              //  $table->string('with_phd');
              //  $table->string('academic_background');
             //   $table->string('pursuing_phd');
             //   $table->string('pursuing_ms');
             //   $table->string('leave_status');
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')->label('First Name')->searchable()->sortable(),
                TextColumn::make('last_name')->label('Last Name')->searchable()->sortable(),
                TextColumn::make('middle_name')->label('Middle Name')->searchable()->sortable(),
                TextColumn::make('designation')->label('Designation')->searchable()->sortable(),
                TextColumn::make('employee_category')->label('Category')->searchable()->sortable(),
                TextColumn::make('research_count')->badge()->counts('research'),
            ])
            ->filters([
                SelectFilter::make('employee_category')
                    ->options([
                        'Faculty' => 'Faculty',
                        'REPS' => 'REPS',
                    ])->label("Filter by Employee Category"),

                SelectFilter::make('ms_phd')
                    ->options([
                        'BS' => 'BS',
                        'MS' => 'MS',
                        'PhD' => 'PhD',
                    ])->label("Filter by Educational Attainment"),


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
            'index' => Pages\ListFaculties::route('/'),
            'create' => Pages\CreateFaculty::route('/create'),
            'view' => Pages\ViewFaculty::route('/{record}'),
            'edit' => Pages\EditFaculty::route('/{record}/edit'),
        ];
    }
}
