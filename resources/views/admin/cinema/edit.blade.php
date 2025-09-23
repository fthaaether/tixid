@extends('templates.app')

@section('content')
    <div class="w-75 d-block mx-auto my-5 p-4">
        <h5 class="text-center mb-3">Edit Data Bioskop</h5>
        <form method="POST" action="{{route('admin.cinemas.update', ['id' => $cinema['id']]) }}">
            @csrf
            {{-- menimpamethod ="POST" html menjadi PUT --}}

            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nama Bioskop</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid
                @enderror" value="{{ $cinema['name'] }}">
                @error('name')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Lokasi</label>
                {{-- $cinema mengambil data cinema yang akan diedit, dr controller edit bagian compact. dimunculkan di input dengan value="" dan textarea di tnegah2 penutup --}}
                <textarea name="location" id="location" rows="5" class="form-control @error('location')
                is-invalid
                @enderror">{{ $cinema['location'] }}</textarea>
                @error('location')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
    </div>
@endsection
