<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Awards and Recognitions Export</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h2 {
            font-size: 18px;
            margin-top: 30px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-size: 14px;
            font-weight: bold;
        }

        td {
            font-size: 12px;
        }
    </style>
</head>
<body>

    <h2>Awards and Recognitions</h2>
    <table>
        <thead>
            <tr>
                <th>Type of Award</th>
                <th>Title of Paper or Award</th>
                <th>Name(s) of Awardee/Recipient</th>
                <th>Granting Organization</th>
                <th>Date Awarded</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $record)
                <tr>
                    <td>{{ $record->award_type }}</td>
                    <td>{{ $record->award_title }}</td>
                    <td>{{ $record->name }}</td>
                    <td>{{ $record->granting_organization }}</td>
                    <td>{{ $record->date_awarded }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
