<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Permohonan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PermohonanController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Permohonan::where('user_id', request()->user()->id)->get()
        ], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal' => ['required', 'date'],
            'jenis_layanan' => ['required', 'string'],
            'nama' => ['required', 'string'],
            'tempat_lahir' => ['required', 'string'],
            'tanggal_lahir' => ['required', 'date'],
            'nama_orang_tua' => ['required', 'string'],
            'nik' => ['required', 'string', 'size:16'],
            'umur' => ['required', 'integer'],
            'alamat' => ['required', 'string'],
            'pekerjaan' => ['required', 'string'],
            'keterangan' => ['required', 'string'],
        ]);

        $validatedData['user_id'] = $request->user()->id;
        $validatedData['status'] = 'DIAJUKAN';

        if ($request->hasFile('file')) {
            $request->validate([
                'file' => ['file', 'mimes:pdf', 'max:5120'], // 5MB max size
            ]);

            $validatedData['file'] = $request->file('file')->store($request->user()->username . '/permohonan');
        }

        Permohonan::create($validatedData);

        return response()->json([
            'message' => 'Berhasil tambah permohonan'
        ], 200);
    }

    public function show(Permohonan $permohonan)
    {
        // Check if the permohonan belongs to the authenticated user
        if ($permohonan->user_id !== request()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['data' => $permohonan], 200);
    }

    public function destroy(Permohonan $permohonan)
    {
        // Check if the permohonan belongs to the authenticated user
        if ($permohonan->user_id !== request()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $permohonan->delete();
        return response()->json(['message' => 'Berhasil hapus permohonan']);
    }

    public function cetakSuratKematian(Permohonan $permohonan)
    {


        // Get the village head information from settings or database
        $kepala_desa = "BUTTU LEMBANG, S.Kom"; // Replace with dynamic data
        $nama_desa = "Sapan";
        $alamat_desa = "Desa Sapan";

        // Format the date for the document
        $tanggal_surat = now()->format('d F Y');

        // Generate a document number
        $nomor_surat = sprintf(
            "%02d /SK/DS-SPN/%s/%s",
            $permohonan->id,
            $this->getRomanMonth(now()->month),
            now()->year
        );

        $data = [
            "jenis_layanan" => $permohonan->jenis_layanan,
            'permohonan' => $permohonan,
            'nomor_surat' => $nomor_surat,
            'kepala_desa' => $kepala_desa,
            'nama_desa' => $nama_desa,
            'alamat_desa' => $alamat_desa,
            'tanggal_surat' => $tanggal_surat,
            'nama' => $permohonan->nama,
            'nik' => $permohonan->nik,
            'tempat_lahir' => $permohonan->tempat_lahir,
            'tanggal_lahir' => date('d-m-Y', strtotime($permohonan->tanggal_lahir)),
            'jenis_kelamin' => $permohonan->jenis_kelamin ?? 'Perempuan',
            'agama' => $permohonan->agama ?? 'Kristen',
            'alamat' => $permohonan->alamat,
            'tanggal_meninggal' => $permohonan->tanggal_meninggal ?? date('d F Y', strtotime($permohonan->tanggal))
        ];

        // For direct view in browser
        // return view('surat-kematian', $data);

        // For PDF generation
        $pdf = Pdf::loadView('surat', $data);
        return $pdf->stream('surat-' . $permohonan->nama . '.pdf');
    }

    private function getRomanMonth($month)
    {
        $romans = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];

        return $romans[$month] ?? '';
    }
}
