<?php

use App\Http\Controllers\CinemaController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ScheduleController;
use App\Models\Schedule;

Route::get('/', [MovieController::class, 'home'])->name('home');
Route::get('/movies/active', [MovieController::class, 'homeMovies'])->name('home.movies.all');

Route::get('/schedules/detail/{movie_id}', [MovieController::class, 'movieSchedule'])
->name('schedules.detail');

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

        Route::get('/export', [CinemaController::class, 'export'])->name('export');

        Route::get('trash', [CinemaController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [CinemaController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [CinemaController::class, 'deletePermanent'])->name('delete_permanent');

        // datatables
        Route::get('/datatables', [CinemaController::class, 'datatables'])->name('datatables');


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

        Route::get('/export', [UserController::class, 'export'])->name('export');

        Route::get('trash', [UserController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [UserController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [UserController::class, 'deletePermanent'])->name('delete_permanent');

        // datatables
        Route::get('/datatables', [UserController::class, 'datatables'])->name('datatables');
    });

    //film
    Route::prefix('/movies')->name('movies.')->group(function () {
        Route::get('/', [MovieController::class, 'index'])->name('index');
        Route::get('/create', [MovieController::class, 'create'])->name('create');
        Route::post('/store', [MovieController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MovieController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [MovieController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [MovieController::class, 'destroy'])->name('delete');
        Route::patch('/nonactive/{id}', [MovieController::class, 'nonactive'])->name('nonactive');
        Route::patch('/active/{id}', [MovieController::class, 'active'])->name('active');
        Route::get('/export', [MovieController::class, 'export'])->name('export');
        Route::get('trash', [MovieController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [MovieController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [MovieController::class, 'deletePermanent'])->name('delete_permanent');
        Route::get('/datatables', [MovieController::class, 'datatables'])->name('datatables');
    });
});

Route::prefix('/staff')->name('staff.')->group(function () {
    Route::get('/dashboard', function () {
        return view('staff.dashboard');
    })->name('dashboard');
    // Promo
    Route::prefix('/promos')->name('promos.')->group(function (): void {
        Route::get('/index', [PromoController::class, 'index'])->name('index');
        Route::get('/create', [PromoController::class, 'create'])->name('create');
        Route::post('/store', [PromoController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PromoController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [PromoController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [PromoController::class, 'destroy'])->name('delete');
        Route::get('/export', [PromoController::class, 'export'])->name('export');
        Route::get('trash', [PromoController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [PromoController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [PromoController::class, 'deletePermanent'])->name('delete_permanent');
        // datatables
        Route::get('/datatables', [PromoController::class, 'datatables'])->name('datatables');
    });

    // Jadwal tayang
    Route::prefix('/schedules')->name('schedules.')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('index');
        Route::post('/store', [ScheduleController::class, 'store'])->name('store');
        Route::get('/edit/{id}',[ScheduleController::class, 'edit'])->name('edit');
        Route::patch('/update/{id}', [ScheduleController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ScheduleController::class, 'destroy'])->name('delete');
        Route::get('trash', [ScheduleController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [ScheduleController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [ScheduleController::class, 'deletePermanent'])->name('delete_permanent');

        Route::get('/export', [ScheduleController::class, 'export'])->name('export');

        // datatables
        Route::get('/datatables', [ScheduleController::class, 'datatables'])->name('datatables');
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

// menu "bioskop" pada navbar user
Route::get('/cinemas/list', [CinemaController::class, 'cinemaList'])->name('cinemas.list');
Route::get('/cinemas/{cinema_id}/schedules',
[CinemaController::class, 'cinemaSchedules'])->name('cinema.schedules');

Route::middleware('isUser')->group(function() {
    Route::get('/schedules/{scheduleId}/hours/{hourId}', [ScheduleController::class, 'showSeats'])->name('schedules.show-seats');

    Route::prefix('/tickets')->name('tickets.')->group(function(){
        Route::post('/', [TicketController::class, 'store'])->name('store');
    });
});

