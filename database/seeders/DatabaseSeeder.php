<?php

namespace Database\Seeders;

use App\Models\Aduan;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'password' => bcrypt('qwerty123'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Nita',
            'email' => 'nita21@gmail.com',
            'username' => 'nita',
            'password' => bcrypt('qwerty123'),
            'role' => 'masyarakat'
        ]);

        Aduan::create([
            'user_id' => 2,
            'jenis_layanan' => 'Layanan 1',
            'tanggal' => '2024-12-01',
            'keterangan' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Nemo ex necessitatibus mollitia, adipisci, perferendis dolore assumenda sequi exercitationem neque eius harum. Laboriosam tenetur, repellat ducimus dolores eius ut explicabo odio.',
            'status' => 'DIAJUKAN',
        ]);
    }
}
