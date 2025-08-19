<?php

namespace App\Filament\Pages;

use App\Exports\PermohonanExport;
use App\Exports\PermohonanSummaryExport;
use App\Livewire\JenisLayananChart as LivewireJenisLayananChart;
use App\Livewire\LaporanChart;
use App\Livewire\LaporanOverview;
use Filament\Pages\Page;
use App\Models\Permohonan;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Actions\BulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Form;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Filament\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;

class Laporan extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Permohonan';
    protected static ?string $title = 'Permohonan';
    protected static string $view = 'filament.pages.laporan';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 4;

    protected function getHeaderWidgets(): array
    {
        return [
            LaporanOverview::class,
            LaporanChart::class,
            LivewireJenisLayananChart::class,
        ];
    }

    /**
     * Header Actions - Tombol di sebelah judul halaman
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportExcel')
                ->label('Download Excel')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    return $this->exportToExcel();
                })
                ->tooltip('Export semua data ke Excel (.xlsx)'),

            // Action::make('exportFiltered')
            //     ->label('Export Filtered')
            //     ->icon('heroicon-o-funnel')
            //     ->color('info')
            //     ->action(function () {
            //         return $this->exportFilteredData();
            //     })
            //     ->tooltip('Export data yang sudah difilter ke Excel'),

            Action::make('exportSummary')
                ->label('Download Ringkasan')
                ->icon('heroicon-o-chart-bar')
                ->color('warning')
                ->action(function () {
                    return $this->exportSummaryReport();
                })
                ->tooltip('Export ringkasan laporan ke Excel'),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Permohonan::query())
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Pemohon')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jenis_layanan')
                    ->label('Jenis Layanan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'DIAJUKAN' => 'warning',
                        'DIPROSES' => 'info',
                        'SELESAI' => 'success',
                        'DITOLAK' => 'danger',
                    }),
                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'DIAJUKAN' => 'Diajukan',
                        'DIPROSES' => 'Diproses',
                        'SELESAI' => 'Selesai',
                        'DITOLAK' => 'Ditolak',
                    ]),
                SelectFilter::make('jenis_layanan')
                    ->label('Jenis Layanan')
                    ->options([
                        'Surat Pindah Penduduk' => 'Surat Pindah Penduduk',
                        'Surat Izin Usaha' => 'Surat Izin Usaha',
                        'Surat Keterangan Tidak Mampu' => 'Surat Keterangan Tidak Mampu',
                        'Surat Penyaluran BLT' => 'Surat Penyaluran BLT',
                        'Surat Stanting' => 'Surat Stanting',
                    ]),
                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                // Anda bisa menambahkan aksi di sini jika diperlukan
            ])
            ->bulkActions([
                BulkAction::make('exportSelected')
                    ->label('Export Selected')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('primary')
                    ->action(function (Collection $records) {
                        return $this->exportSelectedRecords($records);
                    }),
            ]);
    }

    /**
     * Export all data to Excel
     */
    public function exportToExcel()
    {
        $filename = 'laporan_permohonan_semua_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(
            new PermohonanExport(null, 'Laporan Semua Permohonan', 'all'),
            $filename
        );
    }

    /**
     * Export filtered data to Excel
     */
    public function exportFilteredData()
    {
        $query = $this->getFilteredTableQuery();
        $data = $query->with('user')->get();

        $filename = 'laporan_permohonan_filtered_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(
            new PermohonanExport($data, 'Laporan Permohonan Filtered', 'filtered'),
            $filename
        );
    }

    /**
     * Export summary report to Excel
     */
    public function exportSummaryReport()
    {
        $filename = 'ringkasan_permohonan_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(
            new PermohonanSummaryExport(),
            $filename
        );
    }

    /**
     * Export selected records to Excel
     */
    public function exportSelectedRecords(Collection $records)
    {
        $filename = 'laporan_permohonan_selected_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(
            new PermohonanExport($records, 'Laporan Permohonan Selected', 'selected'),
            $filename
        );
    }
}
