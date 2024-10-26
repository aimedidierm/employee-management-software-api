<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Service\EmployeeService;
use Symfony\Component\HttpFoundation\Response;

class EmployeeController extends Controller
{
    public function __construct(private EmployeeService $employeeService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->employeeService->getAllEmployees();
        } catch (\Throwable $th) {
            return $this->formatExceptionError($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeRequest $request)
    {
        try {
            $this->employeeService->createEmployee($request->validated());
            return response(null, Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->formatExceptionError($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $employeeId)
    {
        try {
            return $this->employeeService->getEmployeeById($employeeId);
        } catch (\Throwable $th) {
            return $this->formatExceptionError($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeRequest $request, int $employeeId)
    {
        try {
            $updatedEmployee = $this->employeeService->updateEmployee($employeeId, $request->validated());
            return response()->json($updatedEmployee);
        } catch (\Throwable $th) {
            return $this->formatExceptionError($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $employeeId)
    {
        try {
            $this->employeeService->deleteEmployee($employeeId);
            return response()->noContent();
        } catch (\Throwable $th) {
            return $this->formatExceptionError($th);
        }
    }
}
