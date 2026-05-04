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
                                <li class="breadcrumb-item"><a href="javascript: void(0)">Manage Course</a></li>
                                <li class="breadcrumb-item" aria-current="page">Detail</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Course Detail</h2>
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
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avtar avtar-s bg-light-success">
                                        <i class="ti ti-list-check f-18"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 mx-3">
                                    <h4 class="mb-0">{{ $course['title'] }}</h4>
                                    <p class="mb-0">by {{ $course['user']['name'] }} {!! $course['status_badge'] !!}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="ti ti-currency-dollar f-18"></i> <b
                                        class="f-20">{{ $course['price_in_usd'] }}</b>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6>Description</h6>
                            <p>{{ $course['description'] }}</p>
                            <ul class="list-inline pt-2">
                                <li class="list-inline-item">
                                    <span
                                        class="bg-body rounded fs-6 p-2 border text-body">{{ $course['category'] }}</span>
                                </li>
                                <li class="list-inline-item">
                                    <span
                                        class="bg-{{ \App\Models\Course::LEVEL_COLOR[$course['level']] }} rounded fs-6 p-2 border text-white">{{ $course['level'] }}</span>
                                </li>
                            </ul>
                            <div class="d-flex align-items-center justify-content-between mt-4">
                                <ul class="list-inline mb-0 me-2">
                                    <li class="list-inline-item"><i
                                            class="text-muted ti ti-video"></i> {{ $course->subSections()->where('type',\App\Models\SubSection::TYPE_VIDEO)->count() }}
                                        Videos
                                    </li>
                                    <li class="list-inline-item"><i
                                            class="text-muted ti ti-flag"></i> {{ $course->subSections()->where('type',\App\Models\SubSection::TYPE_QUIZ)->count() }}
                                        Quizes
                                    </li>
                                </ul>
                                @if($course['status'] === \App\Models\Course::STATUS_DRAFT)
                                    <form action="/courses/{{ $course['id'] }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" value="PUT">
                                        <input type="hidden" name="status" value="{{ \App\Models\Course::STATUS_PUBLISHED }}">
                                        <button type="submit" class="btn btn-primary">Publish</button>
                                    </form>
                                @elseif($course['status'] === \App\Models\Course::STATUS_PUBLISHED)
                                    <form action="/courses/{{ $course['id'] }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" value="PUT">
                                        <input type="hidden" name="status" value="{{ \App\Models\Course::STATUS_DRAFT }}">
                                        <button type="submit" class="btn btn-secondary">Unpublish</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <h5 class="mb-0">Course Contents</h5>
                                </div>
                                <div class="col-6" style="text-align: right">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#add-section-modal"
                                       class="btn btn-info btn-sm"><i class="ti ti-plus"></i> Add Section</a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <tbody>
                                    @foreach($course['sections']->sortBy('index') as $section)
                                        <tr>
                                            <td>
                                                @if($hasSectionArrow)
                                                    @if($section['index'] !== $topSection['index'])
                                                        <a href="javascript:void(0)"
                                                           onclick="document.getElementById('reorder-section-{{ $section['id'] }}-up').submit()"
                                                           class="avtar avtar-xs bg-light-secondary flex-shrink-0 me-2">
                                                            <i class="ti ti-arrow-up f-14"></i>
                                                        </a>
                                                        <form id="reorder-section-{{ $section['id'] }}-up"
                                                              action="/courses/{{ $course['id'] }}/sections/{{ $section['id'] }}/up"
                                                              method="POST" style="display:none">
                                                            @csrf
                                                        </form>
                                                    @else
                                                        <div class="avtar avtar-xs flex-shrink-0 me-2"
                                                             style="background-color: transparent"></div>
                                                    @endif

                                                    @if($section['index'] !== $bottomSection['index'])
                                                        <a href="javascript:void(0)"
                                                           onclick="document.getElementById('reorder-section-{{ $section['id'] }}-down').submit()"
                                                           class="avtar avtar-xs bg-light-secondary flex-shrink-0 me-2">
                                                            <i class="ti ti-arrow-down f-14"></i>
                                                        </a>
                                                        <form id="reorder-section-{{ $section['id'] }}-down"
                                                              action="/courses/{{ $course['id'] }}/sections/{{ $section['id'] }}/down"
                                                              method="POST" style="display:none">
                                                            @csrf
                                                        </form>
                                                    @else
                                                        <div class="avtar avtar-xs flex-shrink-0 me-2"
                                                             style="background-color: transparent"></div>
                                                    @endif
                                                @endif
                                            </td>
                                            <td><h4>{{ $section['title'] }}</h4></td>
                                            <td>
                                                <a href="javascript:void(0)"
                                                   onclick="showAllVideos('{{ $section['id'] }}')"
                                                   data-bs-toggle="modal" data-bs-target="#add-subSection-video-modal"
                                                   class="avtar avtar-xs bg-light-success flex-shrink-0 me-2">
                                                    <i class="ti ti-video-plus f-14"></i>
                                                </a>
                                                <a href="javascript:void(0)"
                                                   onclick="showAllQuiz('{{ $section['id'] }}')"
                                                   data-bs-toggle="modal" data-bs-target="#add-subSection-quiz-modal"
                                                   class="avtar avtar-xs bg-light-warning flex-shrink-0 me-2">
                                                    <i class="ti ti-flag f-14"></i>
                                                </a>
                                                <a href="javascript:void(0)"
                                                   onclick="document.getElementById('form-delete-section-{{ $section['id'] }}').submit()"
                                                   class="avtar avtar-xs bg-light-danger flex-shrink-0 me-2">
                                                    <i class="ti ti-trash f-14"></i>
                                                </a>
                                                <form style="display: none"
                                                      id="form-delete-section-{{ $section['id'] }}"
                                                      action="/courses/{{ $course['id'] }}/sections/{{ $section['id'] }}"
                                                      method="POST">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                </form>
                                            </td>
                                        </tr>
                                        @foreach($section['subSections']->sortBy('index') as $subSection)
                                            <tr style="background-color: {{ $subSection['type'] === \App\Models\SubSection::TYPE_QUIZ ? '#ffdede':'' }}">
                                                <td>&nbsp;</td>
                                                <td>
                                                    @if($subSection['type'] === \App\Models\SubSection::TYPE_VIDEO)
                                                    <div class="row align-items-center">
                                                        <div class="col-auto pe-0">
                                                            <img src="{{ $subSection['video']['thumbnail_img'] }}"
                                                                 alt="user-image" class="hei-55 rounded"/>
                                                        </div>
                                                        <div class="col">
                                                            <h6 class="mb-2"><span
                                                                    class="text-truncate w-100">{{ $subSection['video']['title'] }}</span>
                                                            </h6>
                                                            <p class="text-muted f-12 mb-0">
                                                                <span
                                                                    class="badge bg-{{ \App\Models\SubSection::TYPE_COLOR[$subSection['type']] }}">{{ strtoupper($subSection['type']) }}</span>
                                                                <span
                                                                    class="text-truncate w-100">{{ $subSection['video']['category'] }}</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    @else
                                                        <h5>{{ $subSection['quiz']['title'] }} <small>(Min : {{ $subSection['quiz']['min_score'] }} %)</small></h5>
                                                    @endif
                                                </td>
                                                <td class="f-w-600">
                                                    <a href="#"
                                                       class="avtar avtar-xs bg-light-secondary flex-shrink-0 me-2">
                                                        <i class="ti ti-arrow-up f-14"></i>
                                                    </a>
                                                    <a href="#"
                                                       class="avtar avtar-xs bg-light-secondary flex-shrink-0 me-2">
                                                        <i class="ti ti-arrow-down f-14"></i>
                                                    </a>
                                                    <a href="#"
                                                       class="avtar avtar-xs bg-light-danger flex-shrink-0 me-2">
                                                        <i class="ti ti-trash f-14"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <div class="modal fade" id="add-section-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="mb-0">Add New Section</h5>
                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="modal">
                        <i class="ti ti-x f-20"></i>
                    </a>
                </div>
                <form action="/courses/{{ $course['id'] }}/sections" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Section Title</label>
                                    <input type="text" name="title" class="form-control" placeholder="Section Title">
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

    <div class="modal fade" id="add-subSection-video-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="mb-0">Add Video</h5>
                    <a href="/videos/list"><i class="ti ti-plus"></i> Upload New Videos</a>
                </div>
                <form action="#" id="form-add-video" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive" id="new-table">
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 text-end">
                            <button type="button" class="btn btn-link-danger btn-pc-default" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Add Videos to this Course</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add-subSection-quiz-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="mb-0">Add Quiz</h5>
                    <a href="/quiz/create"><i class="ti ti-plus"></i> Create New Quiz</a>
                </div>
                <form action="#" id="form-add-quiz" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive" id="quiz-table">
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 text-end">
                            <button type="button" class="btn btn-link-danger btn-pc-default" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Add Quiz to this Course</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="/dashboard/assets/js/plugins/simple-datatables.js"></script>
    <script>
        function showAllVideos(sectionId) {
            $('#form-add-video').attr('action', '/courses/{{ $course['id'] }}/sections/' + sectionId + '/videos');

            document.getElementById('new-table').innerHTML = "";
            document.getElementById('new-table').innerHTML = '<table class="table table-hover" id="pc-dt-simple"> </table>';
            document.getElementById('pc-dt-simple').innerHTML = "";
            $.get("/api/courses/{{ $course['id'] }}/videos", function (data, status) {
                document.getElementById('pc-dt-simple').innerHTML = data;
                const dataTable = new simpleDatatables.DataTable('#pc-dt-simple', {
                    sortable: false,
                    perPage: 10
                });
            });
        }
    </script>
    <script>
        function showAllQuiz(sectionId) {
            $('#form-add-quiz').attr('action', '/courses/{{ $course['id'] }}/sections/' + sectionId + '/quiz');

            document.getElementById('quiz-table').innerHTML = "";
            document.getElementById('quiz-table').innerHTML = '<table class="table table-hover" id="pc-dt-quiz"> </table>';
            document.getElementById('pc-dt-quiz').innerHTML = "";
            $.get("/api/courses/{{ $course['id'] }}/quiz", function (data, status) {
                document.getElementById('pc-dt-quiz').innerHTML = data;
                const dataTable = new simpleDatatables.DataTable('#pc-dt-quiz', {
                    sortable: false,
                    perPage: 10
                });
            });
        }
    </script>
    <script>
        function showPreview() {
            var img = document.getElementById('flupld').value.toString();
            document.getElementById('img-name').innerHTML = img.replace("C:\\fakepath\\", "") + ' <a href="javascript:void(0)" style="color: red" onclick="removePreview()">x</a>'
        }

        function removePreview() {
            document.getElementById('flupld').value = ''
            document.getElementById('img-name').innerHTML = '<span class="text-danger">*</span> Recommended resolution is 640*320 with file size'
        }
    </script>
@endpush
