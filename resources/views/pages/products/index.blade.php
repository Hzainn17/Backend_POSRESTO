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
                <h1>Product</h1>
                <div class="section-header-button">
                    <a href="{{ route('products.create') }}" class="btn btn-primary">Add New</a>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('home') }}">Dashboard</a>
                    </div>
                    <div class="breadcrumb-item active">
                        Management Product
                    </div>
                </div>
            </div>
            <div class="section-body">
                <h2 class="section-title">Products</h2>
                <p class="section-lead">
                    You can manage all Products, such as editing, deleting and more.
                </p>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Product Management</h4>
                            </div>
                            <div class="card-body">
                                <div class="float-left">
                                    <form method="GET" action="{{ route('products.index') }}">

                                        <input type="hidden" name="name" value="{{ request('name') }}">

                                        <div class="input-group">
                                            <select name="category_id" class="form-control selectric"
                                                onchange="this.form.submit()">

                                                <option value="">All Categories</option>

                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>

                                    </form>
                                </div>
                                <div class="float-right">
                                    <form method="GET" action="{{ route('products.index') }}">
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
                                                <th>Product</th>
                                                <th>Category</th>
                                                <th>Price</th>
                                                <th>Stock</th>
                                                <th>Status</th>
                                                <th width="180">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @foreach ($products as $product)
                                                <tr>

                                                    <td>
                                                        <div class="d-flex align-items-center">

                                                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/60x60' }}"
                                                                alt="{{ $product->name }}" class="rounded shadow-sm mr-3"
                                                                width="40" height="40" style="object-fit:cover">

                                                            <div>
                                                                <div class="font-weight-bold">
                                                                    {{ $product->name }}
                                                                </div>

                                                                <small class="text-muted">
                                                                    {{ Str::limit($product->description, 40) }}
                                                                </small>
                                                            </div>

                                                        </div>
                                                    </td>

                                                    <td>
                                                        <span class="badge badge-pill"
                                                            style="background-color: {{ $product->category->color ?? '#6c757d' }}; color: #fff;">
                                                            {{ $product->category->name ?? '-' }}
                                                        </span>
                                                    </td>


                                                    <td>
                                                        <strong>
                                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                                        </strong>
                                                    </td>

                                                    <td>
                                                        @if ($product->stock <= 5)
                                                            <span class="badge badge-danger">
                                                                {{ $product->stock }}
                                                            </span>
                                                        @else
                                                            <span class="badge badge-success">
                                                                {{ $product->stock }}
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if ($product->is_active)
                                                            <span class="badge badge-success">
                                                                Active
                                                            </span>
                                                        @else
                                                            <span class="badge badge-secondary">
                                                                Inactive
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <div class="d-flex align-items-left">
                                                            <a href='{{ route('products.edit', $product->id) }}'
                                                                class="btn btn-sm btn-info btn-icon">
                                                                <i class="fas fa-edit"></i>
                                                                Edit
                                                            </a>
                                                            <form action="{{ route('products.destroy', $product->id) }}"
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
                                    {{ $products->withQueryString()->links() }}
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
