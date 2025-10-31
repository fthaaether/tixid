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
            <a href="{{ route('admin.cinemas.trash') }}" class="btn btn-secondary me-2">Data Sampah</a>
            <a href="{{ route('admin.cinemas.export') }}" class="btn btn-secondary me-2">
                Export (.xlsx)</a>
            <a href="{{ route('admin.cinemas.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5 class="mt-3">Data Bioskop</h5>
        <table class="table table-bordered" id="cinemasTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Bioskop</th>
                    <th>Lokasi Bioskop</th>
                    <th>Aksi</th>
                </tr>
            </thead>

        </table>
    </div>
@endsection

@push('script')
    <script>
        $(function () {
            $('#cinemasTable').DataTable({
                processing: true, // loading
                // data untuk datatable diproses secara serverside (controller)
                serverSide: true,
                // routing menuju fungsi yang memproses data untuk data table
                ajax: "{{ route('admin.cinemas.datatables') }}",
                // urutan column (td), pastikan urutan sesuai th
                // data : 'nama' -> nama diambil dari rawColumns jika addColumns, atau field dari model fillable
                columns: [
                    {
                        data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name', name: 'name', orderable: true,
                        searchable:     true
                    },
                    {
                        data: 'location', name: 'location', orderable: true,
                        searchable: true
                    },
                    {
                        data: 'action', name: 'action', orderable: false    ,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endpush
