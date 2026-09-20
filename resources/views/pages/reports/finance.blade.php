@extends('layouts.app')

@section('title', 'Laporan Keuangan - POS Resto')

@push('style')
<style>
    .metric-title {
        font-size: 0.85rem;
        text-transform: uppercase;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 5px;
    }
    .metric-number {
        font-size: 1.4rem;
        font-weight: 700;
        color: #2c3e50;
    }
    @media print {
        .main-sidebar, .navbar, .section-header, .filter-card, .btn-print, .main-footer {
            display: none !important;
        }
        .main-content {
            padding-left: 0 !important;
            padding-right: 0 !important;
            padding-top: 0 !important;
            width: 100% !important;
        }
        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }
        .print-header {
            display: block !important;
            text-align: center;
            margin-bottom: 25px;
        }
    }
    .print-header {
        display: none;
    }
</style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            {{-- Header --}}
            <div class="section-header">
                <h1>Laporan Keuangan & Penjualan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item active">Laporan Keuangan</div>
                </div>
            </div>

            {{-- Print-only Title --}}
            <div class="print-header">
                <h2>POS RESTO</h2>
                <h4>LAPORAN KEUANGAN & PENJUALAN</h4>
                <p>Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</p>
                <hr>
            </div>

            {{-- Filter Form --}}
            <div class="card filter-card">
                <div class="card-header">
                    <h4>Filter Periode & Parameter</h4>
                    <div class="card-header-action">
                        <button onclick="window.print()" class="btn btn-warning btn-icon icon-left btn-print">
                            <i class="fas fa-print"></i> Cetak Laporan
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.finance') }}" class="row">
                        <div class="form-group col-md-3">
                            <label>Dari Tanggal</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Metode Pembayaran</label>
                            <select name="payment_method" class="form-control">
                                <option value="">Semua Metode</option>
                                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash (Tunai)</option>
                                <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="debit" {{ request('payment_method') == 'debit' ? 'selected' : '' }}>Debit</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Kasir</label>
                            <select name="user_id" class="form-control">
                                <option value="">Semua Kasir</option>
                                @foreach ($cashiers as $c)
                                    <option value="{{ $c->id }}" {{ request('user_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary mr-1 flex-fill"><i class="fas fa-filter"></i> Filter</button>
                            <a href="{{ route('reports.finance') }}" class="btn btn-secondary"><i class="fas fa-sync"></i></a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 4 Financial Metric Cards --}}
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Pendapatan Bersih</h4>
                            </div>
                            <div class="card-body metric-number text-primary">
                                Rp {{ number_format($totalNetRevenue, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Transaksi</h4>
                            </div>
                            <div class="card-body metric-number text-success">
                                {{ number_format($totalTransactions) }} Order
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Diskon Diberikan</h4>
                            </div>
                            <div class="card-body metric-number text-warning">
                                Rp {{ number_format($totalDiscount, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Rata-rata / Order (AOV)</h4>
                            </div>
                            <div class="card-body metric-number text-info">
                                Rp {{ number_format($averageOrderValue, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Breakdown by Payment Method & Summary Table --}}
            <div class="row">
                <div class="col-lg-6 col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Rincian Metode Pembayaran</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Metode</th>
                                            <th class="text-center">Jumlah Transaksi</th>
                                            <th class="text-right">Total Nominal</th>
                                            <th class="text-right">Porsi (%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($paymentMethods as $pm)
                                            @php
                                                $percentage = $totalNetRevenue > 0 ? ($pm->total_amount / $totalNetRevenue) * 100 : 0;
                                            @endphp
                                            <tr>
                                                <td><span class="badge badge-primary text-uppercase">{{ $pm->payment_method }}</span></td>
                                                <td class="text-center">{{ $pm->count }} trx</td>
                                                <td class="text-right font-weight-bold">Rp {{ number_format($pm->total_amount, 0, ',', '.') }}</td>
                                                <td class="text-right">{{ number_format($percentage, 1) }}%</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-3 text-muted">Belum ada data pembayaran.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Top 5 Menu Paling Laris (Periode Ini)</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Menu</th>
                                            <th class="text-center">Qty Terjual</th>
                                            <th class="text-right">Total Penjualan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($topProducts->take(5) as $tp)
                                            <tr>
                                                <td><strong>{{ $tp->name }}</strong></td>
                                                <td class="text-center"><span class="badge badge-success">{{ $tp->qty_sold }}</span></td>
                                                <td class="text-right font-weight-bold">Rp {{ number_format($tp->total_amount, 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-3 text-muted">Belum ada data penjualan menu.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Daily Summary Table --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Rincian Penjualan Harian</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th class="text-center">Total Order</th>
                                            <th class="text-center">Item Terjual</th>
                                            <th class="text-right">Diskon</th>
                                            <th class="text-right">Pajak</th>
                                            <th class="text-right">Total Pendapatan Bersih</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($dailySummary as $ds)
                                            <tr>
                                                <td><strong>{{ \Carbon\Carbon::parse($ds->date)->translatedFormat('d F Y') }}</strong></td>
                                                <td class="text-center">{{ $ds->total_orders }}</td>
                                                <td class="text-center">{{ $ds->total_items }}</td>
                                                <td class="text-right text-warning">Rp {{ number_format($ds->total_discount, 0, ',', '.') }}</td>
                                                <td class="text-right">Rp {{ number_format($ds->total_tax, 0, ',', '.') }}</td>
                                                <td class="text-right font-weight-bold text-primary">Rp {{ number_format($ds->total_revenue, 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">
                                                    Tidak ada transaksi pada rentang tanggal yang dipilih.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light font-weight-bold">
                                            <td>TOTAL KESELURUHAN</td>
                                            <td class="text-center">{{ $totalTransactions }}</td>
                                            <td class="text-center">{{ $totalItemsSold }}</td>
                                            <td class="text-right text-warning">Rp {{ number_format($totalDiscount, 0, ',', '.') }}</td>
                                            <td class="text-right">Rp {{ number_format($totalTax, 0, ',', '.') }}</td>
                                            <td class="text-right text-primary" style="font-size: 1.1rem;">
                                                Rp {{ number_format($totalNetRevenue, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection
