<head>
    @extends('layouts.master')
    @section('judul', 'Dashboard')
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="assets/img/avatar/logo.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('template/plugins/bootstrap/css/bootstrap.min.css') }}">
    @stack('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="{{ asset('template/plugins/chart.js/Chart.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('template/dist/css/adminlte.min.css') }}">
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('template/dist/js/adminlte.js') }}"></script>
    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctxPie = document.getElementById('myPieChart').getContext('2d');
            var myPieChart = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: ['Pending Properties', 'Sold Properties', 'Available Properties'],
                    datasets: [{
                        data: [{{ $jumlah_properti_pending }}, {{ $jumlah_properti_terjual }}, {{ $jumlah_properti_tersedia }}],
                        backgroundColor: ['#6c757d', '#d9534f', '#5cb85c'],
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Properties'
                    },
                    tooltips: {
                        callbacks: {
                            label: function (tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var total = dataset.data.reduce(function (previousValue, currentValue, currentIndex, array) {
                                    return previousValue + currentValue;
                                });
                                var currentValue = dataset.data[tooltipItem.index];
                                var percentage = Math.round(((currentValue / total) * 100) * 10) / 10;
                                return `${data.labels[tooltipItem.index]}: ${currentValue} (${percentage}%)`;
                            }
                        }
                    }
                }
            });

            var ctxBar = document.getElementById('myBarChart').getContext('2d');
            var myBarChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: ['Accepted', 'Pending', 'Done', 'Rejected'],
                    datasets: [{
                        label: 'Schedules',
                        data: [{{ $jumlah_Schedule_Accept }}, {{ $jumlah_Schedule_Pending }}, {{ $jumlah_Schedule_Done }}, {{ $jumlah_Schedule_Reject }}],
                        backgroundColor: ['#5cb85c', '#f0ad4e', '#0275d8', '#d9534f']
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Schedules'
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</head>

<body>
    @section('content')
    <main class="main users chart-page" id="skip-target">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-3 col-md-6 col-12">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $jumlah_properti_terjual }}</h3>
                        <p>Total Sold Properties</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $jumlah_properti_tersedia }}</h3>
                        <p>Total Available Properties</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{ $jumlah_properti_pending }}</h3>
                        <p>Total Pending Properties</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $property }}</h3>
                        <p>Total Properties</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card  mt-3">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-th mr-1"></i>
                            Total Property : {{ $property }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <canvas id="myPieChart" style="width: 100%; height: 500px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card  mt-3">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-th mr-1"></i>
                            Schedule Overview
                        </h3>
                    </div>
                    <div class="card-body">
                        <canvas id="myBarChart" style="width: 100%; height: 537px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @endsection
</body>

@push('scripts')
    <aside class="control-sidebar control-sidebar-dark">
    </aside>
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <script src="{{ asset('template/plugins/sparklines/sparkline.js') }}"></script>
    <script src="{{ asset('template/dist/js/demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush