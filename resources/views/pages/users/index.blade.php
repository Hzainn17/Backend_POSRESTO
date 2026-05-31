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
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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
                <h1>Users</h1>
                <div class="section-header-button">
                    <a href="{{ route('users.create') }}" class="btn btn-primary">Add New</a>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('home') }}">Dashboard</a>
                    </div>
                    <div class="breadcrumb-item active">
                    Management User
                    </div>
                </div>
            </div>
            <div class="section-body">
                <h2 class="section-title">Users</h2>
                <p class="section-lead">
                    You can manage all Users, such as editing, deleting and more.
                </p>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>User Management</h4>
                            </div>
                            <div class="card-body">
                                <div class="float-left">
                                    <form method="GET" action="{{ route('users.index') }}">
                                        <div class="input-group">
                                            <select name="role" class="form-control selectric" onchange="this.form.submit()">
                                                <option value="">All Role</option>
                                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>
                                                Admin
                                                </option>
                                                <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>
                                                Staff
                                                </option>
                                                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>
                                                User
                                                </option>
                                            </select>
                                        </div>
                                    </form>
                                </div>      
                                <div class="float-right">
                                    <form method="GET" action="{{ route('users.index') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search" name="name">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="clearfix mb-3"></div>

                                <div class="table-responsive">
                                    <table class="table-striped table">
                                        <tr>

                                            <th>Name</th>
                                            <th>Role</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img 
                                                            src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6777ef&color=fff"
                                                            alt="{{ $user->name }}"
                                                            class="rounded-circle mr-2"
                                                            width="40"
                                                            height="40">
                                                        <div>
                                                            <div class="font-weight-bold">
                                                                {{ $user->name }}
                                                            </div>
                                                            <small class="text-muted">
                                                            {{ $user->email }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td> 
                                                <td>
                                                    @if ($user->role == 'admin')
                                                        <div class="badge badge-primary">Admin</div>
                                                    @elseif ($user->role == 'staff')
                                                        <div class="badge badge-info">Staff</div>
                                                    @else
                                                        <div class="badge badge-secondary">User</div>
                                                    @endif
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</td>
                                                <td>
                                                    <div class="d-flex align-items-left">
                                                        <a href='{{ route('users.edit', $user->id) }}'
                                                            class="btn btn-sm btn-info btn-icon">
                                                            <i class="fas fa-edit"></i>
                                                            Edit
                                                        </a>
                                                        <form action="{{ route('users.destroy', $user->id) }}"
                                                            method="POST"
                                                            class="ml-2 delete-form">
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


                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $users->withQueryString()->links() }}
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
    $(document).ready(function () {

        $('.btn-delete').on('click', function (e) {
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