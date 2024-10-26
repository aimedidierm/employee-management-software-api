<?php

namespace App\Service;

use App\Exceptions\NotFoundException;
use App\Exceptions\ServerErrorException;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AttendanceRecorded;
use Symfony\Component\HttpFoundation\Response;

class AttendanceService
{
    public function getAllAttendances()
    {
        try {
            return Attendance::latest()->with('employee')->get();
        } catch (\Throwable $th) {
            Log::error("Could not retrieve attendances", [
                "message" => $th->getMessage(),
                "trace" => $th->getTrace(),
            ]);
            throw new ServerErrorException(trans("Server error, could not retrieve attendances!"));
        }
    }

    public function checkIn(int $employeeId)
    {
        try {
            $employee = Employee::findOrFail($employeeId);

            $todayAttendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('check_in', now())
                ->exists();

            if ($todayAttendance) {
                return ['status' => Response::HTTP_CONFLICT, 'message' => 'Check-in record already exists for today.'];
            }

            $attendance = Attendance::create([
                'employee_id' => $employee->id,
                'check_in' => now(),
                'check_out' => null,
            ]);

            Mail::to($employee->email)->queue(new AttendanceRecorded($attendance));
            return ['status' => Response::HTTP_CREATED, 'message' => 'Check-in recorded and email sent.'];
        } catch (NotFoundException $e) {
            throw $e;
        } catch (\Throwable $th) {
            Log::error("Check-in error", [
                "employee_id" => $employeeId,
                "message" => $th->getMessage(),
                "trace" => $th->getTrace(),
            ]);
            throw new ServerErrorException(trans("Server error, could not record check-in!"));
        }
    }

    public function checkOut(int $employeeId)
    {
        try {
            $employee = Employee::findOrFail($employeeId);

            $attendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('check_in', now())
                ->whereNotNull('check_in')
                ->whereNull('check_out')
                ->latest()
                ->first();

            if (!$attendance) {
                return ['status' => Response::HTTP_NOT_FOUND, 'message' => 'No check-in record found for today.'];
            }

            $attendance->update(['check_out' => now()]);
            Mail::to($employee->email)->queue(new AttendanceRecorded($attendance));
            return ['status' => 200, 'message' => 'Check-out recorded and email sent.'];
        } catch (NotFoundException $e) {
            throw $e;
        } catch (\Throwable $th) {
            Log::error("Check-out error", [
                "employee_id" => $employeeId,
                "message" => $th->getMessage(),
                "trace" => $th->getTrace(),
            ]);
            throw new ServerErrorException(trans("Server error, could not record check-out!"));
        }
    }
}
