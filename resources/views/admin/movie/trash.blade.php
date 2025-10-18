@extends('templates.app')

@section('content')
    <div class="container my-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get
            ('success') }}</div>
        @endif
        @if (Session::get('error'))
            <div class="alert alert-danger">{{ Session::get
            ('error') }}</div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.movies.index') }}" class="btn btn-success">Kembali</a>
        </div>
        <h5 class="mb-3">Data Film</h5>
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Poster</th>
                <th>Judul Film</th>
                <th>Status Aktif</th>
                <th>Aksi</th>
            </tr>

            @foreach ($movieTrash as $index => $item)
                <tr>
                    <th>{{  $index + 1 }}</th>
                    <th>
                        <img src="{{ asset('storage/' . $item['poster']) }}" width="120">
                    </th>
                    <th>{{ $item['title'] }}</th>
                    <td>
                        @if ($item['actived'] == 1)
                        <span class="badge badge-success">Aktif</span>
                        @else
                        <span class="badge badge-danger">Non AKtif</span>

                        @endif
                    </td>
                    <th class="d-flex">
                        <form action="{{ route('admin.movies.restore', ['id' => $item['id']]) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success me-2">Kembalikan Data</button>
                        </form>
                        <form action="{{ route('admin.movies.delete_permanent', ['id' => $item['id']]) }}" method="POST">
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

{{-- mengisi stack
@push('script')
<script>
    function showModal(item) {
        // console.log(item)
        // pengambilan gambar di public
        // asset() fungsi php sehingga gunakan kurung kurawal 2 kali
        let image = "{{ asset('storage/') }}" + "/" + item.poster;
        // membuat konten yang akan ditambahkan
        // backtip (diatas tab) : menulis string lebih dari 1 baris
        let content = `
                <img src="${image}" width="120" class="d-block" mx-auto my-3">
                <ul>
                    <li>Judul :  ${item.title}</li>
                    <li>Durasi :  ${item.duration}</li>
                    <li>Genre :  ${item.genre}</li>
                    <li>Sutradara :  ${item.director}</li>
                    <li>Usia Minimal : <span class="badge badge-danger">${item.age_rating}</    ></li>
                    <li>Sinopsis : ${item.description}</li>
                </ul>
                `;

        // mengambil elemen html yang akan disimpan konten diatas : document.querySelector()
        let modalDetailBody = document.querySelector(#modalDetailBody);
        // isi HTML diatas ke id="modalDetailBody"
        modalDetailBody.innetHTML = content;
        let modalDetailBody = document.querySelector(#modalDetailBody);
        // munculkan modal bootstrap
        new bootstrap.Modal(modalDetail).show();
    }
</script>
@endpush --}}

@push('script')
    <script>
        function showModal(item) {
            // console.log(item)
            // pengambilan gambar di public
            // asset() fungsi php sehingga gunakan kurung kurawal 2 kali
            let image = "{{ asset('storage/') }}" + "/" + item.poster;
            // membuat konten yg akan di tambahkan
            // backtip (diatas tab) : menulis strin glebih dari 1 baris
            let content = `
                                                                        <img src="${image}" width="120" class="d-block mx-auto my-3">
                                                                        <ul>
                                                                            <li>Judul : ${item.title}</li>
                                                                            <li>Durasi : ${item.duration}</li>
                                                                            <li>Genre : ${item.genre}</li>
                                                                            <li>Sutradara : ${item.director}</li>
                                                                            <li>Usia Minimal : <span class="badge badge-danger">${item.age_rating}</span></li>
                                                                            <li>Sinopsis : ${item.description}</li>
                                                                        </ul>
                                                                        `;
            // mengambil element html yg akan di simpan di konten di atas : document.querySelector()
            let modalDetailBody = document.querySelector("#modalDetailBody");
            // isi html di atas ke id="modalDetailBody"
            modalDetailBody.innerHTML = content;
            let modalDetail = document.querySelector("#modalDetail");
            // munculkan modal bootstrap
            new bootstrap.Modal(modalDetail).show();
        }
    </script>
@endpush
