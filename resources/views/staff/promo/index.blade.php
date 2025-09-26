@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('staff.promos.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5 class="mt-3">Data Promo</h5>
        <table class="table table-bordered ">
            {{-- jika ingin seperti sebelumnya hapus class di tr nya --}}
            <tr class="text-center">
                <th>No</th>
                <th>Kode Promo</th>
                <th>Total Potongan</th>
                <th>Aksi</th>
            </tr>
            @foreach ($promos as $index => $item)
                <tr>
                    <th class="text-center">{{ $index + 1 }}</th>
                    <th>{{ $item['promo_code'] }}</th>
                    <th>
                        @if ($item['type'] == 'percent')
                            {{ $item['discount'] }}%
                        @else
                            Rp {{ number_format($item['discount'], 0, ',', '.') }}
                        @endif
                    </th>
                    {{-- tipe dan jumlah potongan disatukan pake if kayaknya --}}
                    {{-- jika ingin tombol seperti yang awal, hapus justify-content-center di classnya dan class di tombol edit
                    --}}
                    <th class="d-flex">
                        {{-- ['id' => $item['id']] : mengirim $item['id'] ke route {id} --}}
                        <a href="{{ route('staff.promos.edit', ['id' => $item['id']]) }}" class="btn btn-primary mx-2">Edit</a>
                        <form action="{{route('staff.promos.delete', ['id' => $item['id']])}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </th>
                </tr>
            @endforeach
        </table>
    </div>
@endsection