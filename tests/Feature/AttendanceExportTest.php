<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AttendanceExportTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $employee = Employee::factory()->create();
        Attendance::create([
            'employee_id' => $employee->id,
            'check_in' => '2023-10-25 08:00:00',
            'check_out' => '2023-10-25 17:00:00',
            'created_at' => '2023-10-25 00:00:00',
            'updated_at' => '2023-10-25 00:00:00',
        ]);
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_export_daily_excel()
    {
        $response = $this->get(route('attendance.export.excel', ['date' => '2023-10-25']));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertFileExists(storage_path("app/public/attendance_report_2023-10-25.xlsx"));
    }

    public function test_export_daily_pdf()
    {
        $response = $this->get(route('attendance.export.pdf', ['date' => '2023-10-25']));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertEquals("attachment; filename=\"attendance_report_2023-10-25.pdf\"", $response->headers->get('Content-Disposition'));
    }
}
