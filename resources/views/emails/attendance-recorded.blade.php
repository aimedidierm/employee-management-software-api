<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{__('Attendance Confirmation')}}</title>
</head>

<body>
    <p>{{__('Hello')}} {{ $attendance->employee->name }},</p>

    <p>{{__('Your attendance has been recorded as follows:')}}</p>

    <ul>
        <li>{{__('Check-in:')}} {{ $attendance->check_in }}</li>
        <li>{{__('Check-out:')}} {{ $attendance->check_out ?? __('Not recorded yet') }}</li>
    </ul>

    <p>{{__('Thank you!')}}</p>
</body>

</html>