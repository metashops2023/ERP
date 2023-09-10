@extends('layout.app')
@section('title')
    Users -
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div>
                <a href="{{ route('tenant.index') }}">Tenants</a> | 
                <a href="{{ route('tenant.create') }}">Add Tenant</a> | 
                <a href="{{ route('tenant.users') }}">Users</a> | 
                <a href="{{ route('tenant.adduser') }}">Add User</a> | 
                <a href="{{ route('tenant.logout') }}">Logout</a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Users') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{-- {{ __('You are logged in!') }} --}}
                        @foreach ($users as $user)
                            <p>{{ $user->email }}</P>
                            <a href="{{ route('tenant.edituser', [$user->id]) }}">Edit</a>
                            <a href="{{ route('tenant.deleteuser', [$user->id]) }}"
                                onclick='return confirm("Are you sure you want to delete user {{ $user->email }}")'>Delete</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
