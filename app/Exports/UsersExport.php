<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Data' => new DataSheet(),
            'Instructions' => new InstructionSheet(),
        ];
    }
}

class DataSheet implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    public function array(): array
    {
        $users = User::where('role', 'masyarakat')->get();

        $data = [];

        foreach ($users as $user) {
            $data[] = [
                $user->name,   // atau field lain sesuai database kamu
                $user->nik,  // atau field lain
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return ['name', 'nik'];
    }

    public function title(): string
    {
        return 'Data';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

class InstructionSheet implements FromArray, WithTitle, ShouldAutoSize, WithStyles
{
    public function array(): array
    {
        return [
            ['Petunjuk Pengisian Template Import User'],
            [''],
            ['Kolom name: Wajib diisi dengan nama lengkap user'],
            ['Kolom nik: Wajib diisi dengan nik yang valid dan unik'],

        ];
    }

    public function title(): string
    {
        return 'Instructions';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            3 => ['font' => ['bold' => true]],
            4 => ['font' => ['bold' => true]],
            5 => ['font' => ['bold' => true]],
            6 => ['font' => ['bold' => true]],
            7 => ['font' => ['bold' => true]],
            8 => ['font' => ['bold' => true]],
        ];
    }
}
