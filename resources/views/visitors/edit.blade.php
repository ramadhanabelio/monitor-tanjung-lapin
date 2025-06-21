@extends('layouts.app')

@section('title', 'Edit Data')

@section('content')
    <div class="pagetitle">
        <h1>Edit Data</h1>
        <nav class="mt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('visitor') }}">Daftar Pengunjung</a>
                </li>
                <li class="breadcrumb-item active">Edit Data</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Edit Data</h5>

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

                <form action="{{ route('visitor.update', $visitor->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="date" id="date" name="date" class="form-control"
                            value="{{ $visitor->date }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="period" class="form-label">Periode</label>
                        <select id="period" name="period" class="form-select" required>
                            <option value="weekly" {{ $visitor->period == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                            <option value="monthly" {{ $visitor->period == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="count" class="form-label">Jumlah Pengunjung</label>
                        <input type="number" id="count" name="count" class="form-control" min="0"
                            value="{{ $visitor->count }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <select id="notes" name="notes" class="form-select">
                            <option value="" {{ $visitor->notes == null ? 'selected' : '' }}>Tidak Ada</option>
                            <option value="Berawan" {{ $visitor->notes == 'Berawan' ? 'selected' : '' }}>Berawan</option>
                            <option value="Cuaca cerah" {{ $visitor->notes == 'Cuaca cerah' ? 'selected' : '' }}>Cuaca
                                cerah
                            </option>
                            <option value="Hari biasa" {{ $visitor->notes == 'Hari biasa' ? 'selected' : '' }}>Hari biasa
                            </option>
                            <option value="Libur nasional" {{ $visitor->notes == 'Libur nasional' ? 'selected' : '' }}>
                                Libur nasional</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Perbarui</button>
                    <a href="{{ url('visitor.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </section>
@endsection
