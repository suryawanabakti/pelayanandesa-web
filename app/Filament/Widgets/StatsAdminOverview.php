<?php

namespace App\Filament\Widgets;

use App\Models\Aduan;
use App\Models\Dokumen;
use App\Models\Informasi;
use App\Models\Permohonan;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsAdminOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Permohonan', Permohonan::count())
                ->description(Permohonan::whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->count() . ' bertambah di bulan ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),
            Stat::make('Jumlah Dokumen', Dokumen::count())
                ->description(Dokumen::whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->count() . ' bertambah di bulan ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),
            Stat::make('Jumlah Informasi', Informasi::count())
                ->description(Informasi::whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->count() . ' bertambah di bulan ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),
        ];
    }
}
