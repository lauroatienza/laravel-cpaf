<!DOCTYPE html>
<html>
<head>
    <title>Extension Involvement PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>Extension Involvement Report</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Type of Extension Involvement</th>
                <th>Event Title</th>
                <th>Start Date</th>
                <th>Type of Extension</th>
                <th>Venue</th>
                <th>End Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $record->name }}</td>
                <td>{{ $record->extension_involvement }}</td>
                <td>{{ $record->event_title }}</td>
                <td>{{ $record->created_at }}</td>
                <td>{{ $record->extensiontype }}</td>
                <td>{{ $record->venue }}</td>
                <td>{{ $record->date_end }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
