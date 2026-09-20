@php
    $role = auth()->user()->role;
@endphp

<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper" class="d-flex flex-column">

        {{-- Sidebar Brand --}}
        <div>
            <div class="sidebar-brand">
                <a href="{{ route('home') }}">POS RESTO</a>
            </div>

            <div class="sidebar-brand sidebar-brand-sm">
                <a href="{{ route('home') }}">PS</a>
            </div>

            {{-- Main Menu --}}
            <ul class="sidebar-menu">

                {{-- DASHBOARD --}}
                <li class="menu-header">MAIN</li>

                <li class="{{ Request::routeIs('home') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="fas fa-fire"></i>
                        <span>Dashboard</span>
                    </a>
                </li>


                {{-- MANAGEMENT --}}
                @if(in_array($role, ['admin', 'staff']))
                    <li class="menu-header">MANAGEMENT</li>
                @endif

                {{-- Products --}}
                @if(in_array($role, ['admin', 'staff']))
                    <li class="{{ Request::routeIs('products.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('products.index') }}">
                            <i class="fas fa-boxes"></i>
                            <span>Products</span>
                        </a>
                    </li>
                @endif

                {{-- Categories --}}
                @if(in_array($role, ['admin', 'staff']))
                    <li class="{{ Request::routeIs('categories.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('categories.index') }}">
                            <i class="fas fa-tags"></i>
                            <span>Categories</span>
                        </a>
                    </li>
                @endif

                {{-- Users - ADMIN ONLY --}}
                @if($role === 'admin')
                    <li class="{{ Request::routeIs('users.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('users.index') }}">
                            <i class="fas fa-users"></i>
                            <span>Users</span>
                        </a>
                    </li>
                @endif


                {{-- TRANSACTIONS - tersedia untuk admin dan staff --}}
                @if(in_array($role, ['admin', 'staff']))

                    <li class="menu-header">TRANSAKSI</li>

                    {{-- Riwayat Order --}}
                    <li class="{{ Request::routeIs('orders.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('orders.index') }}">
                            <i class="fas fa-receipt"></i>
                            <span>Riwayat Order</span>
                        </a>
                    </li>

                @endif

                {{-- LAPORAN - admin saja --}}
                @if($role === 'admin')

                    <li class="menu-header">LAPORAN</li>

                    {{-- Laporan Keuangan --}}
                    <li class="{{ Request::routeIs('reports.finance') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('reports.finance') }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Laporan Keuangan</span>
                        </a>
                    </li>

                @endif

            </ul>
        </div>

    </aside>
</div>


{{-- STYLE --}}
<style>
    #sidebar-wrapper {
        height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .sidebar-bottom {
        margin-top: auto;
        border-top: 1px solid #f1f1f1;
        padding-top: 10px;
    }

    .sidebar-menu li a {
        transition: all .2s ease;
    }

    .sidebar-menu li a:hover {
        transform: translateX(4px);
    }
</style>
