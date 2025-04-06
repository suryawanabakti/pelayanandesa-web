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
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();
    if ($user->is_approve == 0) {
        throw ValidationException::withMessages([
            'email' => ['Akun kamu belum di approve oleh admin. harap tunggu'],
        ]);
    }
    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return [
        "user" => $user,
        "token" => $user->createToken("api")->plainTextToken
    ];
});

Route::post('/v1/register', function (Request $request) {

    $request->validate([
        'name' => 'required|string',
        'nik' => 'required|string',
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::create([
        'name' => $request->name,
        'nik' => $request->nik,
        'email' => $request->email,
        'username' => $request->email,
        'role' => 'masyarakat',
        'password' => Hash::make($request->password),
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
