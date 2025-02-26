<?php

namespace App\Filament\Widgets;

use App\Models\research;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use InteractsWithPageFilters;

class LatestResearch extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = "full";
    public function table(Table $table): Table
    {
        return $table
            ->query(
                research::query()
                ->latest()
                ->limit(5)
            )
            ->columns([
            TextColumn::make('contributing_unit')->label('Contributing Unit')
                ->sortable()->searchable(),
            TextColumn::make('title')->label('Title')
                ->sortable()->searchable(),
            TextColumn::make('start_date')
                ->sortable()->searchable(),
            TextColumn::make('end_date')
                ->sortable()->searchable(),
            ]);
    }
}
