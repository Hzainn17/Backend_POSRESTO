@extends('layouts.app')

@section('title', 'Dashboard')

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
    <link rel="stylesheet"
        href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
                <span style="margin-left: 15px; font-size: 14px; color: #28a745;">
                    CI/CD Deployment Test
                </span>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>
@endpush
