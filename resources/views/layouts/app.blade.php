<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title') - Monitor Tanjung Lapin</title>
    <meta content="" name="description" />
    <meta content="" name="keywords" />
    <!-- Favicons -->
    <link href="{{ asset('img/favicon.png') }}" rel="icon" />
    <link href="{{ asset('img/apple-touch-icon.png') }}" rel="apple-touch-icon" />
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect" />
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet" />
    <!-- Vendor CSS Files -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/quill/quill.snow.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/quill/quill.bubble.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/remixicon/remixicon.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/simple-datatables/style.css') }}" rel="stylesheet" />
    <!-- Template Main CSS File -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <a href="" class="logo d-flex align-items-center">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Monitor Tanjung Lapin" class="me-3" />
                <span class="d-none d-lg-block">Monitor Tanjung Lapin</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>
        <!-- End Logo -->
    </header>
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">

            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>
    </aside>
    <!-- End Sidebar -->

    <main id="main" class="main">
        @yield('content')
    </main>

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>Monitor Tanjung Lapin</span></strong>. All Rights
            Reserved
        </div>
        <div class="credits">
            Designed by
            <a href="#" target="_blank">Kelompok 5</a>
        </div>
    </footer>
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('vendor/php-email-form/validate.js') }}"></script>
    <!-- Template Main JS File -->
    <script src="{{ asset('js/main.js') }}"></script>
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
</body>

</html>
