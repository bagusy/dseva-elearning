@extends('layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <div class="container title">
                <div class="row justify-content-center text-center wow fadeInUp" data-wow-delay="0.2s">
                    <div class="col-md-8 col-xl-6">
                        <h2 class="mb-3 mt-5">Training</h2>
                        <p class="mb-5">This is the list of training</p>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row align-items-center justify-content-center mb-4 mb-sm-5">
                    @foreach($courses as $course)
                        <div class="col-md-6 col-lg-3 text-center">
                            <div class="card">
                                <div class="card-body">
                                    <a href="/training/{{ $course['id'] }}">
                                        <img class="zoom pt-2"
                                             src="{{ $course['thumbnail_img'] ?? '/dashboard/assets/images/training.jpg' }}"
                                             width="100%"/>
                                        <h5 style="margin-top: 5px">{{ $course['title'] }}</h5>
                                        <div class="row">
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
                                        </div>
                                    </a>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush

@push('head')

@endpush
