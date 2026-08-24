<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TeacherPerformanceReportExport implements FromView
{
    public function __construct(private array $data)
    {
    }

    public function view(): View
    {
        return view('reports.teacher-performance-excel-table', $this->data);
    }
}
