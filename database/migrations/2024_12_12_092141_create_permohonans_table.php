<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permohonan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('jenis_layanan'); // surat keterangan tidak mampu, surat pindah penduduk, dll
            $table->date('tanggal');

            // Personal information fields
            $table->string('nama');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('nama_orang_tua');
            $table->string('nik', 16);
            $table->integer('umur');
            $table->text('alamat');
            $table->string('pekerjaan');

            $table->text('keterangan');
            $table->enum('status', ['DIAJUKAN', 'DIPROSES', 'SELESAI', 'DITOLAK'])->default('DIAJUKAN');
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan');
    }
};
