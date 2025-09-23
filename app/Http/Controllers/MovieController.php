<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // //Model::all() => mengambil semua data dari model
        $movies = Movie::all();
        return view('admin.movie.index', compact('movies')); //movie nya gapake s karena nama foldermnya juga ga apake s
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
    public function edit(Movie $movie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        //
    }
}
