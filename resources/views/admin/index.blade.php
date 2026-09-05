@extends('admin.layouts.master')

@section('head-tag')
    <title>Dashboard</title>
@endsection

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body"> Today’s Revenue <br>
                        <h5>
                            ${{ rtrim(rtrim(number_format($todayRevenue, 2), '0'), '.') }}
                        </h5>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">

                        <a class="small text-white stretched-link"
                            href="{{ route('admin.market.payment.filter', [
                                'sort' => '1',
                            ]) }}">View
                            Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">

                    <div class="card-body">Low availability variants <br>
                        <h5>
                            {{ $lowVariantsAvailable }}
                        </h5>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{route('admin.market.warehouse.index')}}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">Today’s Confirmed Orders <br>
                        <h5>
                            {{ $confirmedOrders }}
                        </h5>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{route('admin.market.order.index')}}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body">Open Support Tickets <br>
                        <h5>
                            {{ $openTickets }}
                        </h5>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link"
                            href="{{ route('admin.ticket.filter', [
                                'sort' => '1',
                            ]) }}">View
                            Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-area me-1"></i>
                        Last 30 Days Sales Trend
                    </div>
                    <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-1"></i>
                        Monthly Revenue (Last 6 Months)
                    </div>
                    <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {


            const areaLabels = {{ Js::from($chartLabels) }};
            const areaData = {{ Js::from($chartValues) }};

            const ctx = document.getElementById("myAreaChart");
            if (!ctx) return;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: areaLabels,
                    datasets: [{
                        label: "فروش ($)",
                        lineTension: 0.3,
                        backgroundColor: "rgba(2, 117, 216, 0.15)",
                        borderColor: "rgba(2, 117, 216, 1)", 
                        pointRadius: 4,
                        pointBackgroundColor: "rgba(2, 117, 216, 1)",
                        pointBorderColor: "rgba(255, 255, 255, 0.8)",
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: "rgba(2, 117, 216, 1)",
                        pointHitRadius: 20,
                        pointBorderWidth: 2,
                        data: areaData,
                        fill: true,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                maxTicksLimit: 7
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                min: 0,
                                maxTicksLimit: 5,
                                callback: function(value) {
                                    return '$' + Number(value)
                                        .toLocaleString(); 
                                }
                            },
                            gridLines: {
                                color: "rgba(0, 0, 0, .075)",
                            }
                        }],
                    },
                    legend: {
                        display: false
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, chart) {
                                return 'فروش: $' + Number(tooltipItem.yLabel).toLocaleString();
                            }
                        }
                    }
                }
            });
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {


            const barLabels = {{ Js::from($barLabels) }};
            const barData = {{ Js::from($barValues) }};

            const ctx = document.getElementById("myBarChart");
            if (!ctx) return;

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: barLabels,
                    datasets: [{
                        label: "Revenue",
                        backgroundColor: "rgba(2, 117, 216, 1)", 
                        hoverBackgroundColor: "rgba(2, 117, 216, 0.85)",
                        borderColor: "rgba(2, 117, 216, 1)",
                        borderWidth: 0,
                        maxBarThickness: 50,
                        data: barData,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                maxTicksLimit: 6
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                min: 0,
                                maxTicksLimit: 5,
                                callback: function(value) {
                                    return '$' + Number(value).toLocaleString();
                                }
                            },
                            gridLines: {
                                color: "rgba(0, 0, 0, .075)"
                            }
                        }],
                    },
                    legend: {
                        display: false
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return 'Revenue: $' + Number(tooltipItem.yLabel).toLocaleString();
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
