<?php

namespace App\Livewire;

use App\Models\Permohonan;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class LaporanChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Permohonan';

    // Make the widget full width
    protected int | string | array $columnSpan = 'full';
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'top', // ⬅️ Legend muncul di atas chart
                    'labels' => [
                        'boxWidth' => 12, // Ukuran kotak warna
                        'padding' => 15,
                        'font' => [
                            'size' => 12,
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getData(): array
    {
        // Get the last 12 months
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonths($i)->format('Y-m'));
        }

        // Query for each service type, grouped by month
        $suratPindahPenduduk = $this->getMonthlyData('Surat Pindah Penduduk');
        $suratIzinUsaha = $this->getMonthlyData('Surat Izin Usaha');
        $suratKeteranganTidakMampu = $this->getMonthlyData('Surat Keterangan Tidak Mampu');
        $suratPenyaluranBLT = $this->getMonthlyData('Surat Penyaluran BLT');
        $suratStanting = $this->getMonthlyData('Surat Stanting');

        // Format months for labels
        $labels = $months->map(function ($month) {
            return Carbon::parse($month . '-01')->format('M Y');
        })->toArray();

        // Create datasets with different colors
        return [
            'datasets' => [
                [
                    'label' => 'Surat Pindah Penduduk',
                    'data' => $this->prepareMonthlyChartData($suratPindahPenduduk, $months),
                    'fill' => true,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)', // Blue
                    'borderColor' => 'rgb(59, 130, 246)',
                ],
                [
                    'label' => 'Surat Izin Usaha',
                    'data' => $this->prepareMonthlyChartData($suratIzinUsaha, $months),
                    'fill' => true,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)', // Green
                    'borderColor' => 'rgb(16, 185, 129)',
                ],
                [
                    'label' => 'Surat Keterangan Tidak Mampu',
                    'data' => $this->prepareMonthlyChartData($suratKeteranganTidakMampu, $months),
                    'fill' => true,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.5)', // Yellow
                    'borderColor' => 'rgb(245, 158, 11)',
                ],
                [
                    'label' => 'Surat Penyaluran BLT',
                    'data' => $this->prepareMonthlyChartData($suratPenyaluranBLT, $months),
                    'fill' => true,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.5)', // Red
                    'borderColor' => 'rgb(239, 68, 68)',
                ],
                [
                    'label' => 'Surat Stanting',
                    'data' => $this->prepareMonthlyChartData($suratStanting, $months),
                    'fill' => true,
                    'backgroundColor' => 'rgba(139, 92, 246, 0.5)', // Purple
                    'borderColor' => 'rgb(139, 92, 246)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    /**
     * Get monthly data for a specific service type
     */

    protected function getMonthlyData($jenisLayanan)
    {
        // Get data for the last 12 months
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();

        return Permohonan::select(
            DB::raw("strftime('%Y-%m', tanggal) as month"),
            DB::raw('COUNT(*) as total')
        )
            ->where('jenis_layanan', $jenisLayanan)
            ->where('tanggal', '>=', $startDate)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    /**
     * Prepare monthly chart data by ensuring all months have values
     */
    protected function prepareMonthlyChartData($queryResult, $allMonths)
    {
        // Create a map of month => total from query results
        $dataMap = $queryResult->pluck('total', 'month')->toArray();

        // Map all months to their values, defaulting to 0 if not present
        return $allMonths->map(function ($month) use ($dataMap) {
            return $dataMap[$month] ?? 0;
        })->toArray();
    }

    protected function getType(): string
    {
        return 'line';
    }
}
