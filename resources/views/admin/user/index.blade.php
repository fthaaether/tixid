@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.users.trash') }}" class="btn btn-secondary me-2">Data Sampah</a>
            <a href="{{ route('admin.users.export') }}" class="btn btn-secondary me-2">
                Export (.xlsx)</a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5 class="mt-3">Data Bioskop</h5>
        <table class="table table-bordered" id="usersTable">
            <thead>
                <tr>
                <th>#</th>
                <th>Nama Petugas</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
            </thead>
        </table>
    </div>
@endsection

@push('script')
    <script>
        $(function () {
            $('#usersTable').DataTable({
                processing: true,
                // data untuk datatable diproses secara serverside (controller)
                serverSide: true,
                // routing menuju fungsi yang memproses data untuk data table
                ajax: "{{ route('admin.users.datatables') }}",
                // iritan column (td), pastikan urutan sesuai th
                // data : 'nama' -> naam diambil dari rawColumns jika addColu,ms, atau field dari model fillable
                columns: [
                    {
                        data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name', name: 'name', orderable: true,
                        searchable: true
                    },
                    {
                        data: 'email', name: 'email', orderable: true,
                        searchable: true
                    },
                    {
                        data: 'role', name: 'role', orderable: true,
                        searchable: true
                    },
                    {
                        data: 'action', name: 'action', orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endpush
