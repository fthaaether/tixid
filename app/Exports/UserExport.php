<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; // th
use Maatwebsite\Excel\Concerns\WithMapping; // tdf

class UserExport implements FromCollection, WithHeadings, WithMapping
{
    private $key = 0;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // mennetukan data yang akan dimuunculkan di excel
        return User::orderBy('created_at', 'DESC')->get();
    }

    // menentukan th
    public function headings(): array{
        return['No', 'Nama', 'Email', 'Role', 'Tanggal Bergabung'];
    }

    // menentukan td
    public function map($user): array
    {
        // menambahkan $key diatas dari 1 dst
        return[
            ++$this->key,
            $user->name,
            $user->email,
            $user->role,
            $user->created_at,
        ];
    }
}
