@extends('layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Dashboard</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active text-uppercase" id="company-tab" data-bs-toggle="tab"
                               href="#company"
                               role="tab" aria-controls="company" aria-selected="true">Company</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-uppercase" id="departments-tab" data-bs-toggle="tab"
                               href="#departments"
                               role="tab" aria-controls="departments" aria-selected="false">Department</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-uppercase" id="users-tab" data-bs-toggle="tab" href="#users"
                               role="tab" aria-controls="users" aria-selected="false">Users</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="company" role="tabpanel"
                             aria-labelledby="company-tab">
                            <div class="row">
                                <div class="col-md-6 col-lg-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0">Progress by Department</h5>
                                                <div class="dropdown">
                                                    <a
                                                        class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none"
                                                        href="#"
                                                        data-bs-toggle="dropdown"
                                                        aria-haspopup="true"
                                                        aria-expanded="false"
                                                    >
                                                        <i class="ti ti-dots-vertical f-18"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Today</a>
                                                        <a class="dropdown-item" href="#">Weekly</a>
                                                        <a class="dropdown-item" href="#">Monthly</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="total-income-graph"></div>
                                            <div class="row g-3 mt-3">
                                                @foreach($departments->take(4) as $i => $department)
                                                    <div class="col-sm-6">
                                                        <div class="bg-body p-3 rounded">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <div class="flex-shrink-0">
                                                              <span class="p-1 d-block rounded-circle"
                                                                    style="background-color: {{ \App\Models\Department::COLOR_LIST[$i] }}">
                                                                <span class="visually-hidden">New alerts</span>
                                                              </span>
                                                                </div>
                                                                <div class="flex-grow-1 ms-2">
                                                                    <p class="mb-0">{{ $department['name'] }}</p>
                                                                </div>
                                                            </div>
                                                            <h6 class="mb-0">{{ $department['total'] }}
                                                                <small class="text-muted"><i
                                                                        class="ti ti-chevrons-up"></i>
                                                                    {{ $department['percentage'] }}%</small>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <h5 class="mb-0">Progress by User</h5>
                                                <div class="dropdown">
                                                    <a
                                                        class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none"
                                                        href="#"
                                                        data-bs-toggle="dropdown"
                                                        aria-haspopup="true"
                                                        aria-expanded="false"
                                                    >
                                                        <i class="ti ti-dots-vertical f-18"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Today</a>
                                                        <a class="dropdown-item" href="#">Weekly</a>
                                                        <a class="dropdown-item" href="#">Monthly</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <p class="mb-1">Total Training Task User</p>
                                                <h3 class="mt-5 mb-5">{{ $statisticUser['total'] }}</h3>
                                                <hr>
                                            </div>
                                            <div class="d-flex align-items-center mt-3">
                                                <div class="flex-shrink-0">
                                                    <span class="badge border bg-success">Completed</span>
                                                </div>
                                                <div class="flex-grow-1 ms-3" style="text-align: right">
                                                    <h6 class="mb-0">{{ $statisticUser['complete'] }}</h6>
                                                </div>
                                                <div class="dropdown">
                                                    <a
                                                        class="avtar avtar-s btn-link-secondary arrow-none"
                                                        href="#"
                                                        data-bs-toggle="dropdown"
                                                        aria-haspopup="true"
                                                        aria-expanded="false"
                                                    >
                                                        <i class="ti ti-chevron-right f-18"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center mt-3">
                                                <div class="flex-shrink-0">
                                                    <span class="badge border bg-warning">In Progress</span>
                                                </div>
                                                <div class="flex-grow-1 ms-3" style="text-align: right">
                                                    <h6 class="mb-0">{{ $statisticUser['on_progress'] }}</h6>
                                                </div>
                                                <div class="dropdown">
                                                    <a
                                                        class="avtar avtar-s btn-link-secondary arrow-none"
                                                        href="#"
                                                        data-bs-toggle="dropdown"
                                                        aria-haspopup="true"
                                                        aria-expanded="false"
                                                    >
                                                        <i class="ti ti-chevron-right f-18"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center mt-3">
                                                <div class="flex-shrink-0">
                                                    <span class="badge border bg-secondary">Not Registered</span>
                                                </div>
                                                <div class="flex-grow-1 ms-3" style="text-align: right">
                                                    <h6 class="mb-0">{{ $statisticUser['not_registered'] }}</h6>
                                                </div>
                                                <div class="dropdown">
                                                    <a
                                                        class="avtar avtar-s btn-link-secondary arrow-none"
                                                        href="#"
                                                        data-bs-toggle="dropdown"
                                                        aria-haspopup="true"
                                                        aria-expanded="false"
                                                    >
                                                        <i class="ti ti-chevron-right f-18"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center mt-3">
                                                <div class="flex-shrink-0">
                                                    <span class="badge border bg-danger">Failed</span>
                                                </div>
                                                <div class="flex-grow-1 ms-3" style="text-align: right">
                                                    <h6 class="mb-0">{{ $statisticUser['failed'] }}</h6>
                                                </div>
                                                <div class="dropdown">
                                                    <a
                                                        class="avtar avtar-s btn-link-secondary arrow-none"
                                                        href="#"
                                                        data-bs-toggle="dropdown"
                                                        aria-haspopup="true"
                                                        aria-expanded="false"
                                                    >
                                                        <i class="ti ti-chevron-right f-18"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="departments" role="tabpanel" aria-labelledby="departments-tab">
                            @foreach($departments as $i => $department)
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5 class="mb-0">{{ $department['name'] }}</h5>
                                            <div class="dropdown">
                                                <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none"
                                                   href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                   aria-expanded="false">
                                                    <i class="ti ti-dots f-18"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="#">Today</a>
                                                    <a class="dropdown-item" href="#">Weekly</a>
                                                    <a class="dropdown-item" href="#">Monthly</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row align-items-center justify-content-center">
                                            <div class="col-md-5 col-xl-5">
                                                <div class="mt-3 row align-items-center">
                                                    <div class="col-6">
                                                        <p class="text-muted mb-1">On Progress Training</p>
                                                        <h5 class="mb-0">{{ $department['on_progress'] }}</h5>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <div class="my-n4" style="width: 130px">
                                                                    <div id="total-earning-graph-{{ $i }}"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 col-xl-5">
                                                <div class="mt-3 row align-items-center">
                                                    <div class="col-6">
                                                        <p class="text-muted mb-1">Total Training</p>
                                                        <h5 class="mb-0">{{ $department['total'] }}</h5>
                                                    </div>
                                                    <div class="col-6">
                                                        <p class="text-muted mb-1">Percentage</p>
                                                        <h5 class="mb-0">{{ $department['percentage'] }} %</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-xl-2">
                                                <div class="mt-3 d-grid">
                                                    <button
                                                        class="btn btn-outline-success d-flex align-items-center justify-content-center">
                                                         Detail <i class="ti ti-arrow-right"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
                            @foreach($users as $user)
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-start">
                                        <img src="{{ $user['avatar'] }}" alt="user-image" class="user-avtar wid-45 rounded-circle">
                                        <h5 class="mb-0" style="margin-left: 20px">{{ $user['name'] }}<br><small style="color: grey; text-decoration: none">{{ $user['email'] }}</small></h5>
                                    </div>
                                    <div class="row align-items-center justify-content-center">
                                        <div class="col-md-5 col-xl-5 col-sm-6">
                                            <div class="mt-3 row align-items-center">
                                                <div class="col-12">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="my-n4" style="width: 130px">
                                                                <div id="total-earning-user-{{ $user['id'] }}"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 col-xl-5 col-sm-6">
                                            <div class="mt-3 row align-items-center">
                                                <div class="col-12">
                                                    <p class="text-muted mb-1">Percentage</p>
                                                    <h5 class="mb-0">{{ $user['progress_percentage'] }} %</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-xl-2">
                                            <div class="mt-3 d-grid">
                                                <button
                                                    class="btn btn-outline-success d-flex align-items-center justify-content-center">
                                                    Detail <i class="ti ti-arrow-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
@endsection

@push('script')
    <script src="/dashboard/assets/js/plugins/apexcharts.min.js"></script>
    <script src="/dashboard/assets/js/plugins/simple-datatables.js"></script>
    <script>
        const dataTable = new simpleDatatables.DataTable('#pc-dt-simple', {
            sortable: false,
            perPage: 5
        });
    </script>
    <script>
        'use strict';
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                floatchart();
            }, 500);
        });
        function floatchart() {
            (function () {
                var options = {
                    chart: {
                        height: 200,
                        type: 'donut'
                    },
                    series: [
                        @forelse($departments as $i => $department)
                            {{ 1 }},
                        @empty
                            1
                        @endforelse
                    ],
                    colors: [
                        @forelse($departments as $i => $department)
                            '{{ \App\Models\Department::COLOR_LIST[$i] }}',
                        @empty
                            '#000'
                        @endforelse
                    ],
                    labels: [
                        @forelse($departments as $department)
                            @json($department['name']),
                        @empty
                            'No Data'
                        @endforelse
                    ],
                    fill: {
                        opacity: [
                            @forelse($departments as $i => $department)
                                1,
                            @empty
                                1
                            @endforelse
                        ]
                    },
                    legend: {
                        show: false
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '65%',
                                labels: {
                                    show: true,
                                    name: {
                                        show: true
                                    },
                                    value: {
                                        show: true
                                    }
                                }
                            }
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    responsive: [
                        {
                            breakpoint: 480,
                            options: {
                                plotOptions: {
                                    pie: {
                                        donut: {
                                            size: '65%',
                                            labels: {
                                                show: true
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    ]
                };
                var chart = new ApexCharts(document.querySelector('#total-income-graph'), options);
                chart.render();
            })();
            @foreach($users as $user)
            (function () {
                var options = {
                    series: [{{ $user['progress_percentage'] }}],
                    chart: {
                        height: 150,
                        type: 'radialBar',
                    },
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                margin: 0,
                                size: '50%',
                                background: 'transparent',
                                imageOffsetX: 0,
                                imageOffsetY: 0,
                                position: 'front',
                            },
                            track: {
                                background: '#13c53050',
                                strokeWidth: '50%',
                            },

                            dataLabels: {
                                show: true,
                                name: {
                                    show: false,
                                },
                                value: {
                                    formatter: function (val) {
                                        return parseInt(val);
                                    },
                                    offsetY: 7,
                                    color: '#13c530',
                                    fontSize: '20px',
                                    fontWeight: '700',
                                    show: true,
                                }
                            }
                        }
                    },
                    colors: ['#13c530'],
                    fill: {
                        type: 'solid',
                    },
                    stroke: {
                        lineCap: 'round'
                    },
                };
                var chart = new ApexCharts(document.querySelector("#total-earning-user-{{ $user['id'] }}"), options);
                chart.render();
            })();
            @endforeach
            @foreach($departments as $i => $department)
            (function () {
                var options = {
                    series: [{{ $department['percentage'] }}],
                    chart: {
                        height: 150,
                        type: 'radialBar',
                    },
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                margin: 0,
                                size: '50%',
                                background: 'transparent',
                                imageOffsetX: 0,
                                imageOffsetY: 0,
                                position: 'front',
                            },
                            track: {
                                background: '{{ \App\Models\Department::COLOR_LIST[$i] }}50',
                                strokeWidth: '50%',
                            },

                            dataLabels: {
                                show: true,
                                name: {
                                    show: false,
                                },
                                value: {
                                    formatter: function (val) {
                                        return parseInt(val);
                                    },
                                    offsetY: 7,
                                    color: '{{ \App\Models\Department::COLOR_LIST[$i] }}',
                                    fontSize: '20px',
                                    fontWeight: '700',
                                    show: true,
                                }
                            }
                        }
                    },
                    colors: ['{{ \App\Models\Department::COLOR_LIST[$i] }}'],
                    fill: {
                        type: 'solid',
                    },
                    stroke: {
                        lineCap: 'round'
                    },
                };
                var chart = new ApexCharts(document.querySelector("#total-earning-graph-{{ $i }}"), options);
                chart.render();
            })();
            @endforeach
        }
    </script>
@endpush
