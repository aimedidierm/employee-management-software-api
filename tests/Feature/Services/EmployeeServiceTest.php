<?php

namespace Tests\Unit;

use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationErrorException;
use App\Models\Employee;
use App\Service\EmployeeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeServiceTest extends TestCase
{
    use RefreshDatabase;

    protected EmployeeService $employeeService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->employeeService = new EmployeeService();
    }

    public function test_get_all_employees_returns_employees()
    {
        Employee::factory()->count(3)->create();

        $employees = $this->employeeService->getAllEmployees();

        $this->assertCount(3, $employees);
    }

    public function test_get_employee_by_id_returns_employee()
    {
        $employee = Employee::factory()->create();

        $result = $this->employeeService->getEmployeeById($employee->id);

        $this->assertEquals($employee->id, $result->id);
    }

    public function test_get_employee_by_id_throws_not_found_exception()
    {
        $this->expectException(NotFoundException::class);
        $this->employeeService->getEmployeeById(999);
    }

    public function test_update_employee_success()
    {
        $employee = Employee::factory()->create();
        $data = ['name' => 'Updated Name'];

        $result = $this->employeeService->updateEmployee($employee->id, $data);

        $this->assertEquals('Updated Name', $result->name);
    }

    public function test_update_employee_throws_not_found_exception()
    {
        $this->expectException(NotFoundException::class);
        $this->employeeService->updateEmployee(999, ['name' => 'New Name']);
    }

    public function test_create_employee_success()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone_number' => '0788888888',
            'position' => 'Developer',
        ];

        $this->employeeService->createEmployee($data);

        $this->assertDatabaseHas('employees', $data);
    }

    public function test_create_employee_throws_validation_exception_if_exists()
    {
        Employee::factory()->create(['email' => 'johndoe@example.com']);

        $this->expectException(ValidationErrorException::class);
        $this->employeeService->createEmployee(['email' => 'johndoe@example.com']);
    }

    public function test_delete_employee_success()
    {
        $employee = Employee::factory()->create();

        $this->employeeService->deleteEmployee($employee->id);

        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }

    public function test_delete_employee_throws_not_found_exception()
    {
        $this->expectException(NotFoundException::class);
        $this->employeeService->deleteEmployee(999);
    }
}
