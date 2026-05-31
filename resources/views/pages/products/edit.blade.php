@extends('layouts.app')

@section('title', 'Edit User')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Advanced Forms</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('home') }}">Dashboard</a>
                    </div>
                    <div class="breadcrumb-item">
                        <a href="{{ route('users.index') }}">Management Product</a>
                    </div>
                    <div class="breadcrumb-item active">
                        Edit Product
                    </div>
                </div>
            </div>
            <div class="section-body">
                <h2 class="section-title">Products</h2>
                <div class="card">
                    <form id="form-update-product" action="{{ route('products.update', $product->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card-header">
                            <h4>Edit Product</h4>
                        </div>

                        <div class="card-body">

                            {{-- NAME --}}
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name', $product->name) }}">

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- DESCRIPTION --}}
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description', $product->description) }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- IMAGE LAMA --}}
                            @if ($product->image)
                                <div class="form-group">
                                    <label>Current Image</label>

                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="img-thumbnail" width="200">
                                </div>
                            @endif

                            {{-- IMAGE --}}
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    name="image">
                                @error('image')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- CATEGORY --}}
                            <div class="form-group">
                                <label>Category</label>
                                <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            @if ($product->category_id == $category->id) selected @endif>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('category_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- PRICE --}}
                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                    name="price" value="{{ old('price', $product->price) }}" step="0.01">
                                @error('price')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- STOCK --}}
                            <div class="form-group">
                                <label>Stock</label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                    name="stock" value="{{ old('stock', $product->stock) }}">
                                @error('stock')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- IS_ACTIVE --}}
                            <div class="form-group">
                                <label class="d-block">Status</label>

                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                        class="custom-control-input @error('is_active') is-invalid @enderror" id="is_active"
                                        name="is_active" value="1"
                                        {{ old('is_active', $product->is_active) ? 'checked' : '' }}>

                                    <label class="custom-control-label" for="is_active">
                                        Active Product
                                    </label>

                                    @error('is_active')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-primary" id="btn-submit">Update</button>
                        </div>
                    </form>
                </div>

            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const button = document.getElementById('btn-submit');

            button.addEventListener('click', function() {

                Swal.fire({
                    title: 'Simpan Perubahan?',
                    text: 'Data user akan diperbarui.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6777ef',
                    cancelButtonColor: '#fc544b',
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal'
                }).then((result) => {

                    if (result.isConfirmed) {

                        document.getElementById('form-update-product').submit();

                    }

                });

            });

        });
    </script>
@endpush
