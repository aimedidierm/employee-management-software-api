<?php

namespace App\Exports;

use App\Models\Attendance;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AttendanceExport
{
    public function exportDailyExcel($date)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Employee ID');
        $sheet->setCellValue('B1', 'Employee Name');
        $sheet->setCellValue('C1', 'Position');
        $sheet->setCellValue('D1', 'Check In');
        $sheet->setCellValue('E1', 'Check Out');

        $attendances = Attendance::whereDate('created_at', $date)->get();
        $attendances->load('employee');
        $row = 2;
        foreach ($attendances as $attendance) {
            $sheet->setCellValue("A{$row}", $attendance->employee_id);
            $sheet->setCellValue("B{$row}", $attendance->employee->name);
            $sheet->setCellValue("C{$row}", $attendance->employee->position);
            $sheet->setCellValue("D{$row}", $attendance->check_out);
            $sheet->setCellValue("E{$row}", $attendance->check_out);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = "attendance_report_{$date}.xlsx";
        $filePath = storage_path("app/public/{$fileName}");

        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
