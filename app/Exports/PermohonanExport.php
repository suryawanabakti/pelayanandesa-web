<?php

namespace App\Exports;

use App\Models\Permohonan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PermohonanExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithMapping, ShouldAutoSize, WithEvents
{
    protected $data;
    protected $title;
    protected $type;

    public function __construct($data = null, $title = 'Laporan Permohonan', $type = 'all')
    {
        $this->data = $data;
        $this->title = $title;
        $this->type = $type;
    }

    public function collection()
    {
        if ($this->data) {
            return $this->data;
        }

        return Permohonan::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function map($permohonan): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $permohonan->user->name ?? 'N/A',
            $permohonan->nik ?? 'N/A',
            $permohonan->jenis_layanan,
            Carbon::parse($permohonan->tanggal)->format('d/m/Y'),
            $permohonan->status,
            Carbon::parse($permohonan->created_at)->format('d/m/Y H:i:s'),
            Carbon::parse($permohonan->updated_at)->format('d/m/Y H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pemohon',
            'NIK',
            'Jenis Layanan',
            'Tanggal Permohonan',
            'Status',
            'Tanggal Dibuat',
            'Tanggal Diupdate',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold
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

                // Add borders to all data
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Add alternating row colors
                for ($i = 2; $i <= $highestRow; $i++) {
                    if ($i % 2 == 0) {
                        $sheet->getStyle('A' . $i . ':' . $highestColumn . $i)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F8FAFC']
                            ]
                        ]);
                    }
                }

                // Auto-fit columns
                foreach (range('A', $highestColumn) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }

    public function title(): string
    {
        return $this->title . ' - ' . Carbon::now()->format('d M Y');
    }
}
