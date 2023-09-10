@extends('layout.app')
{{-- @section('title', 'Reset Password - ') --}}
@section('content')
    <div class="form-wraper">
        <div class="container">
            <div class="form-content">
                <div class="col-lg-4 col-md-5 col-12">
                    <div class="form-head">
                        <div class="head">
                            <img src="{{ asset('assets/images/metashops_logo.png') }}" alt="" class="logo">
                            <span class="head-text">
                                MetaShops, Point of Sale Software By Meta Shops
                            </span>
                        </div>
                    </div>
                    {{-- Alert --}}
                    <div>
                        @if (session('status'))
                            <div class="bg-success p-3 mt-4 mx-2">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>

                    <div class="main-form">
                        <div class="form-title">
                            <p>@lang('Reset Password')</p>
                        </div>
                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="left-inner-addon input-container">
                                <i class="fa fa-envelope"></i>
                                <input type="email" name="email" class="form-control form-st" value="{{ $email ?? old('email') }}"
                                placeholder="Enter Your Email"
                                required autocomplete="email" />
                            </div>

                            <div class="left-inner-addon input-container">
                                <i class="fa fa-lock"></i>

                                <input id="password" type="password" class="form-control form-st"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="New Password"
                                autofocus>
                            </div>

                            <div class="left-inner-addon input-container">
                                <i class="fa fa-check-double"></i>

                                <input id="password_confimation" type="password" class="form-control form-st rounded-bottom"
                                    name="password_confirmation" required placeholder="Confirm Password">
                            </div>

                            {{-- Custom errror message --}}
                            @if (Session::has('errorMsg'))
                                <div class="bg-danger mt-4 mx-2">
                                    <p class="text-white">
                                        {{ session('errorMsg') }}
                                    </p>
                                </div>
                            @endif
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="bg-danger p-4 mt-4">
                                        <p class="text-white">
                                            {{ $error }}
                                        </p>
                                    </div>
                                @endforeach
                            @endif
                            {{-- Custom errror message --}}
                            <button type="submit" class="submit-button">
                                {{ __('Send Password Reset Link') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
