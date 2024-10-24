<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Mail\AttendanceRecorded;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Support\Facades\Mail;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendances = Attendance::with('employee')->whereDate('check_in', now()->toDateString())->get();
        return response()->json($attendances);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttendanceRequest $request)
    {
        $employee = Employee::findOrFail($request->employee_id);


        $attendance = Attendance::create([
            'employee_id' => $employee->id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out
        ]);

        Mail::to($employee->email)->queue(new AttendanceRecorded($attendance));

        return response()->json(['message' => 'Attendance recorded and email sent.'], 201);
    }
}
