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


                {{-- TRANSACTIONS --}}
                @if(in_array($role, ['admin', 'staff']))

                    <li class="menu-header">TRANSACTIONS</li>

                    {{-- Orders --}}
                    <li class="{{ Request::routeIs('orders.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('orders.index') }}">
                            <i class="fas fa-cash-register"></i>
                            <span>Orders</span>
                        </a>
                    </li>

                    {{-- History --}}
                    <li class="{{ Request::routeIs('history.*') ? 'active' : '' }}">
                        <a class="nav-link" href="#">
                            <i class="fas fa-history"></i>
                            <span>History</span>
                        </a>
                    </li>

                    {{-- Expenses --}}
                    <li class="{{ Request::routeIs('expenses.*') ? 'active' : '' }}">
                        <a class="nav-link" href="#">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Expenses</span>
                        </a>
                    </li>

                @endif


                {{-- REPORTS - ADMIN ONLY --}}
                @if($role === 'admin')

                    <li class="menu-header">REPORTS</li>

                    {{-- Sales Report --}}
                    <li class="{{ Request::routeIs('sales-report.*') ? 'active' : '' }}">
                        <a class="nav-link" href="#">
                            <i class="fas fa-chart-line"></i>
                            <span>Sales Report</span>
                        </a>
                    </li>

                    {{-- Transactions Report --}}
                    <li class="{{ Request::routeIs('transactions-report.*') ? 'active' : '' }}">
                        <a class="nav-link" href="#">
                            <i class="fas fa-chart-bar"></i>
                            <span>Transactions Report</span>
                        </a>
                    </li>

                @endif

            </ul>
        </div>


        {{-- SYSTEM MENU --}}
        @if($role === 'admin')

            <div class="sidebar-bottom mt-auto mb-3">

                <ul class="sidebar-menu">

                    <li class="menu-header">SYSTEM</li>

                    {{-- Settings --}}
                    <li class="{{ Request::routeIs('settings.*') ? 'active' : '' }}">
                        <a class="nav-link" href="#">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>

                </ul>

            </div>

        @endif

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
