<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Ticket;
use App\Models\Movie;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Exports\ScheduleExport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
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

        if ($updateData) {
            return redirect()->route('staff.schedules.index')->with('success', 'Berhasil mengubah data!');
        } else {
            return redirect()->back()->with('error', 'Gagal! silahkan coba lagi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule, $id)
    {
        Schedule::where('id', $id)->delete();
        return redirect()->route('staff.schedules.index')->with('success', 'Berhasil menghapus data!');
    }
    public function trash()
    {
        // onlyTrashed() -> filter darta yang dihapus, delete_at BUKAN NULL
        $scheduleTrash = Schedule::with(['cinema', 'movie'])->onlyTrashed()->get();
        return view('staff.schedule.trash', compact('scheduleTrash'));
    }

    public function restore($id)
    {
        $schedule = Schedule::onlyTrashed()->find($id);
        // restore() -> mengembaliukan data yagn sudah dihapus (menghaps=us nilai tanggal pada delete_at)
        $schedule->restore();
        return redirect()->route('staff.schedules.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $schedule = Schedule::onlyTrashed()->find($id);
        // forceDelete() -> menghapus dara secara permanen, data hilang bahkan dari db nya
        $schedule->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus seutuhnya!');
    }

    public function export()
    {
        // nama file yang akan di downloas
        // ekstensi antara xlsx/csv
        $fileName = "data-schedule.xlsx";
        // prosese download
        return Excel::download(new ScheduleExport, $fileName);
    }

    public function datatables()
    {

        $schedules = Schedule::query()->with(['cinema', 'movie'])->get();
        return DataTables::of($schedules)
            ->addIndexColumn()
            ->addColumn('cinema_lam', function ($schedule) {
                return $schedule->cinema->name;
            })
            ->addColumn('movie_title', function ($schedule) {
                return $schedule->movie->title;
            })
            ->addColumn('price', function ($schedule) {
                return 'Rp.' . number_format($schedule->price, 0, ',', '.');
            })
            ->addColumn('hours', function ($schedule) {
                if (is_array($schedule->hours)) {
                    $list = '<ul>';
                    foreach ($schedule->hours as $hours) {
                        $list .= '<li>' . $hours . '</li>';

                    }
                    $list .= '</ul>';
                    return $list;
                }
            })
            ->addColumn('action', function ($schedule) {
                $btnEdit = ' <a href="' . route('staff.schedules.edit', $schedule->id) . '" class="btn btn-primary me-2">Edit</a>';
                $btnDelete = '<form action="' . route('staff.schedules.delete', $schedule->id) . '" method="POST">
            ' . @csrf_field() . method_field('DELETE') . '<button class="btn btn-danger">Hapus</button>
                        </form>';
                return '<div class="d-flex justify-content-center align-items-center gap-2">'
                    . $btnEdit . $btnDelete .
                    '</div>';
            })
            ->rawColumns(['cinema_lam', 'movie_title', 'price', 'hours', 'action'])
            ->make(true);
    }

    public function showSeats($scheduleId, $hourId)
    {
        $schedule = Schedule::where('id', $scheduleId)->with('cinema')->first();
        $hour = $schedule['hours'][$hourId];
        // ambil data kuri dengan kriteria
        // 1. uda dibayar (ada paid_date di ticket paymernt)
        // 2. tiket dibeli di tgl dan jam yanng seduai diklik

        $seats = Ticket::where('schedule_id', $scheduleId)->whereHas('ticketPayment', function ($q) {
            // ambil tanggal sekarang
            $date = now()->format('Y-m-d');
            // whereDate : mencari berrdasarkan tanggal
            $q->whereDate('paid_date', $date);
        })->whereTime('hour', $hour)->pluck('rows_of_seats');
        // pluck() : mengambil data hanya satu column
        // mengganti array dua dimensi menjadis atu dimesni
        $seatsFormat = array_merge(...$seats);
        // ... : spread operator, mengeularkan isi array. array_merge() menggunakan isi array. jf mengeluarkan dr dimensi kedua, digabungkan ke dimensi pertema
        // dd($seatsFormat);
        return view('schedule.show-seats', compact('schedule', 'hour', 'seatsFormat'));
    }
}

