@extends('layout.app')
@section('title')
    Edit Tenant -
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
                    <div class="card-header">{{ __('Edit Tenant') }}</div>

                    <div class="card-body">
                        <form action="{{ route('tenant.update') }}" method="post">
                            @csrf
                            <p><b>Tenant Id:</b> {{ $tenant->id }}</p>
                            <input type="hidden" name="name" value="{{ $tenant->id }}">
                            <p><b>Plan:</b> <select name="plan">
                                    <option {{ $tenant->plan == 'Free' ? 'selected' : '' }}>Free</option>
                                    <option {{ $tenant->plan == '30 Days' ? 'selected' : '' }}>30 Days</option>
                                    <option {{ $tenant->plan == '12 Months' ? 'selected' : '' }}>12 Months</option>
                                </select></p>
                            <p><b>Registration Date:</b> <input name="registrationdate" class="form-control"
                                    placeholder="Registration Date" value="{{ $tenant->registrationdate }}">
                            </p>
                            <button type="submit" class="btn btn-primary btn-block">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
