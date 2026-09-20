@extends('layouts.app')

@section('title', 'Riwayat Transaksi - POS Resto')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Riwayat Transaksi / Orders</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item active">Riwayat Transaksi</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Daftar Transaksi Resto</h2>
                <p class="section-lead">
                    Pantau seluruh pesanan kasir, metode pembayaran, dan rincian transaksi kafe/resto.
                </p>

                <div class="card">
                    <div class="card-header">
                        <h4>Filter Transaksi</h4>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('orders.index') }}" class="row">
                            <div class="form-group col-md-3">
                                <label>Cari (No. TRX / Nama / Meja)</label>
                                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Cari...">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Dari Tanggal</label>
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Metode Pembayaran</label>
                                <select name="payment_method" class="form-control">
                                    <option value="">Semua</option>
                                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                    <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                    <option value="debit" {{ request('payment_method') == 'debit' ? 'selected' : '' }}>Debit</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-filter"></i> Filter</button>
                                <a href="{{ route('orders.index') }}" class="btn btn-secondary"><i class="fas fa-sync"></i> Reset</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>No. Transaksi</th>
                                        <th>Kasir</th>
                                        <th>Pelanggan / Meja</th>
                                        <th>Total Item</th>
                                        <th>Total Tagihan</th>
                                        <th>Metode</th>
                                        <th>Waktu</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $order)
                                        <tr>
                                            <td><span class="font-weight-bold text-primary">{{ $order->transaction_number }}</span></td>
                                            <td>{{ $order->user ? $order->user->name : '-' }}</td>
                                            <td>
                                                {{ $order->customer_name ?? 'Umum' }}
                                                @if($order->table_number)
                                                    <span class="badge badge-light">Meja {{ $order->table_number }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $order->total_item }} item</td>
                                            <td><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                                            <td>
                                                <span class="badge badge-info text-uppercase">{{ $order->payment_method }}</span>
                                            </td>
                                            <td>{{ $order->created_at->translatedFormat('d/m/Y H:i') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-receipt"></i> Detail / Struk
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                Belum ada data transaksi yang sesuai filter.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        {{ $orders->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
