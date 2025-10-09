<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CinemaExport;

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

        $schedules = Schedule::where('cinema_id', $id)->count();
        if($schedules) {
            return redirect()->route('admin.cinemas.index')->with('error', 'Tidak dapat menghapus data bioskop! Data tertaut dengan jadwal tayang');
        }
        


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
}
