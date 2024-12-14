<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DokumenResource;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use League\CommonMark\Node\Block\Document;

class DokumenController extends Controller
{
    public function donwload($filename)
    {
        $path = storage_path("app/public/{$filename}");
        if (!file_exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }
        return response()->download($path, $filename);
    }
    public function index()
    {
        return DokumenResource::collection(Dokumen::all());
    }
}
