@extends('templates.app')

@section('content')
    <div class="w-75 d-block mx-auto my-5">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-6">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $product->name) }}">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-6">
                    <label for="price" class="form-label">Harga</label>
                    <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror"
                        value="{{ old('price', $product->price) }}">
                    @error('price')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label for="category_id" class="form-label">Kategori</label>
                    <select name="category_id" id="category_id"
                        class="form-select @error('category_id') is-invalid @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-6">
                    <label for="discount_id" class="form-label">Diskon (Opsional)</label>
                    <select name="discount_id" id="discount_id" class="form-select">
                        <option value="">-- Pilih Diskon --</option>
                        @foreach($discounts as $discount)
                            <option value="{{ $discount->id }}" {{ old('discount_id', $product->discount_id) == $discount->id ? 'selected' : '' }}>
                                {{ $discount->discount_name }} ({{ $discount->value }}%)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea name="description" id="description" rows="5"
                    class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar Produk</label>
                <img src="{{ asset('storage/' . $product->image) }}" style="height: 150px; object-fit: cover;"
                    class="d-block mx-auto mb-3" alt="">
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                @error('image')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update Produk</button>
        </form>
    </div>
@endsection