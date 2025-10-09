<?php

namespace App\Exports;

use App\Models\Schedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; // th
use Maatwebsite\Excel\Concerns\WithMapping; // td

class ScheduleExport implements FromCollection, WithHeadings, WithMapping
{
    private $key = 0;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // mennetukan data yang akan dimuunculkan di excel
        return Schedule::orderBy('created_at', 'DESC')->get();
    }

    // menentukan th
    public function headings(): array{
        return['No', 'Nama', 'Judul FIlm', 'Harga', 'Jam Tayang'];
    }

    // menentukan td
    public function map($schedule): array
    {
        // menambahkan $key diatas dari 1 dst
        return[
            ++$this->key,
            $schedule->cinema->name,
            $schedule->movie->title,
            $schedule->price
            = 'Rp. ' . number_format($schedule->price, 0, ',', '.'),
            implode(" ", (array) $schedule->hours),
        ];
    }
}

