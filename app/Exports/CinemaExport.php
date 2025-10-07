<?php

namespace App\Exports;

use App\Models\Cinema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; // th
use Maatwebsite\Excel\Concerns\WithMapping; // tdf
class CinemaExport implements FromCollection, WithHeadings, WithMapping
{
    private $key = 0;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // mennetukan data yang akan dimuunculkan di excel
        return Cinema::orderBy('created_at', 'DESC')->get();
    }

    // menentukan th
    public function headings(): array{
        return['No', 'Nama Bioskop', 'Lokasi'];
    }

    // menentukan td
    public function map($cinema): array
    {
        // menambahkan $key diatas dari 1 dst
        return[
            ++$this->key,
            $cinema->name,
            $cinema->location,
        ];
    }
}

