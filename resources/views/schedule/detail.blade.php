@extends('templates.app')

@section('content')

    {{-- <div class="bg-image" style="background-image: url('https://timelinecovers.pro/facebook-cover/download/interstellar4-facebook-cover.jpg');
                    height: 70vh"> --}}
        <div class="container pt-5">
            <div class="w-75 d-block m-auto">
                <div class="d-flex">
                    <div style="width: 150px; height: 200px;">
                        <img src="https://m.media-amazon.com/images/M/MV5BYzdjMDAxZGItMjI2My00ODA1LTlkNzItOWFjMDU5ZDJlYWY3XkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg"
                            alt="" class="w-100">
                    </div>
                    <div class="ms-5 mt-4">
                        <h5>Interstellar</h5>
                        <table>
                            <tr>
                                <td>
                                    <b class="text-secondary">Genre</b>
                                </td>
                                <td class="px-3">

                                </td>
                                <td>
                                    Science Fiction / Action / Adventure / Suspense / Thriller / Mystery / Drama
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b class="text-secondary">Durasi</b>
                                </td>
                                <td class="px-3">

                                </td>
                                <td>
                                    2 Jam 49 Menit
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b class="text-secondary">Sutradara</b>
                                </td>
                                <td class="px-3">

                                </td>
                                <td>
                                    Christopher Nolan
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b class="text-secondary">Rating Usia</b>
                                </td>
                                <td class="px-3">

                                </td>
                                <td>
                                    <span class="badge badge-danger">13+</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="w-100 row mt-5">
                    <div class="col-6 pe-5">
                        <div class="d-flex flex-column justify-content-end align-items-end">
                            <div class="d-flex align-items-center">
                                <h3 class="text-warning me-2">9.5</h3>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <small>268.213 Vote</small>
                        </div>
                    </div>
                    <div class="col ps-5" style="border-left: 2px solid #c7c7c7">
                        <div class="d-flex align-items-center">
                            <div class="fas fa-heart text-danger me-2">

                            </div>
                            <b>
                                Masukkan Watchlist
                            </b>

                        </div>
                        <small>268.186 Orang</small>
                    </div>
                </div>
                <div class="dropdown mt-3">
                    <button class="btn btn-light w-100 text-start dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
                        <i class="fa-solid fa-location-dot"></i> Bogor
                    </button>
                    <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#">Jakarta</a></li>
                        <li><a class="dropdown-item" href="#">Depok</a></li>
                        <li><a class="dropdown-item" href="#">Tangerang</a></li>
                        <li><a class="dropdown-item" href="#">Bekasi</a></li>
                </div>
                <div class="mb-5">
                    <div class="w-100 my-3">
                        <i class="fa-solid fa-building"></i>
                        <b class="ms-2">
                            Lippo Plaza Ekalokasari
                        </b>
                        <br>
                        <small class="ms-3">
                            Jl. Siliwangi No.123, Sukasari, Kec. Bogor Tim., Kota Bogor, Jawa Barat 16142
                        </small>
                        <div class="d-flex gap-3 ps-3 my-2">
                            <div class="btn btn-outline-secondary">11.00</div>
                            <div class="btn btn-outline-secondary">13.50</div>
                            <div class="btn btn-outline-secondary">16.40</div>
                            <div class="btn btn-outline-secondary">19.30</div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-5">
                    <div class="w-100 my-3">
                        <i class="fa-solid fa-building"></i>
                        <b class="ms-2">
                            Ramayana Tajur
                        </b>
                        <br>
                        <small class="ms-3">
                            Jl. Raya Tajur, Pakuan, Muarasari, Kec. Bogor Sel., Kota Bogor, Jawa Barat 16134
                        </small>
                        <div class="d-flex gap-3 ps-3 my-2">
                            <div class="btn btn-outline-secondary">11.00</div>
                            <div class="btn btn-outline-secondary">13.50</div>
                            <div class="btn btn-outline-secondary">16.40</div>
                            <div class="btn btn-outline-secondary">19.30</div>
                        </div>
                    </div>
                </div>
                <div class="w-100 p-2 bg-light text-center fixed-bottom">
                    <a href="">
                        <i class="fa-solid fa-ticket"></i>
                        BELI TIKET
                    </a>
                </div>
            </div>
        </div>
@endsection
