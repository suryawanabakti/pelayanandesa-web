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
            'name' => 'Kepala',
            'email' => 'kepala@gmail.com',
            'username' => 'kepala',
            'password' => bcrypt('qwerty123'),
            'role' => 'kepala'
        ]);


        // User::create([
        //     'name' => 'Admin',
        //     'email' => 'admin@gmail.com',
        //     'username' => 'admin',
        //     'password' => bcrypt('qwerty123'),
        //     'role' => 'admin'
        // ]);

        // User::create([
        //     'name' => 'Nita',
        //     'email' => 'nita21@gmail.com',
        //     'username' => 'nita',
        //     'password' => bcrypt('qwerty123'),
        //     'role' => 'masyarakat'
        // ]);


        // $this->call(PermohonanSeeder::class);
    }
}
