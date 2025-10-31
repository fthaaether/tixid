@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('staff.promos.trash') }}" class="btn btn-secondary me-2">Data Sampah</a>
            <a href="{{ route('staff.promos.export') }}" class="btn btn-secondary me-2">
                Export (.xlsx)</a>
            <a href="{{ route('staff.promos.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5 class="mt-3">Data Promo</h5>
        <table class="table table-bordered" id="promosTable">
            {{-- jika ingin seperti sebelumnya hapus class di tr nya --}}
            <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Kode Promo</th>
                <th>Total Potongan</th>
                <th>Aksi</th>
            </tr>
            </thead>
        </table>
    </div>
@endsection

@push('script')
    <script>
        $(function () {
            $('#promosTable').DataTable({
                processing: true,
                // data untuk datatable diproses secara serverside (controller)
                serverSide: true,
                // routing menuju fungsi yang memproses data untuk data table
                ajax: "{{ route('staff.promos.datatables') }}",
                // iritan column (td), pastikan urutan sesuai th
                // data : 'nama' -> naam diambil dari rawColumns jika addColu,ms, atau field dari model fillable
                columns: [
                    {
                        data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false,
                        searchable: false
                    },
                    {
                        data: 'promo_code', name: 'promo_code', orderable: true,
                        searchable: true
                    },
                    {
                        data: 'discount', name: 'discount', orderable: true,
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

