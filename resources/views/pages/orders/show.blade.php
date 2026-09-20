@extends('layouts.app')

@section('title', 'Detail Transaksi - POS Resto')

@push('style')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printable-invoice, #printable-invoice * {
            visibility: visible;
        }
        #printable-invoice {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
    }
</style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header no-print">
                <h1>Detail Transaksi & Struk</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('orders.index') }}">Transaksi</a></div>
                    <div class="breadcrumb-item active">{{ $order->transaction_number }}</div>
                </div>
            </div>

            <div class="section-body">
                <div class="invoice" id="printable-invoice">
                    <div class="invoice-print">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="invoice-title">
                                    <h2>POS RESTO</h2>
                                    <div class="invoice-number">{{ $order->transaction_number }}</div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <address>
                                            <strong>Informasi Pesanan:</strong><br>
                                            Pelanggan: {{ $order->customer_name ?? 'Pelanggan Umum' }}<br>
                                            Nomor Meja: {{ $order->table_number ? 'Meja ' . $order->table_number : '-' }}<br>
                                            Kasir: {{ $order->user ? $order->user->name : '-' }}
                                        </address>
                                    </div>
                                    <div class="col-md-6 text-md-right">
                                        <address>
                                            <strong>Tanggal & Waktu:</strong><br>
                                            {{ $order->created_at->translatedFormat('d F Y, H:i:s') }}<br>
                                            <strong>Metode Pembayaran:</strong><br>
                                            <span class="badge badge-info text-uppercase">{{ $order->payment_method }}</span>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="section-title">Rincian Menu Dipesan</div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-md">
                                        <thead>
                                            <tr>
                                                <th data-width="40">#</th>
                                                <th>Item Menu</th>
                                                <th class="text-center">Harga Satuan</th>
                                                <th class="text-center">Jumlah</th>
                                                <th class="text-right">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->orderItems as $index => $item)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <strong>{{ $item->product ? $item->product->name : 'Menu tidak tersedia' }}</strong>
                                                        @if($item->notes)
                                                            <br><small class="text-muted">Catatan: {{ $item->notes }}</small>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                                    <td class="text-center">{{ $item->quantity }}</td>
                                                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-lg-8">
                                        @if($order->notes)
                                            <div class="section-title">Catatan Pesanan</div>
                                            <p class="section-lead">{{ $order->notes }}</p>
                                        @endif
                                    </div>
                                    <div class="col-lg-4 text-right">
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Subtotal</div>
                                            <div class="invoice-detail-value">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</div>
                                        </div>
                                        @if($order->discount > 0)
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">Diskon</div>
                                                <div class="invoice-detail-value text-danger">- Rp {{ number_format($order->discount, 0, ',', '.') }}</div>
                                            </div>
                                        @endif
                                        @if($order->tax > 0)
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">Pajak (PB1 / Resto)</div>
                                                <div class="invoice-detail-value">Rp {{ number_format($order->tax, 0, ',', '.') }}</div>
                                            </div>
                                        @endif
                                        @if($order->service_charge > 0)
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">Biaya Layanan</div>
                                                <div class="invoice-detail-value">Rp {{ number_format($order->service_charge, 0, ',', '.') }}</div>
                                            </div>
                                        @endif
                                        <hr class="mt-2 mb-2">
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name font-weight-bold">Total Tagihan</div>
                                            <div class="invoice-detail-value invoice-detail-value-lg text-primary font-weight-bold">
                                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Nominal Dibayar</div>
                                            <div class="invoice-detail-value">Rp {{ number_format($order->payment_amount, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Kembalian</div>
                                            <div class="invoice-detail-value font-weight-bold">Rp {{ number_format($order->change_amount, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-md-right no-print">
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary mr-2"><i class="fas fa-arrow-left"></i> Kembali</a>
                        <button class="btn btn-warning btn-icon icon-left" onclick="window.print()"><i class="fas fa-print"></i> Cetak Struk</button>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
