<?php

namespace Tests\Feature;

use App\Enums\EmployeePosition;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * Test listing all employees.
     *
     * @return void
     */
    public function test_index_returns_all_employees()
    {
        Employee::factory()->count(3)->create();

        $response = $this->getJson(route('employees.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3);
    }

    /**
     * Test storing a new employee.
     *
     * @return void
     */
    public function test_store_creates_a_new_employee()
    {
        $employeeData = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone_number' => '0788888888',
            'position' => EmployeePosition::DEVELOPER->value,
        ];

        $this->postJson(route('employees.store'), $employeeData);

        $this->assertDatabaseHas('employees', $employeeData);
    }

    /**
     * Test showing a single employee.
     *
     * @return void
     */
    public function test_show_returns_an_employee()
    {
        $employee = Employee::factory()->create();

        $response = $this->getJson(route('employees.show', $employee->id));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'id' => $employee->id,
                'name' => $employee->name,
                'email' => $employee->email,
                'position' => $employee->position,
            ]);
    }

    /**
     * Test updating an employee.
     *
     * @return void
     */
    public function test_update_modifies_an_employee()
    {
        $employee = Employee::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
            'position' => EmployeePosition::DESIGNER->value,
        ]);

        $updateData = [
            'name' => 'Jane Smith',
            'email' => 'janesmith@example.com',
            'position' => EmployeePosition::DEVELOPER->value,
        ];

        $response = $this->putJson(route('employees.update', $employee->id), $updateData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($updateData);

        $this->assertDatabaseHas('employees', $updateData);
    }

    /**
     * Test deleting an employee.
     *
     * @return void
     */
    public function test_destroy_deletes_an_employee()
    {
        $employee = Employee::factory()->create();

        $response = $this->deleteJson(route('employees.destroy', $employee->id)); // Use destroy route

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }
}
