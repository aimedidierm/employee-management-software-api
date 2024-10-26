<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{__('Daily Attendance Report')}} - {{ $date }}</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 8px;
        }
    </style>
</head>

<body>
    <h2>{{__('Attendance Report for')}} {{ $date }}</h2>
    <table>
        <thead>
            <tr>
                <th>{{__('Employee ID')}}</th>
                <th>{{__('Employee Name')}}</th>
                <th>{{__('Position')}}</th>
                <th>{{__('Check In')}}</th>
                <th>{{__('Check Out')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
            <tr>
                <td>{{ $attendance->employee_id }}</td>
                <td>{{ $attendance->employee->name }}</td>
                <td>{{ $attendance->employee->position }}</td>
                <td>{{ $attendance->check_in }}</td>
                <td>{{ $attendance->check_out }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>