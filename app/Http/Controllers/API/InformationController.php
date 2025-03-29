<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\InformasiResource;
use App\Models\Informasi;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => InformasiResource::collection(Informasi::latest()->get())
        ], 200);
    }

    public function show(Informasi $informasi)
    {
        return response()->json([
            'data' => $informasi
        ], 200);
    }
}
