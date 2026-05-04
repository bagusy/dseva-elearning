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
                                <li class="breadcrumb-item" aria-current="page">Manage Course</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Course List</h2>
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
                    <div class="card table-card">
                        <div class="card-body">
                            <div class="text-end p-4 pb-0">
                                <a href="/courses/create" class="btn btn-primary d-inline-flex align-items-center">
                                    <i class="ti ti-plus f-18"></i> Create Course
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="pc-dt-simple">
                                    <thead>
                                    <tr>
                                        <th>Creator</th>
                                        <th>Title</th>
                                        <th>Categories</th>
                                        <th>Level</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($courses as $course)
                                        <tr>
                                            <td>
                                                <div class="row">
                                                    <div class="col-auto pe-0">
                                                        <img src="{{ $course['user']['avatar'] }}" alt="user-image"
                                                             class="wid-40 rounded-circle">
                                                    </div>
                                                    <div class="col">
                                                        <h6 class="mb-0">{{ $course['user']['name'] }}</h6>
                                                        <p class="text-muted f-12 mb-0">{{ $course['user']['email'] }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $course['title'] }}<br>
                                                <small>{{ now()->format('j F Y, H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-6 m-1">
                                                        <span class="badge bg-light-secondary rounded-pill f-12">{{ $course['category'] }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-6 m-1">
                                                        <span class="badge bg-{{ \App\Models\Course::LEVEL_COLOR[$course['level']] }} rounded-pill f-12">{{ $course['level'] }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {!! $course['status_badge'] !!}
                                            </td>
                                            <td class="text-center">
                                                <ul class="list-inline me-auto mb-0">
                                                    @if($course['user_id'] === auth()->user()->id)
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                        title="View">
                                                        <a href="/courses/{{ $course['id'] }}"
                                                           class="avtar avtar-xs btn-link-secondary btn-pc-default">
                                                            <i class="ti ti-eye f-18"></i>
                                                        </a>
                                                    </li>
{{--                                                    @if(auth()->user()->hasRole(\App\Models\User::ROLE_ADMIN) || auth()->user()['id'] === $video['user_id'])--}}
                                                        <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                            title="Edit">
                                                            <a href="#"
                                                               class="avtar avtar-xs btn-link-success btn-pc-default"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#edit-video-modal">
                                                                <i class="ti ti-edit-circle f-18"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                            title="Delete">
                                                            <a href="#"
                                                               class="avtar avtar-xs btn-link-danger btn-pc-default">
                                                                <i class="ti ti-trash f-18"></i>
                                                            </a>
                                                        </li>
{{--                                                    @endif--}}
                                                    @endif
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
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
    <script src="/dashboard/assets/js/plugins/simple-datatables.js"></script>
    <script>
        const dataTable = new simpleDatatables.DataTable('#pc-dt-simple', {
            sortable: false,
            perPage: 10
        });
    </script>
@endpush
