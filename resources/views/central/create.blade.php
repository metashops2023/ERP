@extends('layout.app')
@section('title')
    Add Tenant -
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
                    <div class="card-header">{{ __('Add Tenant') }}</div>

                    <div class="card-body">
                        <form action="{{ route('tenant.store') }}" method="post">
                            @csrf
                            <p><b>Name:</b> <input name="name" class="form-control" placeholder="Name"></p>
                            <p><b>Plan:</b> <select name="plan">
                                    <option>Free</option>
                                    <option>30 Days</option>
                                    <option>12 Months</option>
                                </select></p>
                            <p><b>Registration Date:</b> <input name="registrationdate" class="form-control"
                                    placeholder="Registration Date"></p>
                            <button type="submit" class="btn btn-primary btn-block">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
