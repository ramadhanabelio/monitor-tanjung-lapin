@extends('layouts.app')

@section('title', 'Daftar Pengunjung')

@section('content')
    <div class="pagetitle">
        <h1>Daftar Pengunjung</h1>
        <nav class="mt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Daftar Pengunjung</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">
                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Action</h6>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('visitor.create') }}"><i
                                                class="bi bi-plus"></i> Tambah Data</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Daftar Pengunjung</h5>
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif
                                <table class="table table-borderless datatable">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Tanggal</th>
                                            <th>Periode</th>
                                            <th>Jumlah Pengunjung</th>
                                            <th>Catatan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($visitors as $index => $visitor)
                                            <tr>
                                                <td>{{ $index + 1 }}.</td>
                                                <td>{{ \Carbon\Carbon::parse($visitor->date)->format('d M Y') }}</td>
                                                <td>
                                                    @php
                                                        $periodLabel = [
                                                            'weekly' => 'Mingguan',
                                                            'monthly' => 'Bulanan',
                                                        ];
                                                    @endphp
                                                    {{ $periodLabel[$visitor->period] ?? $visitor->period }}
                                                </td>
                                                <td>{{ $visitor->count }}</td>
                                                <td>{{ $visitor->notes ?? '-' }}</td>
                                                <td>
                                                    <a href="{{ route('visitor.edit', $visitor->id) }}"
                                                        class="badge bg-warning text-dark">
                                                        <i class="bi bi-pencil-square"></i> Edit
                                                    </a>
                                                    <form action="{{ route('visitor.destroy', $visitor->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="badge bg-danger text-white border-0">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Belum ada data.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
