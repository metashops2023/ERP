@extends('layout.app')

{{-- @section('title', 'Login - ') --}}

    @push('css')

    @endpush

@section('content')
    {{-- <div class="form-wraper" style="background: #448aff"> --}}
    <div class="form-wraper">
        <div class="container">
            <div class="form-content">
                <div class="inner-div col-lg-7">
                    <div class="border-div">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-head">
                                    <div class="head p-1">
                                        <img src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}"
                                            alt="POS" class="logo">
                                        <span class="head-text">
                                            MetaShops, Point of Sale software by MetaShops
                                        </span>
                                    </div>
                                </div>

                                <div class="main-form">
                                    <div class="form-title">
                                        <p>@lang('User Login')</p>
                                    </div>
                                    <form action="" method="POST">
                                        @csrf
                                        <div class="left-inner-addon input-container">
                                            <i class="fa fa-key"></i>
                                            <input name="pin" type="Password"
                                                class="form-control form-st rounded-bottom" placeholder="Pin Number"
                                                required />
                                        </div>
                                        @if (Session::has('errorMsg'))
                                            <div class="bg-danger p-3 mt-4">
                                                <p class="text-white">
                                                    {{ session('errorMsg') }}
                                                </p>
                                            </div>
                                        @endif
                                        <button type="submit" class="submit-button">@lang('Login')</button>
                                    </form>
                                    <div class="login_opt_link">

                                        <a class="forget-pw" href="">
                                            &nbsp; {{ __('Forgot Your Pin Number?') }}
                                        </a>

                                        {{-- <div class="form-group cx-box">
                                            <input type="checkbox" id="remembar" class="form-control">
                                            <label for="remembar">@lang('Remembar me')</label>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-head addr">
                                    <div class="head addr-t pt-4">
                                        {{-- <h2>
                                            Genuine Point Of Sale
                                        </h2> --}}
                                        <div class="px-2">
                                            <p class="logo-main-sec"><img
                                                    src="{{ asset('assets/images/metashops_logo.png') }}" alt="POS"
                                                    class="logo">
                                            </p>
                                            <p class="details"><span>@lang('Address'):</span> @lang('Motijheel Arambagh, Dhaka')</p>
                                            <p class="details"><span>@lang('Support'):</span> @lang('support')@MetaShops.com</p>
                                            <p class="details"><span>@lang('Website'):</span> @lang('www').MetaShops.com.sa</p>

                                            <div class="function-btn">
                                                <span class="btn-fn">P</span>
                                                <span class="btn-fn">O</span>
                                                <span class="btn-fn">S</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')

@endpush
