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
                                    <img src="/dashboard/assets/images/authentication/google.svg" alt="img"> <span> Sign In with Google</span>
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
                    <h4 class="text-center f-w-500 mb-3">Login with your email</h4>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email Address" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="d-flex mt-1 justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input input-primary" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-muted" for="customCheckc1">Remember me?</label>
                            </div>
                            <a href="{{ route('password.request') }}"><h6 class="text-secondary f-w-400 mb-0">Forgot Password?</h6></a>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                    <div class="d-flex justify-content-between align-items-end mt-4">
                        <h6 class="f-w-500 mb-0">Don't have an Account?</h6>
                        <a href="/register" class="link-primary">Create Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

