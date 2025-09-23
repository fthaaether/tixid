<?php

use App\Http\Controllers\CinemaController;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', [MovieController::class, 'home'])->name('home');

Route::get('/schedules/detail', function () {
    // standar penulisan :
    // path (mengacu ke data/fitur) gunakan jamak, folder view fitur gunakan tunggal
    return view('schedule.detail');
})->name('schedules.detail');

Route::get('/logout', [UserController::class, 'logout'])->name('logout');

// untuk halman admin
// membuat group route dengan middlwware isAdmin,
// prefix() : memberikan path (/) awalan, / admin ditulis 1x bisa dipake berkali kali
Route::middleware('isAdmin')->prefix('/admin')->name('admin.')->group(function () {
    Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    // admin.cinemas.index admin.cinemas.index \create

    //bioskop
    Route::prefix('/cinemas')->name('cinemas.')->group(function () {
        Route::get('/index', [CinemaController::class, 'index'])->name('index');
        Route::get('/create', [CinemaController::class, 'create'])->name('create');
        Route::post('/store', [CinemaController::class, 'store'])->name('store');

        // parameter placeholder -> {id} : mencari data spesifik
        Route::get('/edit/{id}', [CinemaController::class, 'edit'])->name('edit');
        Route::put('/update{id}', [CinemaController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CinemaController::class, 'destroy'])->name('delete');
        // fungsi put mengirimkan data untuk diubah
    });

    //petugas
    Route::prefix('/users')->name('users.')->group(function () {
        Route::get('/index', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');

        // parameter placeholder -> {id} : mencari data spesifik
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::put('/update{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('delete');
        // fungsi put mengirimkan data untuk diubah
    });

    //film
    Route::prefix('/movies')->name('movies.')->group(function () {
        Route::get('/', [MovieController::class, 'index'])->name('index');
        Route::get('/create', [MovieController::class, 'create'])->name('create');
        Route::post('/store', [MovieController::class, 'store'])->name('store');
    });
});

Route::middleware('isGuest')->group(function () {
    Route::get('/login', function () {
        // standar penulisan :
        // path (mengacu ke data/fitur) gunakan jamak, folder view fitur gunakan tunggal
        return view('auth.login');
    })->name('login');

    Route::get('/signup', function () {
        // standar penulisan :
        // path (mengacu ke data/fitur) gunakan jamak, folder view fitur gunakan tunggal
        return view('auth.signup');
    })->name('signup');

    Route::post('/signup', [UserController::class, 'register'])->name('signup.send_data');
    //name harus unique

    Route::post('/auth', [UserController::class, 'authentication'])->name('auth'); //mau mengirim data makanya menggunakan post
});






