@extends('layouts.app')

@section('content')

    <div class="pc-container">
        <div class="pc-content">
            <div class="container title">
                <div class="row justify-content-center text-center wow fadeInUp" data-wow-delay="0.2s">
                    <div class="col-md-8 col-xl-6">
                        <h2 class="mb-3 mt-5">Video Guides for a Quick Start</h2>
                        <p class="mb-5">A few short videos to make your experience with Dseva easier!</p>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row align-items-center justify-content-center mb-4 mb-sm-5">
                    @foreach($videos as $video)
                    <div class="col-md-6 col-lg-3">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#videoModal{{ $video['id'] }}">
                            <img class="zoom pt-2" src="{{ $video['thumbnail_img'] }}" width="100%"/>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

            @include('layouts.video-modal', ['videos' => $videos])

            <div class="container title">
                <div class="row justify-content-center text-center wow fadeInUp" data-wow-delay="0.2s">
                    <div class="col-md-8 col-xl-6">
                        <h3 class="mb-3 mt-2">You might be interested in...</h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-1">How-To Guides</h4>
                                    <p class="text-muted mb-0">Frequently asked questions, introduction, guides, and troubleshooting instructions.</p>
                                </div>
                                <div class="col-4 text-end">
                                    <img src="/dashboard/assets/images/landing/lamp.svg">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-1">Book a Demo</h4>
                                    <p class="text-muted mb-0">Get a full demo of the platform’s features and answers to all your questions.</p>
                                </div>
                                <div class="col-4 text-end">
                                    <img src="/dashboard/assets/images/landing/demo.svg">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-1">Pricing</h4>
                                    <p class="text-muted mb-0">Explore benefits and features by comparing the <b>Free</b> and <b>Boost</b> plans.</p>
                                </div>
                                <div class="col-4 text-end">
                                    <img src="/dashboard/assets/images/landing/pricing.svg">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-1">Become an Affiliate</h4>
                                    <p class="text-muted mb-0">Join the Wizer affiliate program and earn <b>10%</b> of every purchase.</p>
                                </div>
                                <div class="col-4 text-end">
                                    <img src="/dashboard/assets/images/landing/affiliate.svg">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-1">Give Feedback</h3>
                                    <p class="text-muted mb-0">Tell us about your experience with the platform.</p>
                                </div>
                                <div class="col-4 text-end">
                                    <img src="/dashboard/assets/images/landing/feedback.svg">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('script')
    <script src="https://fast.wistia.net/assets/external/E-v1.js" async></script>
    @foreach($videos as $video)
        @if($video['source'] === \App\Models\Video::SOURCE_YOUTUBE)
            <script>
                $("#videoModal{{ $video['id'] }}").on('hidden.bs.modal', function (e) {
                    $("#videoModal{{ $video['id'] }} iframe").attr("src", $("#videoModal{{ $video['id'] }} iframe").attr("src"));
                });
            </script>
        @endif
    @endforeach
@endpush

@push('head')
    <style>
        .zoom {
            padding: 10px;
            transition: transform .2s; /* Animation */
            margin: 0 auto;
        }

        .zoom:hover {
            transform: scale(1.2); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
        }
    </style>
@endpush
