<?php

namespace App\Livewire;

use App\Models\Permohonan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LaporanOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Permohonan', Permohonan::count())
                ->description('Semua permohonan')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),

            Stat::make('Permohonan Diproses', Permohonan::where('status', 'DIPROSES')->count())
                ->description('Sedang dalam proses')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),

            Stat::make('Permohonan Selesai', Permohonan::where('status', 'SELESAI')->count())
                ->description('Telah selesai diproses')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Permohonan Ditolak', Permohonan::where('status', 'DITOLAK')->count())
                ->description('Tidak disetujui')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
