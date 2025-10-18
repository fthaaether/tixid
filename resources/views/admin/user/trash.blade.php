@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
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
            @foreach ($userTrash as $index => $item)
                <tr>
                    {{-- $index dari 0, biar muncul dr 1 -> +1 --}}
                    <th>{{  $index + 1 }}</th>
                    {{-- name, location dari fillable model Cinema --}}
                    <th>{{ $item['name'] }}</th>
                    <th>{{ $item['email'] }}</th>
                    <th>
                        @if($item['role'] == 'admin')
                            <span class="alert alert-primary p-1 d-inline-block">admin</span>
                        @elseif($item['role'] == 'staff')
                            <span class="alert alert-success p-1 d-inline-block">staff</span>
                        @endif
                    </th>
                    <th class="d-flex">
                        <form action="{{route('admin.users.restore', ['id' => $item['id']])}}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success me-2">Kembalikan Data</button>
                        </form>
                        <form action="{{route('admin.users.delete_permanent', ['id' => $item['id']])}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus Permanen</button>
                        </form>
                    </th>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
