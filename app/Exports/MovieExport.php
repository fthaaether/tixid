<?php

namespace App\Exports;

use App\Models\Movie;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; // th
use Maatwebsite\Excel\Concerns\WithMapping; // td
// proses manipulasi tanggal dan waktu menggunakan class namanya Carbon
use Carbon\Carbon;

class MovieExport implements FromCollection, WithHeadings, WithMapping
{
    private $key = 0;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // mennetukan data yang akan dimuunculkan di excel
        return Movie::orderBy('created_at', 'DESC')->get();
    }

    // menentukan th
    public function headings(): array{
        return["No", 'Judul', 'Durasi','Genre', 'Sutradara', 'Usia Minimal', 'Poster', 'Sinopsis', 'Status Aktif'];
    }

    // menentukan td
    public function map($movie): array
    {
        // menambahkan $key diatas dari 1 dst
        return[
            ++$this->key,
            $movie->title,
            // format("H") : mengambil jam dari duration
            Carbon::parse($movie->duration)->format('H') . "Jam" . Carbon::parse(
                $movie->duration)->format('i') . "Menit",
                $movie->genre,
                $movie->director,
                $movie->age_rating . "+",
                    // poster berupa url, -> asset()
                    asset("storage") . "/" . $movie->poster,
                $movie->description,
                //jika actived 1 muncul 'aktif', tidak muncul 'non-aktif'
                $movie->actived == 1 ? 'Aktif' : 'Non-Aktif'
        ];
    }
}
