@extends('layouts.app')

@section('title', 'Advanced Forms')

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
                        <a href="{{ route('products.index') }}">Management Product</a>
                    </div>
                    <div class="breadcrumb-item active">
                        Create Product
                    </div>
                </div>
            </div>
            <div class="section-body">
                <h2 class="section-title">Products</h2>
                <div class="card">
                    <form action="{{ route('products.store') }}" method="POST" id="form-product"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="card-header">
                            <h4>Create Product</h4>
                        </div>

                        <div class="card-body">

                            {{-- NAME --}}
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}">

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- DESCRIPTION --}}
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- PRICE --}}
                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                    name="price" value="{{ old('price') }}" step="0.01">

                                @error('price')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

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
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>

                                @error('category_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- STOCK --}}
                            <div class="form-group">
                                <label>Stock</label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                    name="stock" value="{{ old('stock') }}">

                                @error('stock')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror   
                            </div>

                            {{-- IS ACTIVE --}}
                            <div class="form-group">
                                <label>Is Active</label>
                                <select name="is_active" class="form-control @error('is_active') is-invalid @enderror">
                                    <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-primary" id="btn-submit">
                                Submit
                            </button>
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
        $('#btn-submit').click(function(e) {

            e.preventDefault();

            Swal.fire({
                title: 'Simpan Product?',
                text: "Pastikan data sudah benar",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6777ef',
                cancelButtonColor: '#fc544b',
                confirmButtonText: 'Ya, simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.isConfirmed) {
                    $('#form-product').submit();
                }

            });

        });
    </script>
@endpush
