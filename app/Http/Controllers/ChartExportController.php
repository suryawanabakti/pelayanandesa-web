<?php

namespace App\Http\Controllers;

use App\Models\Permohonan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ChartExportController extends Controller
{
    /**
     * Export chart data as Excel
     */
    public function exportExcel()
    {
        // Get the same data as the chart
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonths($i)->format('Y-m'));
        }

        $serviceTypes = [
            'Surat Pindah Penduduk',
            'Surat Izin Usaha',
            'Surat Keterangan Tidak Mampu',
            'Surat Penyaluran BLT',
            'Surat Stanting'
        ];

        $data = [];
        foreach ($months as $month) {
            $monthLabel = Carbon::parse($month . '-01')->format('M Y');
            $row = ['Bulan' => $monthLabel];

            foreach ($serviceTypes as $serviceType) {
                $count = Permohonan::where('jenis_layanan', $serviceType)
                    ->whereRaw("strftime('%Y-%m', tanggal) = ?", [$month])
                    ->count();
                $row[$serviceType] = $count;
            }
            $data[] = $row;
        }

        // Create CSV content
        $csvContent = implode(',', array_keys($data[0])) . "\n";
        foreach ($data as $row) {
            $csvContent .= implode(',', $row) . "\n";
        }

        $filename = 'laporan_permohonan_' . Carbon::now()->format('Y-m-d') . '.csv';

        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export detailed report
     */
    public function exportDetailedReport()
    {
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();

        $data = Permohonan::select(
            'jenis_layanan',
            DB::raw("strftime('%Y-%m', tanggal) as month"),
            DB::raw("strftime('%Y-%m-%d', tanggal) as tanggal"),
            'nama_pemohon',
            'status'
        )
            ->where('tanggal', '>=', $startDate)
            ->orderBy('tanggal', 'desc')
            ->get();

        // Create CSV content
        $csvContent = "Jenis Layanan,Bulan,Tanggal,Nama Pemohon,Status\n";

        foreach ($data as $row) {
            $monthLabel = Carbon::parse($row->month . '-01')->format('M Y');
            $csvContent .= '"' . $row->jenis_layanan . '","' . $monthLabel . '","' . $row->tanggal . '","' . $row->nama_pemohon . '","' . $row->status . '"' . "\n";
        }

        $filename = 'laporan_detail_permohonan_' . Carbon::now()->format('Y-m-d') . '.csv';

        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
