@extends('layout.app')
@section('title')
    Add User -
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
                    <div class="card-header">{{ __('Add User') }}</div>

                    <div class="card-body">
                        <form action="{{ route('tenant.saveuser') }}" method="post">
                            @csrf
                            <input type="hidden" name="name">
                            <input name="name" class="form-control" placeholder="Name">
                            <input name="email" class="form-control" placeholder="Email">
                            <input name="password" type="password" class="form-control" placeholder="Password">
                            <button type="submit" class="btn btn-primary btn-block">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
