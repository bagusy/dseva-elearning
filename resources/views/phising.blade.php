@extends('layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <div class="container title">
                <div class="row justify-content-center text-center wow fadeInUp" data-wow-delay="0.2s">
                    <div class="col-md-8 col-xl-6">
                        <h2 class="mb-3 mt-5">Phishing</h2>
                        <p class="mb-5">This is the list of phishing template</p>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row align-items-center justify-content-center mb-4 mb-sm-5">
                    @foreach($phishings as $phishing)
                        <div class="col-md-6 col-lg-3 text-center">
                            <div class="card">
                                <div class="card-body">
                                    <a href="#">
                                        <img style="height: auto; width: 100%" class="zoom pt-2" src="{{ $phishing['logo'] }}"/>
                                        <h5 style="margin-top: 5px">{{ $phishing['name'] }}</h5>
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
