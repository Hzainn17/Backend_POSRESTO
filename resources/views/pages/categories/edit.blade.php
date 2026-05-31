@extends('layouts.app')

@section('title', 'Edit Category')

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
                        <a href="{{ route('categories.index') }}">Management Category</a>
                    </div>
                    <div class="breadcrumb-item active">
                        Edit Category
                    </div>
                </div>
            </div>
            <div class="section-body">
                <h2 class="section-title">Categories</h2>
                <div class="card">
                    <form id="form-update-category" action="{{ route('categories.update', $category->id) }}" method="POST"
                        enctype="multipart/form-data">

                        @csrf
                        @method('PUT')

                        <div class="card-header">
                            <h4>Edit Category</h4>
                        </div>

                        <div class="card-body">

                            {{-- NAME --}}
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name', $category->name) }}">

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- DESCRIPTION --}}
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description', $category->description) }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- LABEL COLOR --}}
                            <div class="form-group">
                                <label>Label Color</label>

                                <select name="color" class="form-control @error('color') is-invalid @enderror">

                                    <option value="#6777ef"
                                        {{ old('color', $category->color) == '#6777ef' ? 'selected' : '' }}>
                                        Ungu
                                    </option>

                                    <option value="#28a745"
                                        {{ old('color', $category->color) == '#28a745' ? 'selected' : '' }}>
                                        Green
                                    </option>

                                    <option value="#dc3545"
                                        {{ old('color', $category->color) == '#dc3545' ? 'selected' : '' }}>
                                        Red
                                    </option>

                                    <option value="#ffc107"
                                        {{ old('color', $category->color) == '#ffc107' ? 'selected' : '' }}>
                                        Yellow
                                    </option>

                                    <option value="#17a2b8"
                                        {{ old('color', $category->color) == '#17a2b8' ? 'selected' : '' }}>
                                        Cyan
                                    </option>

                                    <option value="#34395e"
                                        {{ old('color', $category->color) == '#34395e' ? 'selected' : '' }}>
                                        Dark
                                    </option>

                                    <option value="#6c757d"
                                        {{ old('color', $category->color) == '#6c757d' ? 'selected' : '' }}>
                                        Gray
                                    </option>

                                    <option value="#007bff"
                                        {{ old('color', $category->color) == '#007bff' ? 'selected' : '' }}>
                                        Blue
                                    </option>
                                    <option value="#fd7e14"
                                        {{ old('color', $category->color) == '#fd7e14' ? 'selected' : '' }}>
                                        Orange
                                    </option>
                                    <option value="#e83e8c"
                                        {{ old('color', $category->color) == '#e83e8c' ? 'selected' : '' }}>
                                        Pink
                                    </option>
                                    <option value="#6f42c1"
                                        {{ old('color', $category->color) == '#6f42c1' ? 'selected' : '' }}>
                                        Purple
                                    </option>
                                    <option value="#20c997"
                                        {{ old('color', $category->color) == '#20c997' ? 'selected' : '' }}>
                                        Teal
                                    </option>
                                    <option value="#6610f2"
                                        {{ old('color', $category->color) == '#6610f2' ? 'selected' : '' }}>
                                        Indigo
                                    </option>
                                    <option value="#198754"
                                        {{ old('color', $category->color) == '#198754' ? 'selected' : '' }}>
                                        Emerald
                                    </option>
                                    <option value="#795548"
                                        {{ old('color', $category->color) == '#795548' ? 'selected' : '' }}>
                                        Brown
                                    </option>
                                    <option value="#ff5722"
                                        {{ old('color', $category->color) == '#ff5722' ? 'selected' : '' }}>
                                        Deep Orange
                                    </option>
                                    <option value="#009688"
                                        {{ old('color', $category->color) == '#009688' ? 'selected' : '' }}>
                                        Turquoise
                                    </option>
                                    <option value="#3f51b5"
                                        {{ old('color', $category->color) == '#3f51b5' ? 'selected' : '' }}>
                                        Royal Blue
                                    </option>
                                    <option value="#9c27b0"
                                        {{ old('color', $category->color) == '#9c27b0' ? 'selected' : '' }}>
                                        Violet
                                    </option>
                                    <option value="#f44336"
                                        {{ old('color', $category->color) == '#f44336' ? 'selected' : '' }}>
                                        Crimson
                                    </option>
                                    <option value="#607d8b"
                                        {{ old('color', $category->color) == '#607d8b' ? 'selected' : '' }}>
                                        Blue Grey
                                    </option>

                                </select>

                                @error('color')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- CURRENT IMAGE --}}
                            @if ($category->image)
                                <div class="form-group">
                                    <label>Current Image</label>
                                    <br>

                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                        class="img-thumbnail" width="200">
                                </div>
                            @endif
                        </div>

                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-primary" id="btn-submit">
                                Update
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

                        document.getElementById('form-update-category').submit();

                    }

                });

            });

        });
    </script>
@endpush
