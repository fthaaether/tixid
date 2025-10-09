@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        @if (Session::get('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.cinemas.index') }}" class="btn btn-success">Kembali</a>
        </div>
        <h5 class="mt-3">Data Bioskop</h5>
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Nama Bioskop</th>
                <th>Lokasi Bioskop</th>
                <th>Aksi</th>
            </tr>

            {{-- $cinemas : dari compact, karena pakai all jd array dimensi --}}
            @foreach ($cinemaTrash as $index => $item)
                <tr>
                    {{-- $index dari 0, biar muncul dr 1 -> +1 --}}
                    <th>{{  $index + 1 }}</th>
                    {{-- name, location dari fillable model Cinema --}}
                    <th>{{ $item['name'] }}</th>
                    <th>{{ $item['location'] }}</th>
                    <th class="d-flex">
                        {{-- ['id' => $item['id']] : mengirimkan $item['id'] ke route {id} --}}
                        <a href="{{ route('admin.cinemas.edit', ['id' => $item['id']])}}" class="btn btn-secondary">Edit</a>

                        <form action="{{ route('admin.cinemas.delete', ['id' => $item['id']]) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger">Hapus</button>
                        </form>


                    </th>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
