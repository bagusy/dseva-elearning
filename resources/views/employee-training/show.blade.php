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
                            <h5 class="mb-0">
                                {{ $subSection[$subSection['type']]['title'] }}
                                {!! $courseEnrollment['status_badge'] !!}
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $orderedSubs = collect();
                                foreach ($course['sections']->sortBy('index') as $secOrdered) {
                                    foreach ($secOrdered['subSections']->sortBy('index') as $subOrdered) {
                                        $orderedSubs->push($subOrdered);
                                    }
                                }
                                $currentPos = $orderedSubs->search(fn($s) => $s['id'] === $subSection['id']);
                                $nextSub = $currentPos !== false ? $orderedSubs->get($currentPos + 1) : null;
                            @endphp

                            @if($subSection['type'] === \App\Models\SubSection::TYPE_VIDEO)
                                @include('layouts.video-player', ['video' => $subSection['video']])

                                @if($nextSub)
                                    @php
                                        $nextType  = $nextSub['type'];
                                        $nextRefId = $nextType === \App\Models\SubSection::TYPE_VIDEO ? $nextSub['video_id'] : $nextSub['quiz_id'];
                                        $nextTitle = $nextSub[$nextType]['title'] ?? '';
                                        $nextLabel = $nextType === \App\Models\SubSection::TYPE_QUIZ ? 'Kerjakan Tugas' : 'Lanjut ke Video Berikutnya';
                                        $nextIcon  = $nextType === \App\Models\SubSection::TYPE_QUIZ ? 'ti-flag' : 'ti-video';
                                    @endphp
                                    <div class="d-flex justify-content-end mt-4">
                                        <a href="/trainings/{{ $courseEnrollment['id'] }}?{{ $nextType }}_id={{ $nextRefId }}"
                                           class="btn btn-primary">
                                            <i class="ti {{ $nextIcon }} me-1"></i>
                                            {{ $nextLabel }}: {{ $nextTitle }}
                                        </a>
                                    </div>
                                @endif
                            @else
                                @include('layouts.quiz-question-list', [
                                            'course' => $course,
                                            'quiz' => $subSection['quiz'],
                                            'quizItems' => $subSection['quiz']['items'],
                                            'quizId' => $subSection['quiz_id'],
                                            'courseEnrollmentId' => $courseEnrollment['id']
                                        ])
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
                                        <a href="/trainings/{{ $courseEnrollment['id'] }}?{{ $subSection['type'] }}_id={{ $subSection[$subSection['type']]['id'] }}"
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
                                                @php
                                                    $step = $subSection['type'] === \App\Models\SubSection::TYPE_VIDEO ? $progress->where('video_id',$subSection['video_id'])->first():$progress->where('quiz_id',$subSection['quiz_id'])->first();
                                                @endphp
                                                <div class="badge bg-{{ $subSection['id'] === $courseEnrollment['sub_section_id']?'primary':($step['is_done'] || $currentIndex + 1 === $step['index']?'light-secondary':'danger') }} f-12"><i
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

                @if($courseEnrollment['status'] === \App\Models\CourseEnrollment::STATUS_COMPLETED)
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h5 class="mb-0">Training has been completed !</h5>
                                    <br>
                                    <img src="/images/congratulation.gif" style="max-width: 100%">
                                    <br>
                                    <a target="_blank" href="/trainings/{{ $courseEnrollment['id'] }}/certificate" class="btn btn-primary mt-2">Download Certificate</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
@endsection
