<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Service\AttendanceService;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /** 
     * Display a listing of the resource. 
     */
    public function index()
    {
        $attendances = $this->attendanceService->getAllAttendances();
        return response()->json($attendances);
    }

    public function checkIn(AttendanceRequest $request)
    {
        $response = $this->attendanceService->checkIn($request->employee_id);
        return response()->json(['message' => $response['message']], $response['status']);
    }

    public function checkOut(AttendanceRequest $request)
    {
        $response = $this->attendanceService->checkOut($request->employee_id);
        return response()->json(['message' => $response['message']], $response['status']);
    }
}
