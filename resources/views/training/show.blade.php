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
                                <li class="breadcrumb-item"><a href="javascript: void(0)">Training</a></li>
                                <li class="breadcrumb-item" aria-current="page">Training Detail</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">{{ $course['title'] }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ $subSection[$subSection['type']]['title'] }}</h5>
                        </div>
                        <div class="card-body">
                            @if($subSection['type'] === \App\Models\SubSection::TYPE_VIDEO)
                                @include('layouts.video-player', ['video' => $subSection->video])
                            @else
                                @include('layouts.quiz-question-list', ['quizItems' => $subSection->quiz->items])
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Course Journey</h5>
                        </div>
                        <div class="card-body" style="max-height: 500px; overflow-y: scroll">
                            @foreach($course['sections']->sortBy('index') as $section)
                                <div class="mb-2">
                                    <p class="mb-2">{{ $section['title'] }}
                                        {{--                                        <span class="float-end">70%</span>--}}
                                    </p>
                                    {{--                                    <div class="progress progress-primary" style="height: 8px">--}}
                                    {{--                                        <div class="progress-bar" style="width: 70%"></div>--}}
                                    {{--                                    </div>--}}
                                </div>

                                <div class="d-grid gap-2">
                                    @foreach($section['subSections']->sortBy('index') as $subSection)
                                        <a href="/training/{{ $course['id'] }}?{{ $subSection['type'] }}_id={{ $subSection[$subSection['type']]['id'] }}"
                                           class="btn btn-link-secondary">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                            <span class="p-1 d-block bg-warning rounded-circle">
                                              <span class="visually-hidden">New alerts</span>
                                            </span>
                                                </div>
                                                <div class="flex-grow-1 mx-2">
                                                    <p class="mb-0 d-grid text-start">
                                                        <span
                                                            class="text-truncate w-100">{{ $subSection[$subSection['type']]->title }}</span>
                                                    </p>
                                                </div>
                                                <div class="badge bg-light-secondary f-12"><i
                                                        class="ti ti-{{ $subSection['type'] === \App\Models\SubSection::TYPE_VIDEO?'video':'flag' }} text-sm"></i>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @can('assign course')
                <form class="row" action="/training/{{ $course['id'] }}/start" method="POST">
                    @csrf
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0">Target Employee</h5>
                                </div>
                                <p><small>Existing and NEW users who meet the selected criteria will be automatically
                                        assigned to policy.</small></p>
                                <div class="row g-3 mt-0">
                                    <div class="form-group">
                                        <input type="radio" class="form-check-input" name="target" value="all" checked
                                               onclick="setTarget('all')"> All Departments
                                        <input type="radio" class="form-check-input" name="target" value="custom"
                                               onclick="setTarget('custom')"> Custom
                                    </div>
                                    <div class="form-group" style="display: none" id="custom-target">
                                        <label class="form-label">Departments</label>
                                        <select id="select-department" name="department_id[]" multiple="multiple">
                                            @foreach ($departments as $department)
                                                <option value="{{ $department['id'] }}">{{ $department['name'] }}</option>
                                            @endforeach
                                        </select>
                                        <small>Selected departments CANNOT be removed after the settings are saved!</small>
                                    </div>
                                </div>
                                <br>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0">Notifications</h5>
                                </div>
                                <p><small>Users will receive an email notification about training started with the link to
                                        the employee app.</small></p>
                                <div class="row g-3 mt-3">
                                    <div class="form-group">
                                        <label class="form-label">Subject</label>
                                        <input type="text" name="subject" required class="form-control"
                                               placeholder="Subject" value="You are required to complete this training">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Message</label>
                                        <textarea class="form-control" name="message" placeholder="Message" required rows="4">You’ve been assigned security awareness training. Please complete it as soon as possible!</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0">Days to complete</h5>
                                </div>
                                <p><small>The countdown for the user will start when training is assigned. Overdue training
                                        remains available to complete until the admin ends training.</small></p>
                                <div class="row g-3 mt-3">
                                    <div class="form-group">
                                        <input type="radio" class="form-check-input" name="day_completion" value="7" checked
                                               onclick="setDaysCompletion('7')"> 7 Days
                                        <input type="radio" class="form-check-input" name="day_completion" value="30"
                                               onclick="setDaysCompletion('30')"> 30 Days
                                        <input type="radio" class="form-check-input" name="day_completion" value="custom"
                                               onclick="setDaysCompletion('custom')"> Custom
                                    </div>
                                    <div class="form-group" style="display: none" id="custom-day">
                                        <label class="form-label">Days Completion</label>
                                        <input type="number" min="1" name="day_completion_value" class="form-control"
                                               placeholder="Days">
                                    </div>
                                </div>
                                <br>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0">Start Date</h5>
                                </div>
                                <div class="row g-3 mt-3">
                                    <div class="form-group">
                                        <input class="form-control" type="date" name="start_date" value="{{ now()->format('Y-m-d') }}" id="demo-date-only">
                                    </div>
                                </div>
                                <br>
                                <div class="mt-3 text-end">
                                    <button type="submit" class="btn btn-primary align-items-center">
                                        <i class="ti ti-send f-18"></i> Start Training
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @endcan
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
@endsection

@push('script')
    @can('assign course')
    <script src="/dashboard/assets/js/select/selectize.min.js"></script>
    <script>
        function setTarget(value) {
            if (value === 'custom') {
                document.getElementById('custom-target').style.display = '';
            } else {
                document.getElementById('custom-target').style.display = 'none';
            }
        }

        function setDaysCompletion(value) {
            if (value === 'custom') {
                document.getElementById('custom-day').style.display = '';
            } else {
                document.getElementById('custom-day').style.display = 'none';
            }
        }

        $("#select-department").selectize({
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
    @endcan
@endpush

@push('head')
    @can('assign course')
    <link rel="stylesheet" href="/dashboard/assets/css/select/selectize.default.min.css"/>
    @endcan
@endpush
