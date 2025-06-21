@extends('layouts.app')

@section('title', 'Tambah Data')

@section('content')
    <div class="pagetitle">
        <h1>Tambah Data</h1>
        <nav class="mt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('visitor') }}">Daftar Pengunjung</a>
                </li>
                <li class="breadcrumb-item active">Tambah Data</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tambah Data</h5>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('visitor.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="date" id="date" name="date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="period" class="form-label">Periode</label>
                        <select id="period" name="period" class="form-select" required>
                            <option value="" disabled selected>Pilih periode</option>
                            <option value="weekly">Mingguan</option>
                            <option value="monthly">Bulanan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="count" class="form-label">Jumlah Pengunjung</label>
                        <input type="number" id="count" name="count" class="form-control" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <select id="notes" name="notes" class="form-select">
                            <option value="" disabled selected>Pilih catatan</option>
                            <option value="Berawan">Berawan</option>
                            <option value="Cuaca cerah">Cuaca cerah</option>
                            <option value="Hari biasa">Hari biasa</option>
                            <option value="Libur nasional">Libur nasional</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ url('visitor.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </section>
@endsection
