@extends('layouts.app')

@section('content')

    <div class="pc-container">
        <div class="pc-content">
            <div class="container title">
                <div class="row justify-content-center text-center wow fadeInUp" data-wow-delay="0.2s">
                    <div class="col-md-12 col-xl-12">
                        <a href="/videos"><span class="badge bg-light-{{ !isset($_GET['category'])?'success':'secondary' }} rounded-pill f-12">{!! !isset($_GET['category'])?'<i class="fa fa-check"></i>':'' !!} All Categories</span></a>
                        @foreach(\App\Models\Video::CATEGORY_LIST as $category)
                            <a href="?category={{ $category }}"><span class="badge bg-light-{{ isset($_GET['category']) && $_GET['category'] === $category?'success':'secondary' }} rounded-pill f-12">{!! isset($_GET['category']) && $_GET['category'] === $category?'<i class="fa fa-check"></i>':'' !!} {{ $category }}</span></a>
                        @endforeach
                    </div>
                    <div class="col-md-8 col-xl-6">
                        <h2 class="mb-3 mt-5">{{ !isset($_GET['category'])?'All Videos':$_GET['category'] }}</h2>
                        <!--p class="mb-5">Search here</p-->
                    </div>
                </div>
                @can('create video')
                    <div class="text-end p-4 pb-0">
                        <a href="/videos/list" class="btn btn-primary d-inline-flex align-items-center">
                            <i class="ti ti-list f-18"></i> Manage Videos
                        </a>
                    </div>
                @endcan
            </div>
            @if(count($latestVideos) > 0)
            <div class="container">
                <div class="row align-items-center justify-content-center mb-4 mb-sm-5">
                    <h4 class="mb-3 mt-5">Latest Video</h4>
                    @foreach($latestVideos as $video)
                        <div class="col-md-6 col-lg-3">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#videoModal{{ $video['id'] }}">
                                <img class="zoom pt-2" src="{{ $video['thumbnail_img'] }}" width="100%"/>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            @forelse($videoPerCategory as $category => $videos)
            <div class="container">
                <div class="row align-items-center justify-content-center mb-4 mb-sm-5">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="mb-3 mt-5">{{ $category }}</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="/videos?category={{ $category }}"><span class="mt-5 badge bg-light-success rounded-pill f-12">See All <i class="fa fa-arrow-circle-right"></i></span></a>
                        </div>
                    </div>
                    @foreach($videos as $video)
                        <div class="col-md-6 col-lg-3">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#videoModal{{ $video['id'] }}">
                                <img class="zoom pt-2" src="{{ $video['thumbnail_img'] }}" width="100%"/>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @empty
            @endforelse

            @if(count($allList) > 0)
                <div class="container">
                    <div class="row align-items-center justify-content-center mb-4 mb-sm-5">
                        <h4 class="mb-3 mt-5">{{ $_GET['category'] }}</h4>
                        @foreach($allList as $video)
                            <div class="col-md-6 col-lg-3">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#videoModal{{ $video['id'] }}">
                                    <img class="zoom pt-2" src="{{ $video['thumbnail_img'] }}" width="100%"/>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(count($latestVideos) > 0)
                @include('layouts.video-modal', ['videos' => $latestVideos])
            @endif

            @forelse($videoPerCategory as $category => $videos)
                @include('layouts.video-modal', ['videos' => $videos])
            @empty
            @endforelse

            @if(count($allList) > 0)
                @include('layouts.video-modal', ['videos' => $allList])
            @endif
        </div>
    </div>
@endsection

@push('script')
    <script src="https://fast.wistia.net/assets/external/E-v1.js" async></script>
    @if(count($latestVideos) > 0)
        @foreach($latestVideos as $video)
            @if($video['source'] === \App\Models\Video::SOURCE_YOUTUBE)
                <script>
                    $("#videoModal{{ $video['id'] }}").on('hidden.bs.modal', function (e) {
                        $("#videoModal{{ $video['id'] }} iframe").attr("src", $("#videoModal{{ $video['id'] }} iframe").attr("src"));
                    });
                </script>
            @endif
        @endforeach
    @endif

    @forelse($videoPerCategory as $category => $videos)
        @foreach($videos as $video)
            @if($video['source'] === \App\Models\Video::SOURCE_YOUTUBE)
                <script>
                    $("#videoModal{{ $video['id'] }}").on('hidden.bs.modal', function (e) {
                        $("#videoModal{{ $video['id'] }} iframe").attr("src", $("#videoModal{{ $video['id'] }} iframe").attr("src"));
                    });
                </script>
            @endif
        @endforeach
    @empty
    @endforelse

    @if(count($allList) > 0)
        @foreach($allList as $video)
            @if($video['source'] === \App\Models\Video::SOURCE_YOUTUBE)
                <script>
                    $("#videoModal{{ $video['id'] }}").on('hidden.bs.modal', function (e) {
                        $("#videoModal{{ $video['id'] }} iframe").attr("src", $("#videoModal{{ $video['id'] }} iframe").attr("src"));
                    });
                </script>
            @endif
        @endforeach
    @endif
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
