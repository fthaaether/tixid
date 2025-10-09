<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cinemas = Cinema::all();
        $movies = Movie::all();

        // return view('staff.schedule.index', compact('cinemas', 'movies'));

        // with () : memanggil detail relasi, tidak hanya angka id nya
        // isi with() dari function relasi di model
        $schedules = Schedule::with(['cinema', 'movie'])->get();

        return view('staff.schedule.index', compact('cinemas', 'movies', 'schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cinema_id' => 'required',
            'movie_id' => 'required',
            'price' => 'required|numeric',
            // karena hours array, yng dicalidasi isi itemnya menggunakan (.*)
            //date format : bentuk item arraynya berupa formar waktu H:i
            'hours.*' => 'required|date_format:H:i',
        ], [
            'cinema_id.required' => 'Bioskop harus dipilih',
            'movie_id.required' => 'Film harus dipilih',
            'price.required' => 'Harga harus diisi',
            'price.numeric' => 'Harga harus diisi dengan angka',
            'hours.*.required' => 'Jam ttayang diisin i minimal 1 data',
            'hours.*.date_format' => 'Jam tayang diisi dengan jam:menit'
        ]);

        // cek aapakah data bioskop dan film yang dipilih sudah ada, al; ada ambil jamnua
        $hours = Schedule::where('cinema_id', $request->cinema_id)
            ->where('movie_id', $request->movie_id)
            ->value('hours');

        // value('hours) : dari schedule cmn ambil bagian hpurus
        // jika blm ada data bioskop dan film, hours akan NULL ubah menjadi []
        $hoursBefore = $hours ?? [];

        // gabungkan hours sebelyuumya dengan hours yang baru akan dirtambahkan
        $mergeHours = array_merge($hoursBefore, $request->hours);
        //jika ada jam duplikat, ambil salha satu
        $newHours = array_unique($mergeHours);
        //updateOrCreate : mengecek berdasarkan array 1, jika ada maka update array 2, jikatidak ada tambahkan data dari awrray 1 dan 2
        $createData = Schedule::updateOrCreate(
            [
                'cinema_id' => $request->cinema_id,
                'movie_id' => $request->movie_id,
            ],
            [
                'price' => $request->price,
                // jam penggabungan sblm dan baru di proses diatas
                'hours' => $newHours
            ]
        );
        if ($createData) {
            return redirect()->route('staff.schedules.index')->with(
                'success',
                'Berhasil menambahkan data!'
            );
        } else {
            return redirect()->back() - with('error', 'Gagal! silahkan coba lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule, $id)
    {
            $schedule = Schedule::where('id', $id)->with(['cinema', 'movie'])->first();
            return view('staff.schedule.edit', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule, $id)
    {
        $request->validate([
            'price' => 'required|numeric',
            'hours.*' => 'required|date_format:H:i',
        ], [
            'price.required' => 'Harga harus diisi',
            'price.numeric' => 'Harga harus diisi dengan angka',
            'hours.required' => 'Jam tayang harus diisi',
            'hours.*.date_format' => 'Jam tayang harus dengan format jam:menit',
        ]);

        $updateData = Schedule::where('id', $id)->update([
            'price' => $request->price,
            'hours' => $request->hours,
        ]);

        if($updateData) {
            return redirect()->route('staff.schedules.index')->with('success', 'Berhasil mengubah data!');
        }
        else {
            return redirect()->back()->with('error', 'Gagal! silahkan coba lagi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        //
    }
}
