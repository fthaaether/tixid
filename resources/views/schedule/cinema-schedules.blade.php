@extends('templates.app')

@section('content')
    <div class="container my-5 card">
        <div class="card-body">
            <i class="fa-solid fa-location-dot me-3"></i>{{ $schedules[0]['cinema']['location'] }}
            <hr>
            @foreach ($schedules as $schedule)
            <div class="my-2">
                <div class="d-flex">
                    <div style="width: 150px; height: 200px;">
                        <img src="{{ asset('storage/' . $schedule['movie']['poster']) }}" alt="Poster Superman"
                            class="w-100 rounded">
                    </div>
                    <div class="ms-5 mt-4">
                        <h5 class="fw-bold">{{ $schedule['movie']['title'] }}</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><b class="text-secondary">Genre</b></td>
                                <td class="px-3">:</td>
                                <td>{{ $schedule['movie']['genre'] }}</td>
                            </tr>
                            <tr>
                                <td><b class="text-secondary">Durasi</b></td>
                                <td class="px-3">:</td>
                                <td>{{ $schedule['movie']['duration'] }}</td>
                            </tr>
                            <tr>
                                <td><b class="text-secondary">Sutradara</b></td>
                                <td class="px-3">:</td>
                                <td>{{ $schedule['movie']['director'] }}</td>
                            </tr>
                            <tr>
                                <td><b class="text-secondary">Rating usia</b></td>
                                <td class="px-3">:</td>
                                <td><span class="badge bg-danger">{{ $schedule['movie']['age_rating'] }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="w-100 my-3">
                    <div class="d-flex justify-content-end">
                        <div class="">
                            <b>Rp. {{ number_format($schedule['price'], 0, ',', '.') }}</b>
                        </div>
                    </div>
                    <div class="d-flex gap-3 ps-3 my-2 flex-wrap">
                        @foreach ($schedule['hours'] as $hours)
                            <div class="btn btn-outline-secondary">{{ $hours }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
            <hr>
            @endforeach

            {{-- <div class="mb-5 mt-4">
                <div class="mb-5">
                    @foreach ($movie['schedules'] as $schedule)
                    @endforeach

                </div> --}}

                <div class="w-100 p-2 bg-light text-center fixed-bottom">

                    <a href="#"><i class="pa-solid fa-tiket"></i>BELI TIKET</a>

                </div>

            </div>
        </div>
    @endsection
@push('script')
<script>
    let selectedScheduleId = null;
    let selectedHourIndex = null;
    let lastCliked = null;

    function selectedHour(scheduleId, HourIndex, el){
        selectedScheduleId = scheduleId;
        selectedHourIndex = HourIndex;

        if(lastCliked) {
            lastCliked.style.backgroundColor = "";
            lastCliked.style.color = "";
            lastCliked.style.borderColor = "";
        }

        el.style.backgroundColor = "#112646";
        el.style.color = "white";
        el.style.borderColor = "#112646";

        lastCliked = el;
    }
</script>
@endpush
