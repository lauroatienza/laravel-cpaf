<!-- resources/views/exports/fsr_or_rsr_attachments.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>FSR/RSR Attachments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>FSR/RSR Attachments</h1>
    <table>
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Year</th>
                <th>Semester</th>
                <th>Uploaded File</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $record->user ? $record->user->name . ' ' . $record->user->last_name : 'N/A' }}</td>
                <td>{{ $record->year }}</td>
                <td>{{ $record->sem }}</td>
                <td><a href="{{ asset('storage/' . $record->file_upload) }}" target="_blank">Download</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
