@extends('layout.appAuth')

@section('title', 'Login')

@section('content')
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div
                    style="display: flex; flex-direction: column; justify-content: center; align-items: center; margin-top: -200px;">
                    <div class="login100-pic js-tilt" data-tilt>
                        <img src="{{ asset('foto/logo.png') }}" alt="IMG">
                    </div>
                    <p style="margin-top: 10px; font-size: 16px;" class="mobile-text">Pelabuhan Cilegon Mandiri</p>
                </div>

                <style>
                    .mobile-text {
                        display: none;

                    }

                    @media (min-width: 768px) {
                        .mobile-text {
                            display: block;
                        }
                    }
                </style>

                <form class="login100-form validate-form" method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <span class="login100-form-title">
                        Member Login
                    </span>

                    <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                        <input class="input100 @error('email') is-invalid @enderror" type="text" name="email"
                            placeholder="Email" value="{{ old('email') }}">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <input class="input100 @error('password') is-invalid @enderror" type="password" name="password"
                            placeholder="Password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="submit">
                            Login
                        </button>
                    </div>

                    <div class="text-center p-t-12">

                    </div>

                    <div class="text-center p-t-136">

                    </div>
                </form>
            </div>
        </div>
    </div>
    @if (session('notification'))
        <script>
            $(document).ready(function() {
                const {
                    title,
                    text,
                    type
                } = @json(session('notification'));
                Swal.fire(title, text, type);
            });
        </script>
    @endif
@endsection
