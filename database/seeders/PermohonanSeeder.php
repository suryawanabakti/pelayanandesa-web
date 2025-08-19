<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permohonan;
use App\Models\User;
use Illuminate\Support\Str;

class PermohonanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisLayanan = [
            'Surat Keterangan Tidak Mampu',
            'Surat Pindah Penduduk',
            'Surat Izin Usaha',
            'Surat Keterangan Usaha',
            'Surat Stanting'
        ];

        $pekerjaanList = ['Pelajar', 'Petani', 'Karyawan', 'Wiraswasta', 'Guru'];

        // Pastikan ada user dulu
        if (User::count() === 0) {
            User::factory()->count(5)->create();
        }

        $users = User::all();

        foreach (range(1, 50) as $i) {
            $nama = fake()->name();
            $tanggalLahir = fake()->dateTimeBetween('-40 years', '-10 years');
            $umur = now()->year - $tanggalLahir->format('Y');

            // Ambil tanggal acak antara 30 hari terakhir dan hari ini
            $tanggal = fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d');

            Permohonan::create([
                'user_id' => $users->random()->id,
                'jenis_layanan' => fake()->randomElement($jenisLayanan),
                'tanggal' => $tanggal,

                'nama' => $nama,
                'tempat_lahir' => fake()->city(),
                'tanggal_lahir' => $tanggalLahir->format('Y-m-d'),
                'nama_orang_tua' => fake()->name(),
                'nik' => fake()->numerify('################'),
                'umur' => $umur,
                'alamat' => fake()->address(),
                'pekerjaan' => fake()->randomElement($pekerjaanList),

                'keterangan' => fake()->sentence(),
                'status' => fake()->randomElement(['DIAJUKAN', 'DIPROSES', 'SELESAI', 'DITOLAK']),
                'file' => null,
            ]);
        }
    }
}
