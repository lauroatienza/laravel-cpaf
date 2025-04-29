<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $type === 'MOA' ? 'MOA Documents' : ($type === 'MOU' ? 'MOU Documents' : 'Documents Export') }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            word-wrap: break-word;
        }
        th {
            background-color: #f2f2f2;
        }
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h2>
    {{ $type === 'MOA' ? 'MOA Documents' : ($type === 'MOU' ? 'MOU Documents' : 'Documents Export') }}
</h2>

<table>
    <thead>
        <tr>
            <th>Unit</th>
            <th>Type</th>
            <th>Title</th>
            <th>Partner</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Training</th>
            <th>Tech Service</th>
            <th>Info Dissemination</th>
            <th>Consultancy</th>
            <th>Community Outreach</th>
            <th>Tech Transfer</th>
            <th>Organizing Events</th>
            <th>Scope of Work</th>
            <th>File Path</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($documents as $doc)
        <tr>
            <td>{{ $doc->contributing_unit }}</td>
            <td>{{ str_contains($doc->partnership_type, 'MOA') ? 'MOA' : 'MOU' }}</td>
            <td>{{ $doc->extension_title }}</td>
            <td>{{ $doc->partner_stakeholder }}</td>
            <td>{{ $doc->start_date }}</td>
            <td>{{ $doc->end_date }}</td>
            <td>{{ $doc->training_courses ? 'YES' : '' }}</td>
            <td>{{ $doc->technical_advisory_service ? 'YES' : '' }}</td>
            <td>{{ $doc->information_dissemination ? 'YES' : '' }}</td>
            <td>{{ $doc->consultancy ? 'YES' : '' }}</td>
            <td>{{ $doc->community_outreach ? 'YES' : '' }}</td>
            <td>{{ $doc->technology_transfer ? 'YES' : '' }}</td>
            <td>{{ $doc->organizing_events ? 'YES' : '' }}</td>
            <td>{{ $doc->scope_of_work }}</td>
            <td>{{ $doc->documents_file_path }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
