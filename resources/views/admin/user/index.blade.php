@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.users.export') }}" class="btn btn-secondary me-2">
                Export (.xlsx)</a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5 class="mt-3">Data Bioskop</h5>
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Nama Petugas</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>

            </tr>
            {{-- $users : dari compact, karena pakai all jd array dimensi --}}
            @foreach ($users as $index => $item)
                <tr>
                    {{-- $index dari 0, biar muncul dr 1 -> +1 --}}
                    <th>{{  $index + 1 }}</th>
                    {{-- name, location dari fillable model Cinema --}}
                    <th>{{ $item['name'] }}</th>
                    <th>{{ $item['email'] }}</th>
                    <th>
                        @if ($item['role'] == 'admin')
                            <span class="alert alert-primary p-1 d-inline-block">admin</span>

                        @elseif($item['role'] == 'staff')
                            <span class="alert alert-success p-1 d-inline-block">staff</span>

                        @endif
                    </th>
                    <th class="d-flex">
                        {{-- ['id' => $item['id']] : mengirimkan $item['id'] ke route {id} --}}
                        <a href="{{ route('admin.users.edit', ['id' => $item['id']])}}" class="btn btn-secondary">Edit</a>

                        <form action="{{ route('admin.users.delete', ['id' => $item['id']]) }}" method="post">
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
