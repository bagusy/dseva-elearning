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
                                <li class="breadcrumb-item" aria-current="page">Quiz</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Quiz List</h2>
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
                                <a href="#" class="btn btn-primary d-inline-flex align-items-center"
                                   data-bs-toggle="modal" data-bs-target="#add-video-modal">
                                    <i class="ti ti-plus f-18"></i> Create New Quiz
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="pc-dt-simple">
                                    <thead>
                                    <tr>
                                        <th>Creator</th>
                                        <th>Title</th>
                                        <th>Min Score</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($quizzes as $quiz)
                                        <tr>
                                            <td>
                                                <div class="row">
                                                    <div class="col-auto pe-0">
                                                        <img src="{{ $quiz['user']['avatar'] }}" alt="user-image"
                                                             class="wid-40 rounded-circle">
                                                    </div>
                                                    <div class="col">
                                                        <h6 class="mb-0">{{ $quiz['user']['name'] }}</h6>
                                                        <p class="text-muted f-12 mb-0">{{ $quiz['user']['email'] }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $quiz['title'] }}<br>
                                                <small>{{ $quiz['created_at']->format('j F Y, H:i') }}</small>
                                            </td>
                                            <td>{{ $quiz['min_score'] }} %</td>
                                            <td class="text-center">
                                                <ul class="list-inline me-auto mb-0">
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                        title="View">
                                                        <a href="/quiz/{{ $quiz['id'] }}"
                                                           class="avtar avtar-xs btn-link-secondary btn-pc-default">
                                                            <i class="ti ti-eye f-18"></i>
                                                        </a>
                                                    </li>
                                                    @if(auth()->user()->hasRole(\App\Models\User::ROLE_ADMIN) || auth()->user()['id'] === $quiz['user_id'])
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                        title="Delete">
                                                        <a href="#"
                                                           class="avtar avtar-xs btn-link-danger btn-pc-default"
                                                           onclick="$('#delete-video').attr('action','/quiz/{{ $quiz['id'] }}').submit()">
                                                            <i class="ti ti-trash f-18"></i>
                                                        </a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {!! $quizzes->links('vendor.pagination.default') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>

    <form id="delete-video" method="POST" action="#" style="display: none">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
    </form>

    <!-- Modal -->
    <div class="modal fade" id="add-video-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="mb-0">Upload Video</h5>
                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="modal">
                        <i class="ti ti-x f-20"></i>
                    </a>
                </div>
                <form action="/quiz" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12" style="text-align: right">
                                Status : <span class="badge bg-warning">Draft</span>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Quiz Title</label>
                                    <input type="text" class="form-control" name="title" required
                                           placeholder="Title">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Minimum Score to Pass (0 ~ 100 %)</label>
                                    <input type="number" class="form-control" name="min_score" required
                                           placeholder="Minimum Score" min="0" max="100">
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 text-end">
                            <button type="button" class="btn btn-link-danger btn-pc-default" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@push('head')
    <link rel="stylesheet" href="/dashboard/assets/css/select/selectize.default.min.css"/>
@endpush

@push('script')

    <script src="/dashboard/assets/js/select/selectize.min.js"></script>
    <script src="/dashboard/assets/js/plugins/simple-datatables.js"></script>
    <script>
        const dataTable = new simpleDatatables.DataTable('#pc-dt-simple', {
            sortable: false,
            perPage: 10
        });
    </script>
    <script>
        $("#select-tag").selectize({
            delimiter: ",",
            persist: false,
            maxItems: null,
            create: function (input) {
                return {
                    value: input,
                    text: input,
                };
            }
        });
    </script>
@endpush
