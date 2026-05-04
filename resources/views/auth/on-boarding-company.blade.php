@extends('layouts.auth')

@section('content')
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <div class="auth-main">
        <div class="auth-wrapper v2">
            <div class="auth-sidecontent">
                <img src="/dashboard/assets/images/authentication/img-auth-sideimg.jpg" alt="images"
                     class="img-fluid img-auth-side">
            </div>
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">
                        <h4 class="text-center f-w-500 mb-3">{{ __('Onboard Company') }}</h4>
                        <p>Please describe your company information</p>
                        <form action="/on-boarding-company" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label>Company Name</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Company Name" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            </div>
                            <div class="form-group mb-3">
                                <label>Company Size</label>
                                <select class="form-control" name="size" required>
                                    <option>--Select--</option>
                                    @foreach(\App\Models\Company::COMPANY_SIZE_LIST as $type => $label)
                                        <option value="{{ $type }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label>Industry</label>
                                <select class="form-control" name="industry" required>
                                    <option>--Select--</option>
                                    @foreach(\App\Models\Company::COMPANY_INDUSTRY_LIST as $industry)
                                        <option value="{{ $industry }}">{{ $industry }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Continue to Dashboard</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
