<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CinemaExport;
use Yajra\DataTables\Facades\DataTables;

class CinemaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Model::all() => Mengambil semua data di model
        $cinemas = Cinema::all();
        //compact() => mengirim data ke blade, nama compact sama dengan nama variable
        return view('admin.cinema.index', compact('cinemas'));
    }

    public function datatables()
    {
        $cinemas = Cinema::query();
        return DataTables::of($cinemas) // untuk mengambi data fillable
        ->addIndexColumn() // untuk data angka di table
        ->addColumn('action', function ($cinema)  {
            $btnEdit = ' <a href="' . route('admin.cinemas.edit', $cinema->id) . '" class="btn btn-primary me-2">Edit</a>';
            $btnDelete = '<form action="' . route('admin.cinemas.delete', $cinema->id) . '" method="POST">
            ' .@csrf_field() . method_field('DELETE') . '<button class="btn btn-danger">Hapus</button>
                        </form>';
                        return '<div class="d-flex justify-content-center align-items-center gap-2">'
                        . $btnEdit . $btnDelete .
                        '</div>';
        })
        ->rawColumns(['action']) // mendaftarkan column yang baru dibuat pada addColumn()
        ->make(true); // mengubah query jadi JSON agar bisa terbaca js nya
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cinema.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $request->validate([
            'name' => 'required',
            'location' => 'required|min:10',
        ], [
            'name.required' => 'Nama Bioskop harus diisi',
            'location.required' => 'Lokasi harus diisi',
            'location.min' => 'Lokasi harus diisi minimal 10 karakter',

        ]);
        $createData = Cinema::create([
            'name' => $request->name,
            'location' => $request->location,
        ]);
        if ($createData) {
            return redirect()->route('admin.cinemas.index')->with('success', 'Berhasil membuat data baru!');
        } else {
            return redirect()->back()->with('error', 'Gagal! silahkan coba lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cinema $cinema)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //edit($id) => $id dari {id} dari {id} di route edit
        // Cinema::find() => mencari data di table cinemas berdasarkan id
        $cinema = Cinema::find($id);

        // dd() => cetak data
        // dd($cinema->toArray());
        return view('admin.cinema.edit', compact('cinema'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //(Request $request, $id) :Request $request (ambl data form), $id ambil parameter pa=lacholder {id} dari rpute
        $request->validate([
            'name' => 'required',
            'location' => 'required|min:10'
        ], [
            'name.required' => 'Nama Bioskop harus diisi',
            'location.required' => 'Lokasi Bioskop harus diis',
            'location.min' => 'Lokasi Bioskop harus diisi minimal 10 karakter',
        ]);

        // where ('id', $id) -> sebelum diupdate wajib cari data nya. untuk mencarinya salah satunya dengan where
        // format -> where ('field_di_fillable', $sumberData)
        $updateData = Cinema::where('id', $id)->update([
            'name' => $request->name,
            'location' => $request->location,
        ]);
        if ($updateData) {
            return redirect()->route('admin.cinemas.index')->with('success', 'Berhasil mengubah data!');
        } else {
            return redirect()->back()->with('error', 'Gagal! silahkan coba lagi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Cinema::where('id', $id)->delete();
        return redirect()->route('admin.cinemas.index')->with('success', 'Berhasil menghapus data');
    }

    public function export()
    {
        // nama file yang akan di downloas
        // ekstensi antara xlsx/csv
        $fileName = "data-cinema.xlsx";
        // prosese download
        return Excel::download(new CinemaExport,$fileName);
    }

    public function trash()
    {
        // onlyTrashed() -> filter data yang dihapus, delete_at BUKAN NULL
        $cinemaTrash = Cinema::onlyTrashed()->get();
        return view('admin.cinema.trash', compact('cinemaTrash'));
    }

    public function restore($id)
    {
        $cinema = Cinema::onlyTrashed()->find($id);
        // restore() -> mengembalikan data yang sudah dihapus
        $cinema->restore();
        return redirect()->route('admin.cinemas.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $cinema = Cinema::onlyTrashed()->find($id);
        // forceDelete() -> menghapus data secara permanen, data hilang bahkan dari db nya
        $cinema->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus seutuhnya!');
    }

    public function cinemaList() {
        $cinemas = Cinema::all();
        return view('schedule.cinemas', compact('cinemas'));
    }

    public function cinemaSchedules($cinema_id){
        // whereHas('namarelasi', function($q) {..} : argumen 1 (nama relasi) wajib, argumen 2 (func untuk filter pada relasi) opsional)
        // whereHas('namarelasi') -> Movie::whereHas('schedules') mengambil data film hanya yang memiliki relasi (memiliki data) schedules
        // whereHas ('namarelasi', function($q) {...}) -> Schedule::whereHas('movie', function($q) {$q->where('actived', 1)}) mengambil data schedula hanya yang memiliki relasi (memiliki data) movie dan nilai actived pada movienya 1
        $schedules = Schedule::where('cinema_id', $cinema_id)->with('movie')->whereHas // mengambil darti table schedule yang memiliki relasi movie
        ('movie', function($q) {
            $q->where('actived', 1);
        })->get();
        return view('schedule.cinema-schedules', compact('schedules'));
    }
}
