<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (!empty($row['name'])) {
            $user = User::where('nik', $row['nik'])->first();
            if (empty($user)) {
                return new User([
                    'name' => $row['name'],
                    'nik' => $row['nik'],
                    'email' => $row['nik'],
                    'username' => $row['nik'],
                    'role' => 'masyarakat', // jangan bcrypt role, itu bukan password
                    'password' => bcrypt($row['nik']),
                ]);
            }
        }
    }

    public function headingRow(): int
    {
        return 1; // Kalau heading ada di row ke-2
    }
}
