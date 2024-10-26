<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Confirmation</title>
</head>

<body>
    <p>Hello {{ $attendance->employee->name }},</p>

    <p>Your attendance has been recorded as follows:</p>

    <ul>
        <li>Check-in: {{ $attendance->check_in }}</li>
        <li>Check-out: {{ $attendance->check_out ?? 'Not recorded yet' }}</li>
    </ul>

    <p>Thank you!</p>
</body>

</html>