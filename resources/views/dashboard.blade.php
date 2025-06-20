<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Monitor Tanjung Lapin</title>
    <link href="{{ asset('img/logo.png') }}" rel="icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-light">
    <div class="container py-4">
        <h2 class="mb-4 text-center">Dashboard Kunjungan Wisata Pantai Tanjung Lapin</h2>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
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
                    <div class="card-header d-flex justify-content-between align-items-center">
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

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
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
                    <div class="card-header d-flex justify-content-between align-items-center">
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

        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Top 5 Bulan Kunjungan Tertinggi</h4>
                <ul class="list-group list-group-flush">
                    @foreach ($topMonths as $month)
                        <li class="list-group-item">
                            {{ \Carbon\Carbon::parse($month->visit_date)->format('F Y') }}: {{ $month->count }}
                            pengunjung
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <script>
        const weeklyData = {!! json_encode($weeklyChartData) !!};
        const weeklyLabels = {!! json_encode($weeklyLabels) !!};
        const monthlyData = {!! json_encode($monthlyChartData) !!};
        const monthlyLabels = {!! json_encode($monthlyLabels) !!};

        const weeklyChart = new Chart(document.getElementById('weeklyChart'), {
            type: 'line',
            data: {
                labels: weeklyLabels,
                datasets: [{
                    label: 'Pengunjung Mingguan',
                    data: weeklyData,
                    borderColor: 'blue',
                    fill: false
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
                    backgroundColor: 'green'
                }]
            }
        });

        const pieChart = new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: {
                labels: ['Mingguan', 'Bulanan'],
                datasets: [{
                    data: [{{ $pieData['weekly'] }}, {{ $pieData['monthly'] }}],
                    backgroundColor: ['#36A2EB', '#FF6384']
                }]
            }
        });

        const noteChart = new Chart(document.getElementById('noteChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($noteSummary->pluck('notes')) !!},
                datasets: [{
                    data: {!! json_encode($noteSummary->pluck('total')) !!},
                    backgroundColor: ['#f39c12', '#00a65a', '#dd4b39', '#3c8dbc']
                }]
            }
        });

        document.getElementById('weeklyFilter').addEventListener('change', function() {
            let filter = this.value;
            let filteredData = [],
                filteredLabels = [];
            const now = new Date();
            for (let i = 0; i < weeklyLabels.length; i++) {
                let date = new Date(weeklyLabels[i] + ' 2024');
                if (filter === '6m' && (now - date) / (1000 * 3600 * 24) <= 183) {
                    filteredData.push(weeklyData[i]);
                    filteredLabels.push(weeklyLabels[i]);
                } else if (filter === '1y' && (now - date) / (1000 * 3600 * 24) <= 365) {
                    filteredData.push(weeklyData[i]);
                    filteredLabels.push(weeklyLabels[i]);
                } else if (filter === 'all') {
                    filteredData = weeklyData;
                    filteredLabels = weeklyLabels;
                    break;
                }
            }
            weeklyChart.data.labels = filteredLabels;
            weeklyChart.data.datasets[0].data = filteredData;
            weeklyChart.update();
        });

        document.getElementById('monthlyFilter').addEventListener('change', function() {
            let filter = this.value;
            let filteredData = [],
                filteredLabels = [];
            const now = new Date();
            for (let i = 0; i < monthlyLabels.length; i++) {
                let date = new Date(monthlyLabels[i]);
                if (filter === '1y' && (now - date) / (1000 * 3600 * 24) <= 365) {
                    filteredData.push(monthlyData[i]);
                    filteredLabels.push(monthlyLabels[i]);
                } else if (filter === 'all') {
                    filteredData = monthlyData;
                    filteredLabels = monthlyLabels;
                    break;
                }
            }
            monthlyChart.data.labels = filteredLabels;
            monthlyChart.data.datasets[0].data = filteredData;
            monthlyChart.update();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
    </script>
</body>

</html>
