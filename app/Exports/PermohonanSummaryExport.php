<?php

namespace App\Exports;

use App\Models\Permohonan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PermohonanSummaryExport implements FromArray, WithHeadings, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    protected $data;

    public function __construct()
    {
        $this->data = $this->prepareSummaryData();
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Kategori',
            'Item',
            'Jumlah',
            'Persentase'
        ];
    }

    private function prepareSummaryData()
    {
        $data = Permohonan::with('user')->get();
        $total = $data->count();
        $summaryData = [];

        // Header info
        $summaryData[] = ['INFORMASI UMUM', '', '', ''];
        $summaryData[] = ['Total Permohonan', $total, '', ''];
        $summaryData[] = ['Tanggal Export', Carbon::now()->format('d/m/Y H:i:s'), '', ''];
        $summaryData[] = ['', '', '', ''];

        // Summary by Status
        $summaryData[] = ['STATUS PERMOHONAN', '', '', ''];
        $statusCounts = $data->groupBy('status')->map->count();

        foreach ($statusCounts as $status => $count) {
            $percentage = $total > 0 ? round(($count / $total) * 100, 2) : 0;
            $summaryData[] = ['Status', $status, $count, $percentage . '%'];
        }
        $summaryData[] = ['', '', '', ''];

        // Summary by Service Type
        $summaryData[] = ['JENIS LAYANAN', '', '', ''];
        $serviceCounts = $data->groupBy('jenis_layanan')->map->count();

        foreach ($serviceCounts as $service => $count) {
            $percentage = $total > 0 ? round(($count / $total) * 100, 2) : 0;
            $summaryData[] = ['Jenis Layanan', $service, $count, $percentage . '%'];
        }
        $summaryData[] = ['', '', '', ''];

        // Monthly Summary (Last 12 months)
        $summaryData[] = ['RINGKASAN BULANAN', '', '', ''];
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $monthlyData = $data->where('created_at', '>=', $startDate)
            ->groupBy(function ($item) {
                return Carbon::parse($item->created_at)->format('Y-m');
            })
            ->map->count();

        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $monthLabel = Carbon::now()->subMonths($i)->format('M Y');
            $count = $monthlyData->get($month, 0);
            $percentage = $total > 0 ? round(($count / $total) * 100, 2) : 0;
            $summaryData[] = ['Bulan', $monthLabel, $count, $percentage . '%'];
        }

        return $summaryData;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0']
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Style category headers
                $categoryRows = [];
                for ($i = 1; $i <= $highestRow; $i++) {
                    $cellValue = $sheet->getCell('A' . $i)->getValue();
                    if (in_array($cellValue, ['INFORMASI UMUM', 'STATUS PERMOHONAN', 'JENIS LAYANAN', 'RINGKASAN BULANAN'])) {
                        $categoryRows[] = $i;
                    }
                }

                foreach ($categoryRows as $row) {
                    $sheet->getStyle('A' . $row . ':' . $highestColumn . $row)->applyFromArray([
                        'font' => ['bold' => true, 'size' => 11],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'CBD5E0']
                        ]
                    ]);
                }

                // Add borders
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Auto-fit columns
                foreach (range('A', $highestColumn) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }

    public function title(): string
    {
        return 'Ringkasan Laporan - ' . Carbon::now()->format('d M Y');
    }
}
