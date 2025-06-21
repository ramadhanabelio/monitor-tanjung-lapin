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

@push('scripts')
    <script>
        const softColors = ['#A3C9A8', '#FFB6B9', '#FFDAC1', '#E2F0CB', '#B5EAD7', '#C7CEEA', '#D5AAFF', '#A0CED9'];

        const weeklyData = {!! json_encode($weeklyChartData) !!};
        const weeklyLabels = {!! json_encode($weeklyLabels) !!};
        const monthlyData = {!! json_encode($monthlyChartData) !!};
        const monthlyLabels = {!! json_encode($monthlyLabels) !!};

        const noteLabels = {!! json_encode($noteSummary->pluck('notes')) !!};
        const noteData = {!! json_encode($noteSummary->pluck('total')) !!};

        const noteColorMap = {
            "Berawan": "#A3C9A8",
            "Cuaca cerah": "#FFB6B9",
            "Hari biasa": "#FFDAC1",
            "Libur nasional": "#E2F0CB"
        };

        const noteColors = noteLabels.map(label => noteColorMap[label] || '#CCCCCC');

        const weeklyChart = new Chart(document.getElementById('weeklyChart'), {
            type: 'line',
            data: {
                labels: weeklyLabels,
                datasets: [{
                    label: 'Pengunjung Mingguan',
                    data: weeklyData,
                    borderColor: softColors[0],
                    backgroundColor: softColors[0],
                    fill: true,
                    tension: 0.4
                }]
            }
        });

        const monthlyChart = new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Pengunjung Bulanan',
                    data: monthlyData,
                    backgroundColor: softColors[1]
                }]
            }
        });

        const pieChart = new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: {
                labels: ['Mingguan', 'Bulanan'],
                datasets: [{
                    data: [{{ $pieData['weekly'] }}, {{ $pieData['monthly'] }}],
                    backgroundColor: [softColors[2], softColors[3]]
                }]
            }
        });

        const noteChart = new Chart(document.getElementById('noteChart'), {
            type: 'pie',
            data: {
                labels: noteLabels,
                datasets: [{
                    data: noteData,
                    backgroundColor: noteColors
                }]
            }
        });

        document.getElementById('weeklyFilter').addEventListener('change', function() {
            const filter = this.value;
            const now = new Date();
            const filteredData = [];
            const filteredLabels = [];

            weeklyLabels.forEach((label, index) => {
                let labelDate = new Date(label + ' 2024');
                let diffDays = (now - labelDate) / (1000 * 3600 * 24);
                if ((filter === '6m' && diffDays <= 183) || (filter === '1y' && diffDays <= 365) ||
                    filter === 'all') {
                    filteredData.push(weeklyData[index]);
                    filteredLabels.push(label);
                }
            });

            weeklyChart.data.labels = filter === 'all' ? weeklyLabels : filteredLabels;
            weeklyChart.data.datasets[0].data = filter === 'all' ? weeklyData : filteredData;
            weeklyChart.update();
        });

        document.getElementById('monthlyFilter').addEventListener('change', function() {
            const filter = this.value;
            const now = new Date();
            const filteredData = [];
            const filteredLabels = [];

            monthlyLabels.forEach((label, index) => {
                let labelDate = new Date('01 ' + label);
                let diffDays = (now - labelDate) / (1000 * 3600 * 24);
                if ((filter === '1y' && diffDays <= 365) || filter === 'all') {
                    filteredData.push(monthlyData[index]);
                    filteredLabels.push(label);
                }
            });

            monthlyChart.data.labels = filter === 'all' ? monthlyLabels : filteredLabels;
            monthlyChart.data.datasets[0].data = filter === 'all' ? monthlyData : filteredData;
            monthlyChart.update();
        });

        document.getElementById('pieFilter').addEventListener('change', function() {
            const filter = this.value;
            const pieDataMap = {
                weekly: {{ $pieData['weekly'] }},
                monthly: {{ $pieData['monthly'] }},
            };

            let filteredData = [];
            let filteredLabels = [];

            if (filter === 'weekly') {
                filteredData = [pieDataMap.weekly];
                filteredLabels = ['Mingguan'];
            } else if (filter === 'monthly') {
                filteredData = [pieDataMap.monthly];
                filteredLabels = ['Bulanan'];
            } else {
                filteredData = [pieDataMap.weekly, pieDataMap.monthly];
                filteredLabels = ['Mingguan', 'Bulanan'];
            }

            pieChart.data.labels = filteredLabels;
            pieChart.data.datasets[0].data = filteredData;
            pieChart.update();
        });

        document.getElementById('noteFilter').addEventListener('change', function() {
            const filter = this.value;

            let filteredLabels = [];
            let filteredData = [];
            let filteredColors = [];

            noteLabels.forEach((label, index) => {
                if (filter === 'all' || label === filter) {
                    filteredLabels.push(label);
                    filteredData.push(noteData[index]);
                    filteredColors.push(noteColorMap[label] || '#CCCCCC');
                }
            });

            noteChart.data.labels = filteredLabels;
            noteChart.data.datasets[0].data = filteredData;
            noteChart.data.datasets[0].backgroundColor = filteredColors;
            noteChart.update();
        });
    </script>
@endpush
