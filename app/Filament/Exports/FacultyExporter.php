<?php

namespace App\Filament\Exports;

use App\Models\Faculty;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class FacultyExporter extends Exporter
{
    protected static ?string $model = Faculty::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('first_name'),
            ExportColumn::make('research_id'),
            ExportColumn::make('last_name'),
            ExportColumn::make('middle_name'),
            ExportColumn::make('fullname'),
            ExportColumn::make('employee_category'),
            ExportColumn::make('employment_status'),
            ExportColumn::make('unit'),
            ExportColumn::make('ms_phd'),
            ExportColumn::make('designation'),
            ExportColumn::make('fulltime_partime'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your faculty export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
