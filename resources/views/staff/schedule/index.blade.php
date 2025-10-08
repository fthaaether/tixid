@extends('templates.app')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-end">
            {{-- menggunakan button karena untuk modal bukan pindah halaman --}}
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd">Tambah Data</button>
        </div>
        <h3 class="my-3">Data Jadwal Tayangan</h3>
        @if (Session::get('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Nama Bioskop</th>
                <th>Judul Film</th>
                <th>Harga</th>
                <th>Jadwal Tayangan</th>
                <th>Aksi</th>
            </tr>
            @foreach ($schedules as $key => $schedule)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    {{-- memunculkan detail relasi : $item['namarelasi']['data'] --}}
                    <td>{{ $schedule['cinema']['name'] }}</td>
                    <td>{{ $schedule['movie']['title'] }}</td>
                    <td>Rp. {{ number_format($schedule['price'], 0, ',', '.') }}</td>
                    {{-- karena hours, array munculkan dengan loop --}}
                    <td>
                        <ul>
                            @foreach ($schedule['hours'] as $hours)
                                <li>{{ $hours }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="d-flex">
                        <a href="" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger ms-2">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </table>

        {{-- modal --}}

        <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('staff.schedules.store') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="cinema_id" class="col-form-label">Bioskop:</label>
                                <select name="cinema_id" id="cinema_id" class="form-select @error('cinema_id')
                                is-invalid @enderror">
                                    <option disabled hidden selected>Pilih Bioskop</option>
                                    {{-- munculkan opsi sesuai data dari cinemas --}}
                                    @foreach ($cinemas as $cinema)
                                        {{-- karena FK cinema_id perlu data id dari cinema, jadi value ambil ['id'], tulisan
                                        yang dmunculkan nama cinemanya --}}
                                        <option value="{{ $cinema['id'] }}">{{ $cinema['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('cinema_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="movie_id" class="form-label">Film :</label>
                                <select name="movie_id" id="movie_id" class="form-select @error('movie_id')
                                is-invalid @enderror">
                                    <option disabled hidden selected>Pilih Film</option>
                                    @foreach ($movies as $movie)
                                        <option value="{{ $movie['id'] }}">{{ $movie['title'] }}</option>
                                    @endforeach
                                </select>
                                @error('movie_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga :</label>
                                <input type="number" name="price" id="price" class="form-control @error('price')
                                    is-invalid
                                @enderror">
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            @if ($errors->has('hours.*'))
                                {{-- jika ada error harus array, ambil 1 error --}}
                                <small class="text-danger">{{ $errors->first('hours.*') }}</small>
                            @endif
                            <div class="mb-3">
                                <label for="hours" class="form-label">Jam Tayang :</label>
                                <input type="time" name="hours[]" id="hours" class="form-control
                                    @if ($errors->has('hours.*'))
                                        is-invalid
                                    @endif">
                                {{-- sediakan tempat untuk penambahan input baru dari JS, gunakan id untuk pemanggilan JS
                                --}}
                                <div id="aditionalInput"></div>
                                {{-- button untuk menambahkan inputan baru --}}
                                <button class="btn btn-outline-primary mt-2" type="button" onclick="addInput()">+Tambah
                                    Input</button>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function addInput() {
            let content = `<input type="time" name="hours[]" id="hours" class="form-control mt-2">`;
            // ambil tempat input akan disimpan
            let wadah = document.querySelector("#aditionalInput");
            // karena input akan terus bertambah, gunakan +=
            wadah.innerHTML += content;
        }
    </script>

    {{-- pengecekan php jika ada error validasi apapun --}}

    @if ($errors->any())
        {{-- jika ada error, modal jangan ditutup. buka kembali --}}
        <script>
            let modalAdd = document.querySelector(#modalAdd);
            new bootstrap.Modal(modalAdd).show();
        </script>

    @endif
@endpush