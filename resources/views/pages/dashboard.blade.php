@extends('layout.app')
@section('title', 'Dashboard')

@section('content')
    <div class="main-content">
        <div class="row">
            <div class="col-xl-6 col-lg-12">
                <div class="card">
                    <div class="card-statistic-4">
                        <div class="align-items-center justify-content-center">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                        <h5 class="font-22">Jumlah User</h5>
                                        <h1 class="mb-3 font-35 ">{{ $userCount }}</h1>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                    <div class="banner-img">
                                        <img src="admin/assets/img/banner/1.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-12">
                <div class="card">
                    <div class="card-statistic-4">
                        <div class="align-items-center justify-content-between">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                        <h5 class="font-22"> Jumlah Product</h5>
                                        <h2 class="mb-3 font-35">{{ $productCount }}</h2>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                    <div class="banner-img">
                                        <img src="admin/assets/img/banner/2.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm mb-4" style="max-width: 400px; margin: auto;">
            <div class="card-body p-3">
                <div class="text-center mb-3">
                    <h4 class="card-title">Filter Period</h4>
                </div>
                <form id="periodForm" action="{{ route('dashboard') }}" method="GET"
                    class="form-inline justify-content-center">
                    <div class="form-group mr-2 mb-2">
                        <select name="period" class="form-control" onchange="this.form.submit()">
                            <option value="all" {{ $period == 'all' ? 'selected' : '' }}>All Time</option>
                            <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Yearly</option>
                            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Monthly</option>
                        </select>
                    </div>
                    @if ($period == 'year' || $period == 'month')
                        <div class="form-group mr-2 mb-2">
                            <select name="year" class="form-control" onchange="this.form.submit()">
                                @foreach ($years as $y)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if ($period == 'month')
                        <div class="form-group mb-2">
                            <select name="month" class="form-control" onchange="this.form.submit()">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    @endif
                </form>
            </div>
        </div>
        <div class="row">
            <!-- Left Column -->
            <div class="col-xl-6 col-lg-12">
                <!-- Net Income Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Net Income</h4>
                    </div>
                    <div class="card-body">
                        <h2 class="mb-0">Rp {{ number_format($netIncome, 0, ',', '.') }}</h2>
                    </div>
                </div>

                <!-- Income Chart Card -->
                <div class="card">
                    <div class="card-header">
                        <h4>Income Chart</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="incomeChart" style="height: 400px; width: 100%;"></canvas>
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                            const ctx = document.getElementById('incomeChart').getContext('2d');

                            // Prepare data based on the selected period
                            let labels = [];
                            let data = [];

                            const period = '{{ $period }}';
                            const monthlyIncome = @json($monthlyIncome);
                            const yearlyIncome = @json($yearlyIncome);
                            const dailyIncome = @json($dailyIncome);

                            if (period === 'all') {
                                labels = Object.keys(yearlyIncome);
                                data = Object.values(yearlyIncome);
                            } else if (period === 'year') {
                                const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                                    'July', 'August', 'September', 'October', 'November', 'December'
                                ];
                                labels = monthNames;
                                data = monthNames.map((_, index) => monthlyIncome[index + 1] || 0);
                            } else if (period === 'month') {
                                const daysInMonth = new Date({{ $year }}, {{ $month }}, 0).getDate();
                                labels = Array.from({
                                    length: daysInMonth
                                }, (_, i) => i + 1);
                                data = labels.map(day => dailyIncome[day] || 0);
                            }

                            const incomeChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Income (Rp)',
                                        data: data,
                                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            title: {
                                                display: true,
                                                text: 'Income (Rp)'
                                            }
                                        },
                                        x: {
                                            title: {
                                                display: true,
                                                text: period === 'month' ? 'Day of Month' : 'Period'
                                            }
                                        }
                                    }
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-xl-6 col-lg-12">
                <!-- Top Exited Products Card -->
                <div class="card">
                    <div class="card-header">
                        <h4>Top Exited Products</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Total Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topExitedProducts as $index => $product)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $product->product_name ?? 'Unknown Product' }}</td>
                                            <td>{{ number_format($product->total_quantity) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No data available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Fungsi untuk memformat angka menjadi format rupiah
            function formatRupiah(angka) {
                var format = 'Rp ' + Number(angka).toLocaleString('id-ID');
                return format;
            }

            // Ambil elemen dengan ID 'netIncome'
            var netIncomeElement = document.getElementById('netIncome');

            // Ambil nilai dari elemen
            var netIncomeValue = netIncomeElement.innerText;

            // Format nilai dan tampilkan kembali di elemen
            netIncomeElement.innerText = formatRupiah(netIncomeValue);
        </script>
    @endsection
