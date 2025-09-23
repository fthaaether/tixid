{{-- import templates --}}
@extends('templates.app')

{{-- ngisi yield --}}
@section('content')
@if (Session::get('success'))
    {{-- Auth:user() : menagambil data user yang login --}}
    <div class="alert alert-success">{{ Session::get('success') }}
        <b>Selamat Datang, {{ Auth::user()->name }}</b>
    </div>
    {{-- Auth:user()-> : kata name diambil dari model User - fillable --}}
@endif
@if (Session::get('logout'))
<div class="alert alert-warning">{{ Session::get('logout') }}</div>
@endif
    <div class="dropdown">
        <button class="btn btn-light
            w-100 text-start dropdown-toggle" type="button" id="dropdownMenuButton" data-mdb-dropdown-init
            data-mdb-ripple-init aria-expanded="false">
            <i class="fa-solid fa-location-dot"></i> Bogor (Kota Indah Sejux Nyiaman)
        </button>
        <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
            <li><a class="dropdown-item" href="#">Jakarta Timur</a></li>
            <li><a class="dropdown-item" href="#">Jakarta Barat</a></li>
            <li><a class="dropdown-item" href="#">Korea Selatan</a></li>
        </ul>
    </div>

    {{-- slider --}}
    <!-- Carousel wrapper -->
    <div id="carouselBasicExample" class="carousel slide carousel-fade" data-mdb-ride="carousel" data-mdb-carousel-init>
        <!-- Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="2"
                aria-label="Slide 3"></button>
        </div>

        <!-- Inner -->
        <div class="carousel-inner">
            <!-- Single item -->
            <div class="carousel-item active">
                <img style="height: 600px"
                    src="https://asset.tix.id/microsite_v2/4b5ba50f-6183-419c-8236-9f1b06a436d9.webp"
                    class="d-block w-100" alt="Sunset Over the City" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>First slide label</h5>
                    <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
                </div>
            </div>

            <!-- Single item -->
            <div class="carousel-item">
                <img style="height: 600px" src="https://asset.tix.id/microsite_v2/d2b394a8-caae-4e0b-b455-7fdb2139ec29.webp"
                    class="d-block w-100" alt="Canyon at Nigh" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>Second slide label</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
            </div>

            <!-- Single item -->
            <div class="carousel-item">
                <img style="height: 600px" src="https://asset.tix.id/microsite_v2/df671081-1959-4af4-8082-9fb14ca17594.webp"
                    class="d-block w-100" alt="Cliff Above a Stormy Sea" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>Third slide label</h5>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
                </div>
            </div>
        </div>
        <!-- Inner -->

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <!-- Carousel wrapper -->

    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center">
            {{-- konten kiri --}}
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-clapperboard"></i>
                <h5 class="ms-2 mt-2">Sedang Tayang</h5>
            </div>
            {{-- konten kanan --}}
            <div class="">
                <button class="btn btn-warning rounded-pill">Semua</button>
            </div>
        </div>
    </div>

    <div class="container d-flex gap-2">
        {{-- gap-1 : jarak antar komponen --}}
        <button class="btn btn-outline-primary rounded-pill">Semua Film</button>
        <button class="btn btn-outline-secondary rounded-pill">XXI</button>
        <button class="btn btn-outline-secondary rounded-pill">Cinepolis</button>
        <button class="btn btn-outline-secondary rounded-pill">IMAX</button>
    </div>


    <div class="container d-flex gap-2 mt-4 justify-content-center">
        <div class="card" style="width: 18rem">
            <img src="https://m.media-amazon.com/images/M/MV5BYzdjMDAxZGItMjI2My00ODA1LTlkNzItOWFjMDU5ZDJlYWY3XkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg" class="card-img-top"
                alt="Fissure in Sandstone" style="height: 350px; object-fit: cover;">
            {{-- object-fit : cover -> gambar ukurannya sesuai aturan height dan width --}}
            <div class="card-body bg-primary  text-warning" style="padding: 0 !important; text-align: center;">
                {{-- karna default card-text ada paddingnya, biar padding yang dibaca dari style jadi dikasi !important
                (memprioritaskan
                styling) --}}
                <p class="card-text" style="padding: 0 !important; text-align: center;">
                    <a href="{{ route('schedules.detail') }} "class="text-warning">BELI TIKET</a>
                </p>
            </div>
        </div>

        <div class="card" style="width: 18rem">
            <img src="https://cdn11.bigcommerce.com/s-ydriczk/products/89967/images/97352/black-panther-wakanda-forever-final-style-original-movie-poster-us-one-sheet-buy-now-at-starstills
                __05962.1670494038.450.659.jpg?c=2" class="card-img-top" alt="Fissure in Sandstone"
                style="height: 350px; object-fit: cover;">
            {{-- object-fit : cover -> gambar ukurannya sesuai aturan height dan width --}}
            <div class="card-body bg-primary text-warning" style="padding: 0 !important; text-align: center;">
                {{-- karna default card-text ada paddingnya, biar padding yang dibaca dari style jadi dikasi !important
                (memprioritaskan
                styling) --}}
                <p class="card-text" style="padding: 0 !important; text-align: center;">
                    <a href="{{ route('schedules.detail') }}" class="text-warning">BELI TIKET</a>
                </p>
            </div>
        </div>

        <div class="card" style="width: 18rem">
            <img src="https://i.ebayimg.com/images/g/6~sAAOSwOoZk5oO1/s-l1200.jpg" class="card-img-top"
                alt="Fissure in Sandstone" style="height: 350px; object-fit: cover;">
            {{-- object-fit : cover -> gambar ukurannya sesuai aturan height dan width --}}
            <div class="card-body bg-primary text-warning" style="padding: 0 !important; text-align: center;">
                {{-- karna default card-text ada paddingnya, biar padding yang dibaca dari style jadi dikasi !important
                (memprioritaskan
                styling) --}}
                <p class="card-text" style="padding: 0 !important; text-align: center;">
                    <a href="{{ route('schedules.detail') }} "class="text-warning">BELI TIKET</a>
                </p>
            </div>
        </div>

        <div class="card" style="width: 18rem">
            <img src="https://images-cdn.ubuy.co.in/635006c5268218559911b466-money-heist-la-casa-de-papel-part-4.jpg"
                class="card-img-top" alt="Fissure in Sandstone" style="height: 350px; object-fit: cover;">
            {{-- object-fit : cover -> gambar ukurannya sesuai aturan height dan width --}}
            <div class="card-body bg-primary text-warning" style="padding: 0 !important; text-align: center;">
                {{-- karna default card-text ada paddingnya, biar padding yang dibaca dari style jadi dikasi !important
                (memprioritaskan
                styling) --}}
                <p class="card-text" style="padding: 0 !important; text-align: center;">
                    <a href="{{ route('schedules.detail') }} "class="text-warning">BELI TIKET</a>
                </p>
            </div>
        </div>
    </div>

    <footer class="bg-body-tertiary text-center text-lg-start mt-5">
        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
            © 2025 Copyright:
            <a class="text-body" href="https://mdbootstrap.com/">TIXID.com</a>
        </div>
        <!-- Copyright -->
    </footer>
@endsection
