<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Permohonan;
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
            'jenis_layanan' => ['required'],
            'keterangan' => ['required'],
        ]);
        $validatedData['user_id'] = $request->user()->id;
        $validatedData['status'] = 'DIAJUKAN';

        if ($request->has('file')) {
            $validatedData = $request->file('file')->store($request->user()->username . '/permohonan');
        }

        Permohonan::create($validatedData);
        return response()->json([
            'message' => 'Berhasil tambah permohonan'
        ], 200);
    }

    public function destroy(Permohonan $permohonan)
    {
        $permohonan->delete();
        return response()->json(['message' => 'Berhasil hapus permohonan']);
    }
}
