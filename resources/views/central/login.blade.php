@extends('layout.app')
@section('title')
    Login -
@endsection

@section('content')
    <div class="container">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="/" class="h1"><b>{{ config('app.name') }}</b></a>
            </div>
            <div class="card-body login-card-body">
                <div class="login-logo">
                    <a href="/"><img src="{{ asset('img/logo.png') }}" style="height:150px;display: block;margin-left: auto;margin-right: auto;" /></a>
                </div>
                <p class="login-box-msg">Sign in to start your session</p>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

                <form action="{{route('tenant.authenticate')}}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input name="email" class="form-control" placeholder="Email/Username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input name="password" type="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
