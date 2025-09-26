<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MovieExport;

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

    public function homeMovies()
    {
        $movies = Movie::where('actived', 1)->orderBy('created_at', 'DESC')->get();
        return view('movies', compact('movies'));
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
        $movies = Movie::findOrFail($id);
        if ($movies->poster && Storage::disk('public')->exists($movies->poster)) {
            Storage::disk('public')->delete($movies->poster);
        }
        $movies->delete();
        return redirect()->route('admin.movies.index')->with('success', 'Berhasil menghapus data film!');
    }

    public function export()
    {
        // nama file yang akan di downloas
        // ekstensi antara xlsx/csv
        $fileName = "data-film.xlsx";
        // prosese download
        return Excel::download(new MovieExport,$fileName);
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
}
