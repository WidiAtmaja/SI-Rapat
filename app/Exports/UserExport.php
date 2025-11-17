<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return User::select('nip', 'name', 'perangkat_daerah_id', 'unit_kerja', 'jabatan', 'no_hp', 'jenis_kelamin', 'email', 'peran')->get();
    }

    public function headings(): array
    {
        return [
            'NIP',
            'Nama',
            'Perangkat Daerah',
            'Unit Kerja',
            'Jabatan',
            'No. HP',
            'Jenis Kelamin',
            'Email',
            'Peran',
        ];
    }

    public function map($user): array
    {
        return [
            $user->nip,
            $user->name,
            $user->perangkatDaerah?->nama_perangkat_daerah,
            $user->unit_kerja,
            $user->jabatan,
            $user->no_hp,
            $user->jenis_kelamin,
            $user->email,
            ucfirst($user->peran),
        ];
    }
}
