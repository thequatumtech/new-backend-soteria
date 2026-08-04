<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClientReportExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $policies;

    public function __construct($policies)
    {
        $this->policies = $policies;
    }

    public function view(): View
    {
        return view('admin.reports.exports.client_report_export', [
            'policies' => $this->policies
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}