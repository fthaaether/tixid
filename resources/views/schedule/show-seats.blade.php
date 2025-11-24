@extends('templates.app')

@section('content')
    <div class="container card my-5 p-4" style="margin-bottom: 14% !important">
        <div class="card-body">
            <b>{{ $schedule['cinema']['name'] }}</b>
            {{-- mengambil tgl hari ini : now(). format ('d F, Y') F nama bulan --}}
            <b>{{ now()->format('d F, Y') }} <br> {{ $hour }}</b>
            <br>
            <div class="alert alert-secondary">
                <i class="fa-solid fa-info text-danger me-3"></i>
                Anak usia 2 tahun keatas wajib membeli tiket.
            </div>
            <div class="w-50 d-block mx-auto my-3">
                <div class="row">
                    <div class="col-4 d-flex">
                        <div style="background : #112646; width: 20px; height: 20px;"></div>
                        <span class="ms-2">Kursi Tersedia</span>
                    </div>
                    <div class="col-4 d-flex">
                        <div style="background : blue; width: 20px; height: 20px;"></div>
                        <span class="ms-2">Kursi Dipilih</span>
                    </div>
                    <div class="col-4 d-flex">
                        <div style="background : #eaeaea; width: 20px; height: 20px;"></div>
                        <span class="ms-2">Kursi Terjual</span>
                    </div>
                </div>
            </div>

            @php
                // membuat array dengan rentang tertentu : range()
                $rows = range('A', 'H');
                $cols = range(1, 18);
            @endphp
            {{-- looping A-H ke bawah --}}
            @foreach ($rows as $row)
                {{-- bikin looping 1-18 ke samping (d-flex) --}}
                <div class="d-flex justify-content-center">
                    @foreach ($cols as $col)
                        {{-- bikin style kotak no kursi --}}
                        {{-- jika kursi no 7 kasi kotak kosong untuk jalan --}}
                        @if ($col == 7)
                            <div style="width : 50px"></div>
                        @endif
                        @php
                            $seat = $row . "-" . $col;
                        @endphp
                        {{-- cek apakah di array $seatFormat ada data kursi ini : in_array() --}}
                        @if (in_array($seat, $seatsFormat))
                            <div style="background: #eaeaea; color: black; width: 40px; height: 35px; margin: 5px;
                                                                    border-radius: 5px; text-align: center; padding-top: 3px;
                                                                    ('{{ $schedule->price }}', '{{ $row }}', '{{ $col }}', this)">
                                <small><b>{{ $row }}-{{ $col }}</b></small>
                            </div>
                        @else
                            <div style="background: #112646; color: white; width: 40px; height: 35px; margin: 5px;
                                                                    border-radius: 5px 0%; text-align: center; padding-top: 3px;
                                                                    cursor: pointer;" onclick="selectSeat
                                                                    ('{{ $schedule->price }}', '{{ $row }}', '{{ $col }}', this)">
                                <small><b>{{ $row }}-{{ $col }}</b></small>
                            </div>
                        @endif
                        @if ($col == 13)
                            <div style="width : 50px"></div>
                        @endif

                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    <div class="fixed-bottom">
        <div class="w-100 bg-light text-center py-3" style="border: 1px solid black">
            <b>LAYAR BIOSKOP</b>
        </div>
        <div class="row bg-light">
            <div class="col-6 text-center p-3" style="border: 1px solid black">
                <b>Total Harga</b>
                <br>
                <b id="totalPrice">Rp. -</b>
            </div>
            <div class="col-6 text-center p-3" style="border: 1px solid black">
                <b>Kursi Dipilih</b>
                <br>
                <b id="selectedSeat">-</b>
            </div>
            {{-- <div class="col-6 text-center p-3" style="border: 1px solid black">
                <b id="selectedSeat">Kursi Dipilih</b>
                <br>
            </div> --}}
        </div>
        {{-- input:hidden menyembunullan monten khtml, digunaakan janua util mentimpan niai php untuk digunakan di JS --}}
        <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
        <input type="hidden" name="schedule_id" id="schedule_id" value="{{ $schedule->id }}">
        <input type="hidden" name="hour" id="hour" value="{{ $hour }}">
        <div class="w-100 bg-light text-center py-3" style="font-weight: bold" id="btnCreateOrder">RINGKASAN ORDER</div>
    </div>
@endsection

@push('script')
    <script>
        let seats = []; // menyimpan data kursi yang sudah dipiih, bisa lebih dari 1
        // biar bisa dipake di 2 function, didefinisikan ......
        let totalPrice = 0;
        function selectSeat(price, row, col, element) {
            // buat format A-1
            let seat = row + "-" + col;
            // cek apakah kursi tersebut ada di array seats atau tidak
            // indexOf : cek item array dan ambil index nya
            let indexSeat = seats.indexOf(seat);
            // jika ada dapet index nya jika tidak ada A-1
            if (indexSeat == -1) {
                // kasi warna biru terang
                seats.push(seat);
                element.style.background = 'blue';
            } else {
                // jika ada, maka klik kali ini untuk menghapus kursi (batal pilih)
                seats.splice(indexSeat, 1); // hapus data index ke (yang ketemu)
                // kembalikan warna ke biru tua
                element.style.background = '#112646';
            }

            totalPrice = price * seats.length; // length : kaya count di php, itung isi array
            let totalPriceElement = document.querySelector("#totalPrice");
            totalPriceElement.innerText = "Rp. " + totalPrice;

            let selectedSeatElement = document.querySelector("#selectedSeat");
            // mengubah array jadi string dipisahkan dengan koma : join()
            selectedSeatElement.innerText = seats.join(', ');

            let btnCreateOrder = document.querySelector("#btnCreateOrder");
            if (seats.length > 0) {
                btnCreateOrder.style.background = '#112646';
                btnCreateOrder.style.color = 'white';
                btnCreateOrder.classList.remove("bg-light");
                // fungsi untuk memanggil ajax, dijalankan ketika btn di klik
                btnCreateOrder.onclick = createOrder;
            } else {
                btnCreateOrder.style.background = '';
                btnCreateOrder.style.color = '';
                btnCreateOrder.onclick = null;
            }
        }

        function createOrder() {
            let data = {
                user_id: $("#user_id").val(), //ambil value dari input:hidden id="user_id"
                schedule_id: $("#schedule_id").val(),
                rows_of_seats: seats,
                quantity: seats.length,
                total_price: totalPrice,
                tax: 4000 * seats.length,
                hour: $("#hour").val(),
                _token: "{{ csrf_token() }}", // token csrf
            }
            // ajax (asynchronus javasripct and XML) : memproses daara ke atau dari BE
            $.ajax({
                url: "{{ route('tickets.store') }}", // route menuju proses data
                method: "POST", // http method
                data: data, // data yang akan dikirim ke BE
                success: function (response) {
                    // kalau berhasil mau ngapain
                    // console.log(response);
                    let ticketId = response.data.id;
                    // pindah halaman : window.location.href
                    window.location.href = `/tickets/${ticketId}/order`;
                },
                error: function (message) {
                    // kalau gagal mau ngapain
                    alert('Gagal membuat data tiket!');
                }
            })
        }
    </script>
@endpush
