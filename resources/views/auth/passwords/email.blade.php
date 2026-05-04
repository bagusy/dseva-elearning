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
                        <a href="#"><img src="/dashboard/assets/images/logo.png" style="width: 180px; height: auto;" class="mb-4 img-fluid" alt="img"></a>
                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <h3 class="mb-0"><b>Forgot Password</b></h3>
                            <a href="/login" class="link-primary">Back to Login</a>
                        </div>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label">Email Address</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email Address" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <p class="mt-4 text-sm text-muted">Do not forgot to check SPAM box.</p>
                            <div class="d-grid mt-3">
                                <button type="submit" class="btn btn-primary">Send Password Reset Email</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
