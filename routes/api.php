<?php

use App\Http\Controllers\API\DokumenController;
use App\Http\Controllers\API\InformationController;
use App\Http\Controllers\API\PermohonanController;
use App\Models\Dokumen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::get('/v1/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');





Route::post('/v1/login', function (Request $request) {

    $request->validate([
        'email' => 'required',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    $user->update(['has_login' => 1]);

    return [
        "user" => $user,
        "token" => $user->createToken("api")->plainTextToken
    ];
});

Route::post('/v1/register', function (Request $request) {

    $request->validate([
        'name' => 'required|string',
        'nik' => 'required|string',
        'password' => 'required',
    ]);

    $user = User::create([
        'name' => $request->name,
        'nik' => $request->nik,
        'email' => $request->nik,
        'username' => $request->nik,
        'role' => 'masyarakat',
        'password' => Hash::make($request->password),
        'is_approve' => true,
    ]);

    return [
        "user" => $user,
        "token" => $user->createToken("api")->plainTextToken
    ];
});

Route::middleware(["auth:sanctum"])->group(function () {
    Route::post('/v1/logout', function (Request $request) {
        // Revoke all tokens of the authenticated user
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out from all devices'], 200);
    });

    Route::get("/v1/permohonan", [PermohonanController::class, 'index']);
    Route::post("/v1/permohonan", [PermohonanController::class, 'store']);
    Route::post("/v1/permohonan/{permohonan}", [PermohonanController::class, 'update']);
    Route::delete("/v1/permohonan/{permohonan}", [PermohonanController::class, 'destroy']);


    Route::get("/v1/informasi", [InformationController::class, 'index']);
    Route::get("/v1/informasi/{informasi}", [InformationController::class, 'show']);

    Route::get("/v1/informasi", [InformationController::class, 'index']);

    Route::get("/v1/dokumen", [DokumenController::class, 'index']);
    Route::get('/v1/download-pdf/{path}', [DokumenController::class, 'download']);
});
Route::get('/permohonan/{permohonan}/cetak', [PermohonanController::class, 'cetakSuratKematian'])
    ->name('permohonan.cetak');
