@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('staff.promos.index') }}" class="btn btn-secondary">Kembali</a>
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
            @foreach ($promoTrash as $index => $item)
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
                        <form action="{{route('staff.promos.restore', ['id' => $item['id']])}}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success me-2">Kembalikan Data</button>
                        </form>
                        <form action="{{route('staff.promos.delete_permanent', ['id' => $item['id']])}}" method="POST">
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
