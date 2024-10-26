<?php

namespace Tests\Feature;

use App\Enums\EmployeePosition;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AttendanceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function testIndex()
    {
        $employee = Employee::factory()->create();
        Attendance::create(['employee_id' => $employee->id, 'check_in' => now()]);

        $response = $this->getJson(route('attendance.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1);
    }

    public function testCheckIn()
    {
        Mail::fake();

        $employee = Employee::factory()->create();

        $response = $this->postJson(route('attendance.check-in'), [
            'employee_id' => $employee->id,
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson(['message' => 'Check-in recorded and email sent.']);

        $response = $this->postJson(route('attendance.check-in'), [
            'employee_id' => $employee->id,
        ]);

        $response->assertStatus(Response::HTTP_CONFLICT)
            ->assertJson(['message' => 'Check-in record already exists for today.']);
    }

    public function testCheckOut()
    {
        Mail::fake();

        $employee = Employee::factory()->create(['position' => EmployeePosition::DEVELOPER->value]);
        Attendance::create(['employee_id' => $employee->id, 'check_in' => now()]);

        $response = $this->postJson(route('attendance.check-out'), [
            'employee_id' => $employee->id,
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(['message' => 'Check-out recorded and email sent.']);

        $response = $this->postJson(route('attendance.check-out'), [
            'employee_id' => $employee->id,
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'No check-in record found for today.']);
    }
}
