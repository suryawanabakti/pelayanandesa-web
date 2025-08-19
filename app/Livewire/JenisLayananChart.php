<?php

namespace App\Livewire;

use App\Models\Permohonan;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class JenisLayananChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Jenis Layanan 30 Hari Terakhir';
    protected int | string | array $columnSpan = 'full';
    protected function getData(): array
    {
        $data = Permohonan::whereDate('tanggal', '>=', now('Asia/Makassar')->subDays(30)) // Filter 30 hari terakhir
            ->selectRaw('jenis_layanan, COUNT(*) as total')
            ->groupBy('jenis_layanan')
            ->pluck('total', 'jenis_layanan');

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Permohonan',
                    'data' => $data->values(),
                    'backgroundColor' => [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6'
                    ],
                ],
            ],
            'labels' => $data->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // tukar ke 'pie' atau 'doughnut' kalau nak
    }

    protected static ?int $sort = 2;
}
