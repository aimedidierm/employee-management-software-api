<?php

use App\Exports\AttendanceExport;
use App\Exports\AttendancePDFExport;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->as('admin.')->group(function () {
    Route::apiResource('/employees', EmployeeController::class)->only('index', 'store', 'show', 'update', 'destroy');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/employees/{employee}/check-in', [AttendanceController::class, 'checkIn']);
    Route::post('/employees/{employee}/check-out', [AttendanceController::class, 'checkOut']);

    Route::prefix('attendance')->as('attendance')->group(function () {
        Route::post('/', [AttendanceController::class, 'store']);
        Route::get('/', [AttendanceController::class, 'index']);
        Route::prefix('export')->as('export')->group(function () {
            Route::get('/excel/{date}', [AttendanceExport::class, 'exportDailyExcel']);
            Route::get('/pdf/{date}', [AttendancePDFExport::class, 'exportDailyPDF']);
        });
    });
});
