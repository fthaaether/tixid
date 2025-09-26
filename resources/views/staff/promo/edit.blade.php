@extends('templates.app')

@section('content')
    <div class="w-75 d-block mx-auto my-5">
        <form action="{{ route('staff.promos.update', ['id' => $promo['id']]) }}" method="POST">
            {{-- mengambil file secara utuh bukan hanya nama filenya saja --}}
            @csrf
            @method('PUT')
            <div class="row mb-3">
                <div>
                    <label for="promo_code" class="form-label"> Kode Promo </label>
                    <input type="text" name="promo_code" id="promo_code"
                        class="form-control @error('promo_code')
                    is-invalid @enderror"
                        value="{{ $promo['promo_code'] }}">
                    @error('promo_code')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label for="type" class="form-label"> Tipe Promo </label>
                    <select type="enum" name="type" id="type"
                        class="form-control @error('type')
                    is-invalid @enderror">
                        <option value="">Pilih</option>
                        <option value="percent" {{ $promo['type'] == 'percent' ? 'selected' : '' }}>%</option>
                        <option value="rupiah" {{ $promo['type'] == 'rupiah' ? 'selected' : '' }}>Rupiah</option>
                    </select>
                    @error('type')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label for="discount" class="form-label"> Jumlah Potongan </label>
                    <input type="number" name="discount" id="discount"
                        class="form-control @error('discount')
                    is-invalid @enderror"
                        value="{{ $promo['discount'] }}">
                    @error('discount')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

            </div>
            <button type="submit" class="btn btn-primary">Edit</button>
        </form>
    </div>
@endsection
