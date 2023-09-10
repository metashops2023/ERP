@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="add_user_form" action="{{ route('users.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form_element m-0 mt-4">
                                    <div class="section-header">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5>@lang('View Profile')</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>@lang('Role Permission')</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p><b>@lang('User Name') :</b> {!! $user->username ? $user->username : '<span class="badge bg-secondary">__("Not-Allowed-to-Login")</span>' !!} </p>
                                                <p><b>@lang('Role') :</b>  
                                                    @if ($user->role_type == 1)
                                                        Super-Admin
                                                    @elseif($user->role_type == 2)  
                                                        Admin 
                                                    @elseif($user->role_type == 3)  
                                                        {{ $user->role->name }}
                                                    @else 
                                                        <span class="badge bg-warning">@lang('No-Role')</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>@lang('Basic Information')</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p><b>@lang('Fullname') :</b> {{ $user->prefix.' '.$user->name.' '.$user->last_name }} </p>
                                                <p><b>@lang('Email') :</b> {{ $user->email}} </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>@lang('Personal Information')</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p><b>@lang('Date of birth') :</b> {{ $user->date_of_birth }}</p>
                                                <p><b>@lang('Gender') :</b> {{ $user->gender }}</p>
                                                <p><b>@lang('Marital Status') :</b> {{ $user->marital_status }}</p>
                                                <p><b>@lang('Blood Group') : </b> {{ $user->blood_group }}</p>
                                                <p><b>@lang('Phone Number') : </b> {{ $user->phone }}</p>
                                                <p><b>@lang('ID proof name') : </b> {{ $user->id_proof_name }}</p>
                                                <p><b>@lang('ID proof Number') : </b> {{ $user->id_proof_number }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>@lang('Other Information')</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p><b>@lang('Guardian Name') :</b> {{ $user->guardian_name }}</p>
                                                <p><b>@lang('Facebook Link') :</b> {{ $user->facebook_link }}</p>
                                                <p><b>@lang('Twitter Link') :</b> {{ $user->twitter_link }}</p>
                                                <p><b>@lang('Instagram Link') :</b> {{ $user->instagram_link }}</p>
                                                <p><b>@lang('Custom Field') 1 :</b> {{ $user->custom_field_1 }}</p>
                                                <p><b>@lang('Permanent Address')  :</b> {{ $user->permanent_address }}</p>
                                                <p><b>@lang('Current Address')  :</b> {{ $user->current_address }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>@lang('Bank Information')</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p><b>@lang('Account Holder')'s Name :</b> {{ $user->bank_ac_holder_name }}</p>
                                                <p><b>@lang('Account No') :</b> {{ $user->bank_ac_no }}</p>
                                                <p><b>@lang('Bank Name') :</b> {{ $user->bank_name }}</p>
                                                <p><b>@lang('Bank Identifier Code') :</b> {{ $user->bank_identifier_code }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($addons->hrm == 1)
                                <div class="col-md-6">
                                    <div class="form_element m-0 mt-2">
                                        <div class="heading_area">
                                            <p class="px-1 pt-1 text-primary"><b>@lang('HRM Details')</b> </p>
                                        </div>

                                        <div class="element-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p><b>@lang('Department') :</b> {{$user->department ? $user->department->department_name : 'N/A'}}</p>
                                                    <p><b>@lang('Designation') :</b> {{$user->designation ? $user->designation->designation_name : 'N/A'}}</p>
                                                    <p><b>@lang('Salery') : </b> {{ $user->salary }} </p>
                                                    <p><b>@lang('Pay Type') : </b>{{ $user->salary_type }} </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>

@endsection
@push('scripts')

@endpush