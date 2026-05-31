@extends('layouts.app')

@section('title', 'Management User')

@push('style')
    <style>
        .rounded-circle {
            transition: all 0.3s ease;
            object-fit: cover;
            border: 2px solid #f1f1f1;
        }

        .rounded-circle:hover {
            transform: scale(1.12);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .font-weight-bold:hover {
            color: #6777ef;
            transition: .2s;
        }
    </style>
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Categories</h1>
                <div class="section-header-button">
                    <a href="{{ route('categories.create') }}" class="btn btn-primary">Add New</a>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('home') }}">Dashboard</a>
                    </div>
                    <div class="breadcrumb-item active">
                        Management Categories
                    </div>
                </div>
            </div>
            <div class="section-body">
                <h2 class="section-title">Categories</h2>
                <p class="section-lead">
                    You can manage all Categories, such as editing, deleting and more.
                </p>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Category Management</h4>
                            </div>
                            <div class="card-body">
                                <div class="float-left">
                                    <div class="float-left">
                                        <span class="badge badge-primary p-2">
                                            Total Categories: {{ $categories->total() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="float-right">
                                    <form method="GET" action="{{ route('categories.index') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search" name="search">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="clearfix mb-3"></div>

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Description</th>
                                                <th>Label</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @foreach ($categories as $category)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="font-weight-bold">
                                                                {{ $category->name }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="font-weight-regular">
                                                                {{ $category->description }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-pill px-3 py-2"
                                                            style="background-color: {{ $category->color }};
                                                            color:white;
                                                            font-size:13px;
                                                            ">
                                                            {{ $category->name }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-left">
                                                            <a href='{{ route('categories.edit', $category->id) }}'
                                                                class="btn btn-sm btn-info btn-icon">
                                                                <i class="fas fa-edit"></i>
                                                                Edit
                                                            </a>
                                                            <form action="{{ route('categories.destroy', $category->id) }}"
                                                                method="POST" class="ml-2 delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger btn-icon btn-delete">
                                                                    <i class="fas fa-times"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $categories->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script>
        $(document).ready(function() {

            $('.btn-delete').on('click', function(e) {
                e.preventDefault();

                let form = $(this).closest('form');

                Swal.fire({
                    title: 'Yakin hapus user?',
                    text: "Data tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {

                    if (result.isConfirmed) {
                        form.submit();
                    }

                });

            });

        });
    </script>
@endpush
