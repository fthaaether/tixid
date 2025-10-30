<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MovieExport;
use Yajra\DataTables\Facades\DataTables;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // //Model::all() => mengambil semua data dari model
        $movies = Movie::all(); // all, tidak mengambil data dengan filter
        return view('admin.movie.index', compact('movies')); //movie nya gapake s karena nama foldermnya juga ga apake s
    }

    public function datatables()
    {
        $movies = Movie::query();
        return DataTables::of($movies)->addIndexColumn()->addColumn('poster_img', function ($movie) {
            $url = asset('storage_img' . $movie->poster);
            return '<img src="' . $url . '" width="70">';
        })
        ->addColumn('actived_badge', function($movie) {
            if ($movie->actived) {
                return '<span class="badge badge-success">Aktif</span>';
            } else {
                return '<span class="badge badge-danger">Non Aktif</span>';
            }
        })
        ->addColumn('action', function ($movie) {
            $btnDetail = '<button class="btn btn-secondary me-2" onclick="showModal(' . $movie . ')">Detail</button>';
            $btnEdit = ' <a href="' . route('admin.movies.edit', $movie->id) . '" class="btn btn-primary me-2">Edit</a>';
            $btnDelete = '<form action="' . route('admin.movies.delete', $movie->id) . '" method="POST">
                        ' .@csrf_field() . method_field('DELETE') . '<button class="btn btn-danger">Hapus</button>
                        </form>';
                        $btnNonAktif = '';
                        if($movie->actived) {
                            $btnNonAktif = '<form action="' . route('admin.movies.nonaktif', $movie->id) . '" method="POST" class="me-2">
                        ' .@csrf_field() . method_field('PATCH') . '<button class="btn btn-danger">Non-aktif</button>
                        </form>';
                        }
                        return '<div class="d-flex justify-content-center align-items-center gap-2">'
                        .$btnDetail . $btnEdit . $btnNonAktif . $btnDelete .
                        '</div>';
        })
        ->rawColumns(['poster_img', 'actived_badge', 'action'])
        ->make(true);
    }

        // public function datatables()


    public function home()
    {
        // where('field', 'operator', 'value',) : mencari data
        // operator : = / < / <= / > / >= / <> / !=
        // orderBy('field', 'ASC/DESC') : mengurutkan data
        // ASC : a-z, 0-9, terlama-terbaru, DESC : 9-0, z-a, terbaru-terlama
        // limit(angka) : mengambil hanya beberapa data
        // get() : ambil hasil proses filter
        $movies = Movie::where('actived', 1)->orderBy('created_at', 'DESC')->limit(3)->get();
        return view('home', compact('movies')); //get mengambil data dengan filter
    }

    public function homeMovies(request $request)
    {
        // ambil $request dari input search
        $nameMovie = $request->search_movie;
        // cek jika input name:"seach_movie" tidak kosong
        if ($nameMovie != "") {
            // LIK : mencari dayayang mengandung teks ternetnu
            // % didepan : menacari kata belakang, % dinelaagn : mecari dara didepan, & didepan belakang : mencari di depan tengah belakang
            $movies = Movie::where('title', 'LIKE', '%' . $nameMovie . '%')->where('actived', 1)->orderBy('created_at', 'DESC')->get();
            // LIKE AGAR SAAT MENCARI TIDAK HARUS 100% SAMA
            // where orderby (eloquent) yang berhubungan middleware
        }
        $movies = Movie::where('actived', 1)->orderBy('created_at', 'DESC')->get();
        return view('movies', compact('movies'));
    }

    public function movieSchedule($movie_id, Request $request)
    {
        $sortPrice = $request->sort_price;

        if ($sortPrice) {
            $movie = Movie::where('id', $movie_id)->with([
                'schedules' => function ($q) use ($sortPrice) {
                    // karna mau mengurutkan price, price di shcedules. schedules itu ada di relasi jadi gunakan fungsi anonim
                    // $q : query eloquent, mewakilkan model relasi (model Schedule)
                    $q->orderBy('price', $sortPrice);
                },'schedules.cinema'])->first();
        } else {
            $movie = Movie::where('id', $movie_id)->with(['schedules', 'schedules.cinema'])->first();
        }
        // ambil data movie bersama schedule dan cinema
        // karena cinema adanua relasi dengan schedul ebukan ovie, jadi gunakan schedules.cinema
        // schedules.cinema : ambil relasi cinema dari scheudles
        // first() : karena mau ambil 1 film

        $sortAlfabet = $request->sort_alfabet;
        if ($sortAlfabet == 'ASC') {
            // sort_alfabet akan mengurutkan dari name yang ada do domce,a. Pada $movie cinema ada di relasi
            // 'schedules.cinema' posisi cinema sebagai relasi kedua. gunakan collection $movie untuk melakukan filter di relasi kedua
            $movie->schedules = $movie->schedules->sortBy(function($schedule) {
                return $schedule->cinema->name;
                // movie->schedules : collection (data dari varable $movie bagian relasi schedules)
                // sortBy : mengurutkan ASC dari collection, $shceudle : mewakilkan data schedule
            })->values(); //values() : mengambil ulang data collection yang udah di filter
        } elseif ($sortAlfabet == 'DESC') {
            $movie->schedules = $movie->schedules->sortByDesc(function($schedule) {
                return $schedule->cinema->name; //diurutkan berdasarkan data ini
            })->values();
            //sortByDesc : mengurutkan DESC dari collection
        }
        return view('schedule.detail', compact('movie'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.movie.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'genre' => 'required',
            'director' => 'required',
            'age_rating' => 'required',
            // mimes: memastikan ekstensi (jenis file) yang diupload
            'poster' => 'required|mimes:jpg,jpeg,png,svg,webp',
            'description' => 'required|min:10',
        ], [
            'title.required' => 'Judul film harus diisi',
            'duration.required' => 'Durasi film harus diisi',
            'genre.required' => 'Genre film harus diisi',
            'director.required' => 'Sutradara film harus diisi',
            'age_rating.required' => 'Usia minimal harus diisi',
            'poster.required' => 'Poster harus diisi',
            'poster.mimes' => 'Poster harus berupa JPG/JPEG/PNG/SVG/WEBP',
            'description.required' => 'Sinopsis harus diisi',
            'description.min' => 'Sinopsis harus diisi minimal 10 karakter',
        ]);
        // ambil file dari input
        $poster = $request->file('poster');
        // buat nama baru untuk filenya
        // format file baru yang diharapkan : <acak>-poster.jpg
        // getClientOriginalExtension() : mengambil ekstensi file yang diupload
        $namaFile = Str::random(10) . "-poster." . $poster->getClientOriginalExtension(); //rannd nama file nya secara acak
        // siimpan file ke folder storage : sotreAs("namasubfodler", namafile, "visibility")
        $path = $poster->storeAs("poster", $namaFile, "public");

        $createData = Movie::create([
            'title' => $request->title,
            'genre' => $request->genre,
            'duration' => $request->duration,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            // poster diisi dengan hasil storeAs(), hasil penyimpanan file di storage sebelumnya
            'poster' => $path,
            'description' => $request->description,
            'actived' => 1
        ]);

        if ($createData) {
            return redirect()->route('admin.movies.index')->with('success', 'Berhasil menambahkan data!');
        } else {
            return redirect()->back()->with('error', 'Gagal! Silahkan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie, $id)
    {
        $movie = Movie::find($id);
        return view('admin.movie.edit', compact('movie'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie, $id)
    {
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'genre' => 'required',
            'director' => 'required',
            'age_rating' => 'required',
            // mimes: memastikan ekstensi (jenis file) yang diupload
            'poster' => 'required|mimes:jpg,jpeg,png,svg,webp',
            'description' => 'required|min:10',
        ], [
            'title.required' => 'Judul film harus diisi',
            'duration.required' => 'Durasi film harus diisi',
            'genre.required' => 'Genre film harus diisi',
            'director.required' => 'Sutradara film harus diisi',
            'age_rating.required' => 'Usia minimal harus diisi',
            'poster.required' => 'Poster harus diisi',
            'poster.mimes' => 'Poster harus berupa JPG/JPEG/PNG/SVG/WEBP',
            'description.required' => 'Sinopsis harus diisi',
            'description.min' => 'Sinopsis harus diisi minimal 10 karakter',
        ]);
        // ambil data sebelemunya
        $movie = Movie::find($id);

        // Jika inpur file poster diisi
        if ($request->hasFile('poster')) {
            $filePath = storage_path('app/public/' . $movie->poster);
            // Jika file ada di storage path tersebut
            if (file_exists($filePath)) {
                // hapu file lama
                unlink($filePath);
            }
            $file = $request->file('poster');
            // buat nama baru untuk file
            $fileName = 'poster-' . Str::random(10) . '.' .
                $file->getClientOriginalExtension();
            $path = $file->storeAs('poster', $fileName, 'public');
        }

        $updateData = $movie->update([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            'poster' => $request->hasFile('poster') ? $path :
                $movie->poster,
            'description' => $request->description,
            'actived' => 1
        ]);

        if ($updateData) {
            return redirect()->route('admin.movies.index')->with('success', 'Berhasil memperbarui data!');
        } else {
            return redirect()->back()->with('error', 'Gagal! silahkan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($id)
    // {
    //     Movie::findOrFail($id);

    //     // Hapus file poster dari storage (pakai disk public)
    //     if($movies->poster && Storage::disk('public')->exists($movies->$poster)) {
    //         Storage::disk('public')->delete($movies->poster);
    //     }

    //     // Hapus record dari db
    //     $movies->delete();

    //     return redirect()->route('admin.movies.index')->with('success', 'Berhasil menghapus film');
    // }

    public function destroy($id)
    {

        $schedules = Schedule::where('movie_id', $id)->count();
        if ($schedules) {
            return redirect()->route('admin.movies.index')->with('error', 'Tidak dapat menghapus data Film! Data tertaut dengan jadwal tayang');
        }

        $movies = Movie::findOrFail($id);
        $movies->delete();
        return redirect()->route('admin.movies.index')->with('success', 'Berhasil menghapus data film!');
    }

    public function export()
    {
        // nama file yang akan di downloas
        // ekstensi antara xlsx/csv
        $fileName = "data-film.xlsx";
        // prosese download
        return Excel::download(new MovieExport, $fileName);
    }

    public function nonactive($id)
    {
        $movie = Movie::findOrFail($id);
        $nonActiveData = $movie->update(['actived' => 0]);

        if ($nonActiveData) {
            return redirect()->back()->with('success', 'Film berhasil di non-aktifkan');
        } else {
            return redirect()->back()->with('error', 'Gagal! silahkan coba lagi.');
        }
    }

    public function trash()
    {
        // onlyTrashed() -> filter data yang dihapus, delete_at BUKAN NULL
        $movieTrash = Movie::onlyTrashed()->get();
        return view('admin.movie.trash', compact('movieTrash'));
    }

    public function restore($id)
    {
        $movie = Movie::onlyTrashed()->find($id);
        // restore() -> mengembaliukan data yagn sudah dihapus (menghaps=us nilai tanggal pada delete_at)
        $movie->restore();
        return redirect()->route('admin.movies.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $movie = Movie::onlyTrashed()->find($id);
        if ($movie->poster && Storage::disk('public')->exists($movie->poster)) {
            Storage::disk('public')->delete($movie->poster);
        }
        // forceDelete() -> menghapus data secara permanen, data hilang bahkan dari db nya
        $movie->forceDelete();

        return redirect()->back()->with('success', 'Berhasil menghapus seutuhnya!');
    }
}
