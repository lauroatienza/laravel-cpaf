<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Extension Program Export</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }

        h1 {
            font-size: 20px;
            text-align: center;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 14px;
            margin-top: 30px;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <h1>Extension Program</h1>

    <table>
        <thead>
            <tr>
                <th>Contributing Unit</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Extension Date</th>
                <th>Status</th>
                <th>Title</th>
                <th>Objectives</th>
                <th>Expected Output</th>
                <th>Timeframe (months)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $record)
                <tr>
                    <td>{{ $record->contributing_unit }}</td>
                    <td>{{ $record->start_date }}</td>
                    <td>{{ $record->end_date }}</td>
                    <td>{{ $record->extension_date }}</td>
                    <td>{{ $record->status }}</td>
                    <td>{{ $record->title_of_extension_program }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($record->objectives, 60) }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($record->expected_output, 60) }}</td>
                    <td>{{ $record->original_timeframe_months }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th>Researchers</th>
                <th>Project Leader</th>
                <th>Source of Funding</th>
                <th>Budget</th>
                <th>Type of Funding</th>
                <th>Fund Code</th>
                <th>PBMS Upload Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $record)
                <tr>
                    <td>{{ $record->researcher_names }}</td>
                    <td>{{ $record->project_leader }}</td>
                    <td>{{ $record->source_of_funding }}</td>
                    <td>{{ $record->budget }}</td>
                    <td>{{ $record->type_of_funding }}</td>
                    <td>{{ $record->fund_code }}</td>
                    <td>{{ $record->pbms_upload_status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
