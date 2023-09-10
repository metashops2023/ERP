@extends('layout.app')
@section('title')
    Tenants -
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
                    <div class="card-header">{{ __('Tenants') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{-- {{ __('You are logged in!') }} --}}
                        @foreach ($tenants as $tenant)
                            <p><b>Tenant Id:</b> {{ $tenant->id }}</P>
                            <p><b>Databse:</b> {{ $tenant->tenancy_db_name ?? '' }}</P>
                            <p><b>Plan:</b> {{ $tenant->plan ?? '' }}</P>
                            <p><b>Registration Date:</b> {{ $tenant->registrationdate ?? '' }}</P>
                            <p><b>Status:</b> {!! $tenant->maintenance_mode == null ? '<span class="text-success">Active</span>' : '<span class="text-danger">Suspended</span>' !!}</P>
                            @foreach ($tenant->domains as $domain)
                                <p><b>Visit:</b> <a href="https://{{ $domain->domain }}"
                                        target="_blank">{{ $domain->domain }}</a></p>
                            @endforeach
                            <a href="{{ route('tenant.edit', [$tenant->id]) }}">Edit</a> |
                            <a href="{{ route('tenant.delete', [$tenant->id]) }}"
                                onclick='return confirm("Are you sure you want to delete tenant {{ $tenant->id }}")'>Delete</a>
                            |
                            @if ($tenant->maintenance_mode == null)
                                <a href="{{ route('tenant.suspend', [$tenant->id]) }}"
                                    onclick='return confirm("Are you sure you want to suspend tenant {{ $tenant->id }}")'>Suspend</a>
                            @else
                                <a href="{{ route('tenant.unsuspend', [$tenant->id]) }}"
                                    onclick='return confirm("Are you sure you want to unsuspend tenant {{ $tenant->id }}")'>Unsuspend</a>
                            @endif
                            <hr />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
