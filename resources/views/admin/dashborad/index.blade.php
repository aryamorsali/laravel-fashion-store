@extends('admin.layouts.master')

@section('head-tag')
    <title>Dashboard</title>
    <style>
        .chart-container {
            position: relative;
            height: 240px;
            width: 100%;
        }

        .chart-container canvas {
            width: 100% !important;
            height: 100% !important;
        }
        .notif-count {
            position: absolute;
            top: 3px;
            right: 2px;
            font-size: 0.6rem;
            font-weight: 700;
            color: #fff;
            background: #e74a3b;
            border-radius: 50%;
            width: 15px;
            height: 15px;
            line-height: 15px;
            text-align: center;
        }

        .notif-menu {
            width: 320px;
            padding: 0;
            font-size: 0.875rem;
            overflow: hidden;
        }
        .notif-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: #21252b;
            border-bottom: 1px solid #eef0f3;
        }

        .notif-head .mark-read {
            font-size: 0.75rem;
            font-weight: 400;
            color: #0d6efd;
            text-decoration: none;
        }

        .notif-head .mark-read:hover {
            text-decoration: underline;
        }

        .notif-item {
            display: block;
            padding: 0.7rem 1rem;
            text-decoration: none;
            border-bottom: 1px solid #f4f5f7;
        }

        .notif-item:hover {
            background: #f8f9fa;
        }

        .notif-item .notif-title {
            margin: 0;
            color: #21252b;
            font-weight: 400;
            line-height: 1.5;
        }


        /* نقطه خوانده‌نشده */
        .notif-item .dot {
            display: inline-block;
            width: 7px;
            height: 7px;
            margin-left: 6px;
            border-radius: 50%;
            background: #0d6efd;
            vertical-align: middle;
        }

        .notif-foot {
            background: #f8f9fa;
            text-align: center;
        }

        .notif-foot a {
            display: block;
            padding: 0.6rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #0d6efd;
            text-decoration: none;
        }

        .notif-foot a:hover {
            background: #eef2f7;
        }

        .notification-icon {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.8rem;
        }

        .notif-item {
            display: flex !important;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.65rem 1rem;
        }

        .notif-body {
            flex: 1;
            min-width: 0;
        }

        .notif-title {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            margin: 0;
            font-size: 0.85rem;
            color: #343a40;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notif-sub {
            margin: 0.1rem 0 0;
            font-size: 0.72rem;
            color: #adb5bd;
        }

        .notif-item .notification-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            color: #fff;
            flex-shrink: 0;
        }

        .notif-title .dot {
            width: 8px;
            height: 8px;
            background: #2f6dfa;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
            margin-left: auto;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Wellcome To Dashboard</li>
        </ol>
        <div class="row">
            @canany(['manage-payments', 'manage-orders'])
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
            @endcanany

            @canany(['view-inventory', 'view-warehouse'])
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4">

                        <div class="card-body">Low availability variants <br>
                            <h5>
                                {{ $lowVariantsAvailable }}
                            </h5>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('admin.market.warehouse.index') }}">View
                                Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            @endcanany

            @can('manage-orders')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">Today’s Confirmed Orders <br>
                            <h5>
                                {{ $confirmedOrders }}
                            </h5>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('admin.market.order.index') }}">View
                                Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            @endcan
            @can('manage-tickets')
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
            @endcan

        </div>
        @canany(['view-revenue-chart', 'view-sales-chart'])
            <div class="row">
                @can('view-sales-chart')
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-chart-area mr-2"></i>
                                    Last 30 Days Sales Trend
                                </h6>
                            </div>

                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="myAreaChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
                @can('view-revenue-chart')
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-chart-bar mr-2"></i>
                                    Monthly Revenue
                                </h6>
                            </div>

                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="myBarChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

            </div>
        @endcanany

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
                                return 'sold: $' + Number(tooltipItem.yLabel).toLocaleString();
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
