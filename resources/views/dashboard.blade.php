@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav class="mt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="">Kunjungan Wisata Pantai Tanjung Lapin</a>
                </li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center mb-2">
                                    <span>Pengunjung Mingguan</span>
                                    <select class="form-select form-select-sm w-auto" id="weeklyFilter">
                                        <option value="all">Semua</option>
                                        <option value="6m">6 Bulan Terakhir</option>
                                        <option value="1y">1 Tahun Terakhir</option>
                                    </select>
                                </div>
                                <div class="card-body">
                                    <canvas id="weeklyChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center mb-2">
                                    <span>Pengunjung Bulanan</span>
                                    <select class="form-select form-select-sm w-auto" id="monthlyFilter">
                                        <option value="all">Semua</option>
                                        <option value="1y">1 Tahun Terakhir</option>
                                    </select>
                                </div>
                                <div class="card-body">
                                    <canvas id="monthlyChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center mb-2">
                                    <span>Perbandingan Periode</span>
                                    <select class="form-select form-select-sm w-auto" id="pieFilter">
                                        <option value="all">Semua</option>
                                        <option value="weekly">Mingguan</option>
                                        <option value="monthly">Bulanan</option>
                                    </select>
                                </div>
                                <div class="card-body">
                                    <canvas id="pieChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center mb-2">
                                    <span>Catatan Kunjungan</span>
                                    <select class="form-select form-select-sm w-auto" id="noteFilter">
                                        <option value="all">Semua</option>
                                        @foreach ($noteSummary as $note)
                                            <option value="{{ $note->notes }}">{{ $note->notes }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="card-body">
                                    <canvas id="noteChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Top 5 Bulan Kunjungan Tertinggi</h4>
                                    <ul class="list-group list-group-flush">
                                        @foreach ($topMonths as $month)
                                            <li class="list-group-item">
                                                {{ \Carbon\Carbon::parse($month->visit_date)->format('F Y') }}:
                                                {{ $month->count }}
                                                pengunjung
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
