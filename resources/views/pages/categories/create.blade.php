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
                        <a href="{{ route('categories.index') }}">Management Categories</a>
                    </div>
                    <div class="breadcrumb-item active">
                        Create Category
                    </div>
                </div>
            </div>
            <div class="section-body">
                <h2 class="section-title">Categories</h2>
                <div class="card">
                    <form action="{{ route('categories.store') }}" method="POST" id="form-category"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="card-header">
                            <h4>Create Category</h4>
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

                            {{-- LABEL COLOR --}}
                            <div class="form-group">
                                <label>Label Color</label>

                                <select name="color" class="form-control @error('color') is-invalid @enderror">

                                    <option value="#6777ef">Ungu</option>
                                    <option value="#28a745">Green</option>
                                    <option value="#dc3545">Red</option>
                                    <option value="#ffc107">Yellow</option>
                                    <option value="#17a2b8">Cyan</option>
                                    <option value="#34395e">Dark</option>
                                    <option value="#6c757d">Gray</option>
                                    <option value="#e83e8c">Pink</option>
                                    <option value="#fd7e14">Orange</option>
                                    <option value="#20c997">Teal</option>
                                     <option value="#198754">
                                        Emerald
                                     </option>
                                     <option value="#795548">
                                        Brown
                                     </option>
                                     <option value="#ff5722">
                                        Deep Orange
                                     </option>
                                     <option value="#009688">
                                        Turquoise
                                     </option>
                                     <option value="#3f51b5">
                                        Royal Blue
                                     </option>
                                     <option value="#9c27b0">
                                        Violet
                                     </option>
                                     <option value="#f44336">
                                        Crimson
                                     </option>
                                     <option value="#607d8b">
                                        Blue Grey
                                     </option>

                                </select>

                                @error('color')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
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
                title: 'Simpan category?',
                text: "Pastikan data sudah benar",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6777ef',
                cancelButtonColor: '#fc544b',
                confirmButtonText: 'Ya, simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.isConfirmed) {
                    $('#form-category').submit();
                }

            });

        });
    </script>
@endpush
