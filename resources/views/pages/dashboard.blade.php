@extends('layouts.app')

@section('title', 'Dashboard - POS Resto')

@push('style')
<style>
    .card-statistic-1 .card-icon {
        line-height: 80px;
    }
    .metric-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
    }
</style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard Pemilik & Kasir</h1>
            </div>

            {{-- 4 Metric Cards --}}
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Omzet Hari Ini</h4>
                            </div>
                            <div class="card-body metric-value">
                                Rp {{ number_format($todayRevenue, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Transaksi Hari Ini</h4>
                            </div>
                            <div class="card-body metric-value">
                                {{ $todayOrdersCount }} Order
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Omzet Bulan Ini</h4>
                            </div>
                            <div class="card-body metric-value">
                                Rp {{ number_format($monthRevenue, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Menu Aktif</h4>
                            </div>
                            <div class="card-body metric-value">
                                {{ $totalProducts }} Menu
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Chart & Best Sellers --}}
            <div class="row">
                <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Statistik Omzet (7 Hari Terakhir)</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" height="150"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Menu Terlaris (Best Seller)</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled list-unstyled-border">
                                @forelse ($bestSellers as $item)
                                    <li class="media">
                                        <div class="media-body">
                                            <div class="float-right text-primary font-weight-bold">
                                                {{ $item->total_sold }} terjual
                                            </div>
                                            <div class="media-title">{{ $item->name }}</div>
                                            <span class="text-small text-muted">
                                                Total: Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </li>
                                @empty
                                    <li class="text-center text-muted py-3">Belum ada data penjualan</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Transactions Table --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>5 Transaksi Terkini</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>No. Transaksi</th>
                                            <th>Kasir</th>
                                            <th>Pelanggan / Meja</th>
                                            <th>Total Tagihan</th>
                                            <th>Metode</th>
                                            <th>Status</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recentOrders as $order)
                                            <tr>
                                                <td><span class="font-weight-bold">{{ $order->transaction_number }}</span></td>
                                                <td>{{ $order->user ? $order->user->name : '-' }}</td>
                                                <td>
                                                    {{ $order->customer_name ?? 'Pelanggan Umum' }}
                                                    @if($order->table_number)
                                                        <span class="badge badge-light">Meja {{ $order->table_number }}</span>
                                                    @endif
                                                </td>
                                                <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                                <td>
                                                    <span class="badge badge-info text-uppercase">{{ $order->payment_method }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-success">{{ $order->status }}</span>
                                                </td>
                                                <td>{{ $order->created_at->translatedFormat('d M Y H:i') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">
                                                    Belum ada transaksi tersimpan.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var ctx = document.getElementById("revenueChart");
            if (ctx) {
                var chartData = @json($last7Days);
                var labels = chartData.map(function(item) { return item.date; });
                var data = chartData.map(function(item) { return item.revenue; });

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Omzet (Rp)',
                            data: data,
                            borderWidth: 3,
                            backgroundColor: 'rgba(103, 119, 239, 0.15)',
                            borderColor: '#6777ef',
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#6777ef',
                            pointRadius: 4,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            yAxes: [{
                                gridLines: {
                                    drawBorder: false,
                                    color: '#f2f2f2',
                                },
                                ticks: {
                                    beginAtZero: true,
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }],
                            xAxes: [{
                                gridLines: {
                                    display: false
                                }
                            }]
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    return 'Rp ' + Number(tooltipItem.yLabel).toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
