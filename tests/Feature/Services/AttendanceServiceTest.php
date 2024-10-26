<?php

namespace Tests\Unit;

use App\Models\Attendance;
use App\Models\Employee;
use App\Service\AttendanceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AttendanceServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $attendanceService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->attendanceService = new AttendanceService();
    }

    public function testGetAllAttendances()
    {
        $this->assertCount(0, $this->attendanceService->getAllAttendances());
        $employee = Employee::factory()->create();
        Attendance::create(['employee_id' => $employee->id, 'check_in' => now()]);
        $attendances = $this->attendanceService->getAllAttendances();

        $this->assertCount(1, $attendances);
    }

    public function testCheckIn()
    {
        Mail::fake();

        $employee = Employee::factory()->create();

        $response = $this->attendanceService->checkIn($employee->id);
        $this->assertEquals(Response::HTTP_CREATED, $response['status']);
        $this->assertDatabaseHas('attendances', ['employee_id' => $employee->id]);
        $response = $this->attendanceService->checkIn($employee->id);
        $this->assertEquals(Response::HTTP_CONFLICT, $response['status']);
    }

    public function testCheckOut()
    {
        Mail::fake();

        $employee = Employee::factory()->create();
        Attendance::create(['employee_id' => $employee->id, 'check_in' => now()]);

        $response = $this->attendanceService->checkOut($employee->id);
        $this->assertEquals(Response::HTTP_OK, $response['status']);
        $this->assertDatabaseHas('attendances', ['employee_id' => $employee->id]);

        $response = $this->attendanceService->checkOut($employee->id);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response['status']);
    }
}
