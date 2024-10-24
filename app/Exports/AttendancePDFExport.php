<?php

namespace App\Exports;

use App\Models\Attendance;
use Barryvdh\Snappy\Facades\SnappyPdf;

class AttendancePDFExport
{
    public function exportDailyPDF($date)
    {
        $attendances = Attendance::whereDate('created_at', $date)->get();
        $attendances->load('employee');
        $pdf = SnappyPdf::loadView('exports.attendance_pdf', compact('attendances', 'date'));

        return $pdf->download("attendance_report_{$date}.pdf");
    }
}
