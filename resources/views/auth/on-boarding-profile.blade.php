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
                        <h4 class="text-center f-w-500 mb-3">{{ __('Complete your profile') }}</h4>
                        <p>Please enter your personal information</p>
                        <form action="/on-boarding-profile" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label>Full Name</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Full Name" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            </div>
                            <div class="form-group mb-3">
                                <label>Select Avatar</label>
                                <br>
                                @for($i = 1; $i <= 10; $i ++)
                                    <label>
                                        <input type="radio" name="avatar" value="{{ url('/dashboard/assets/images/user/avatar-' . $i . '.jpg') }}" {{ $i == 1?'checked':'' }}>
                                        <img src="/dashboard/assets/images/user/avatar-{{ $i }}.jpg" alt="Avatar {{ $i }}">
                                    </label>
                                @endfor
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

@push('head')
    <style>
        [type=radio] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* IMAGE STYLES */
        [type=radio] + img {
            cursor: pointer;
            border-radius: 50%;
            width: 50px;
            height: auto;
            margin: 3px;
        }

        /* CHECKED STYLES */
        [type=radio]:checked + img {
            outline: 2px solid #c02e2e;
            border-radius: 50%;
        }
    </style>
@endpush
