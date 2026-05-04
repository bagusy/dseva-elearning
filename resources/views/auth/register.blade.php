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
                        @if(env('APP_ENV') === 'production' || env('APP_ENV') === 'local')
                            <div class="text-center">
                                <a href="#"><img src="/dashboard/assets/images/logo.png" style="width: 180px; height: auto;" alt="img"></a>
                                <div class="d-grid my-3">
                                    <a href="/auth/google" class="btn mt-2 btn-light-primary bg-light text-muted">
                                        <img src="/dashboard/assets/images/authentication/google.svg" alt="img"> <span> Sign Up with Google</span>
                                    </a>
                                </div>
                            </div>
                            <div class="saprator my-3">
                                <span>OR</span>
                            </div>
                        @else
                            <div class="text-center">
                                <a href="#"><img src="/dashboard/assets/images/logo.png" style="width: 180px; height: auto;" alt="img"></a>
                            </div>
                        @endif
                        <h4 class="text-center f-w-500 mb-3">Sign up with your work email.</h4>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Name" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email Address" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <hr>
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
                            <div class="d-flex mt-1 justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" id="customCheckc1" checked="true" required>
                                    <label class="form-check-label text-muted" for="customCheckc1">I agree to all the Terms & Condition</label>
                                </div>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                        </form>
                        <div class="d-flex justify-content-between align-items-end mt-4">
                            <h6 class="f-w-500 mb-0">Already have an Account?</h6>
                            <a href="/login" class="link-primary">Sign In</a>
                        </div>
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
