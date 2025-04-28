<!DOCTYPE html>
<html>
<head>
    <title>Appointments Export</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>Appointments List</h2>

    <table>
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Type of Appointment</th>
                <th>Position</th>
                <th>Appointment</th>
                <th>Effectivity Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->full_name }}</td>
                    <td>{{ $appointment->type_of_appointments }}</td>
                    <td>{{ $appointment->position }}</td>
                    <td>{{ $appointment->appointment }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_effectivity_date)->format('F d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
