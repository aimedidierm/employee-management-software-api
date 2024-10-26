<?php

namespace App\Service;

use App\Exceptions\NotFoundException;
use App\Exceptions\ServerErrorException;
use App\Exceptions\ValidationErrorException;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;

class EmployeeService
{
    public function getAllEmployees()
    {
        try {
            return Employee::all();
        } catch (\Throwable $th) {
            Log::error("Could not retrieve employees", [
                "message" => $th->getMessage(),
                "trace" => $th->getTrace(),
            ]);
            throw new ServerErrorException(trans("Server error, could not retrieve employees!"));
        }
    }

    public function getEmployeeById(int $employeeId)
    {
        try {
            $employee = Employee::find($employeeId);

            if (!$employee) {
                throw new NotFoundException(trans("Employee not found!"));
            }

            return $employee;
        } catch (NotFoundException $e) {
            throw $e;
        } catch (\Throwable $th) {
            Log::error("Could not retrieve employee", [
                "id" => $employeeId,
                "message" => $th->getMessage(),
                "trace" => $th->getTrace(),
            ]);
            throw new ServerErrorException(trans("Server error, could not retrieve employee!"));
        }
    }

    public function updateEmployee(int $employeeId, array $data)
    {
        try {
            $employee = Employee::find($employeeId);

            if (!$employee) {
                throw new NotFoundException(trans("Employee not found!"));
            }

            $employee->update($data);

            return $employee;
        } catch (NotFoundException $e) {
            throw $e;
        } catch (\Throwable $th) {
            Log::error("Could not update employee", [
                "id" => $employeeId,
                "data" => $data,
                "message" => $th->getMessage(),
                "trace" => $th->getTrace(),
            ]);
            throw new ServerErrorException(trans("Server error, could not update employee!"));
        }
    }

    public function createEmployee(array $data)
    {
        try {
            $employeeExists = Employee::where("email", $data['email'])->exists();
            if ($employeeExists) {
                throw new ValidationErrorException(trans("Employee already exists!"));
            }

            return Employee::create($data);
        } catch (ValidationErrorException $e) {
            throw $e;
        } catch (\Throwable $th) {
            Log::error("Could not create employee", [
                "data" => $data,
                "message" => $th->getMessage(),
                "trace" => $th->getTrace(),
            ]);
            throw new ServerErrorException(trans("Server error, could not create employee!"));
        }
    }

    public function deleteEmployee(int $employeeId)
    {
        try {
            $employee = Employee::find($employeeId);

            if (!$employee) {
                throw new NotFoundException(trans("Employee not found!"));
            }

            $employee->delete();
        } catch (NotFoundException $e) {
            throw $e;
        } catch (\Throwable $th) {
            Log::error("Could not delete employee", [
                "id" => $employeeId,
                "message" => $th->getMessage(),
                "trace" => $th->getTrace(),
            ]);
            throw new ServerErrorException(trans("Server error, could not delete employee!"));
        }
    }
}
