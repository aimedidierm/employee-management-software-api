<?php

use App\Exports\AttendanceExport;
use App\Exports\AttendancePDFExport;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;


Route::group(["prefix" => "auth", "as" => "auth."], function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/password/forgot', [AuthController::class, 'forgotPassword'])->name('password.forget');
    Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('/employees', EmployeeController::class)->only('index', 'store', 'show', 'update', 'destroy');

    Route::prefix('/attendance')->as('attendance.')->group(function () {
        Route::apiResource('/', AttendanceController::class)->only('index');
        Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('check-in');
        Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('check-out');
        Route::prefix('/export')->as('export.')->group(function () {
            Route::get('/excel/{date}', [AttendanceExport::class, 'exportDailyExcel'])->name('excel');
            Route::get('/pdf/{date}', [AttendancePDFExport::class, 'exportDailyPDF'])->name('pdf');
        });
    });
});
