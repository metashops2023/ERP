@extends('layout.master')
@push('stylesheets')
<style>
    .form_element {
        border: 1px solid #7e0d3d;
    }

    label {
        font-size: 12px !important;
    }

    b {
        font-weight: 500;
        font-family: Arial, Helvetica, sans-serif;
    }
</style>
@endpush
@section('content')
<div class="body-woaper">
    <div class="container-fluid">
        <form id="update_user_form" action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            <section class="mt-5">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form_element m-0 mt-4">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>@lang('Edit User')</h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Prefix') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="prefix" class="form-control" placeholder="@lang('Mr / Mrs / Miss')" value="{{ $user->prefix }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><span class="text-danger">*
                                                    </span><b>@lang('First Name') :</b> </label>

                                                <div class="col-8">
                                                    <input type="text" name="first_name" class="form-control" placeholder="@lang('First Name')" id="first_name" value="{{ $user->name }}">
                                                    <span class="error error_first_name"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Last Name') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="last_name" class="form-control" placeholder="@lang('Last Name')" value="{{ $user->last_name }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><span class="text-danger">*</span> <b>@lang('Email') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="email" id="email" class="form-control" placeholder="@lang('exmple@email.com')" value="{{ $user->email }}">
                                                    <span class="error error_email"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form_element m-0 mt-2">
                                <div class="heading_area">
                                    <p class="p-1 text-primary"><b>@lang('Role Permission')</b> </p>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $user->allow_login == 1 ? 'CHECKED' : '' }} name="allow_login" id="allow_login">
                                                <b>@lang('Allow login')</b>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="auth_field_area">
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><span class="text-danger">*</span> <b>@lang('Username') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="username" id="username" class="form-control " placeholder="@lang('Username')" autocomplete="off" value="{{ $user->username }}">
                                                        <span class="error error_username"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <span class="text-danger">*</span> <b>@lang('Role') :</b> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Admin has access to all business location." class="fas fa-info-circle tp"></i></label>
                                                    <div class="col-8">
                                                        <select name="role_id" id="role_id" class="form-control">
                                                            <!-- <option {{ $user->role_type == 2 ? 'SELECTED' : '' }} value="">@lang('Admin')</option>
                                                            @foreach ($roles as $role) -->
                                                            <option value="">@lang('Select Role')</option>
                                                            <option {{ $user->role_id == $role->id ? 'SELECTED' : '' }} value="{{ $role->id }}">{{ $role->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><span class="text-danger">*</span> <b>@lang('Password') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="password" name="password" id="password" class="form-control" placeholder="@lang('Password')" autocomplete="off">
                                                        <span class="error error_password"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b><span class="text-danger">*</span> @lang('Confirm Pass') : </b> </label>
                                                    <div class="col-8">
                                                        <input type="password" name="password_confirmation" class="form-control" placeholder="@lang('Confirm password')" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row mt-2">
                                        @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                        <div class="col-md-6 access_branch" style="display: none;">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Access Location') :</b> </label>
                                                <div class="col-8">
                                                    <select name="branch_id" id="branch_id" class="form-control">
                                                        <option value="">@lang('Select Business Location')</option>
                                                        <!-- <option {{ $user->branch_id == NULL ? 'SELECTED' : '' }} value="head_office">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option> -->
                                                        @foreach ($branches as $branch)
                                                        <option {{ $user->branch_id == $branch->id ? 'SELECTED' : '' }} value="{{ $branch->id }}">{{ $branch->name.' - '.$branch->branch_code }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_branch_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 belonging_branch d-none">
                                            <div class="input-group">
                                                <label class="col-4"><span class="text-danger">*</span> <b>@lang('Belonging Location') :</b> </label>
                                                <div class="col-8">
                                                    <select name="belonging_branch_id" id="belonging_branch_id" class="form-control">
                                                        <option value="head_office">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                        @foreach ($branches as $branch)
                                                        <option {{ $user->branch_id == $branch->id ? 'SELECTED' : '' }} value="{{ $branch->id }}">{{ $branch->name.' - '.$branch->branch_code }}</option>
                                                        @endforeach
                                                    </select>

                                                    <span class="error error_belonging_branch_id"></span>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                                        <input type="hidden" name="belonging_branch_id" value="{{ auth()->user()->branch_id }}">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form_element m-0 mt-2">
                                <div class="heading_area">
                                    <p class="px-1 pt-1 pb-0 text-primary"><b>@lang('Sales')</b> </p>
                                </div>

                                <div class="element-body">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"> <b>@lang('Commission') (%) :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="sales_commission_percent" class="form-control" placeholder="@lang('Sales Commission Percentage (%)')" autocomplete="off" value="{{ $user->sales_commission_percent }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Max Discount')(%) : </b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="max_sales_discount_percent" class="form-control" placeholder="@lang('Max sales discount percent')" autocomplete="off" value="{{ $user->max_sales_discount_percent }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form_element m-0 mt-2">
                                <div class="heading_area">
                                    <p class="px-1 pt-1 pb-0 text-primary"><b>@lang('More Information')</b> </p>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"> <b>@lang('Date of birth') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="date_of_birth" class="form-control" autocomplete="off" placeholder="@lang('Date of birth')" value="{{ $user->date_of_birth }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Gender') :</b> </label>
                                                <div class="col-8">
                                                    <select name="gender" class="form-control">
                                                        <option value="">@lang('Select Gender')</option>
                                                        <option {{ $user->gender == 'Male' ? 'SELECTED' : '' }} value="Male">@lang('Male')</option>
                                                        <option {{ $user->gender == 'Female' ? 'SELECTED' : '' }} value="Female">@lang('Female')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Marital Status') :</b> </label>
                                                <div class="col-8">
                                                    <select name="marital_status" class="form-control">
                                                        <option value="">@lang('Marital Status')</option>
                                                        <option {{ $user->marital_status == 'Married' ? 'SELECTED' : '' }} value="Married">@lang('Married')</option>
                                                        <option {{ $user->marital_status == 'Unmarried' ? 'SELECTED' : '' }} value="Unmarried">@lang('Unmarried')</option>
                                                        <option {{ $user->marital_status == 'Divorced' ? 'SELECTED' : '' }} value="Divorced">@lang('Divorced')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Blood Group') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="blood_group" class="form-control" placeholder="@lang('Blood group')" autocomplete="off" value="{{ $user->blood_group }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Phone') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="phone" class="form-control" autocomplete="off" placeholder="@lang('Phone number')" value="{{ $user->phone }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Facebook Link') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="facebook_link" class="form-control" autocomplete="off" placeholder="@lang('Facebook link')" value="{{$user->facebook_link }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Twitter Link') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="twitter_link" class="form-control" autocomplete="off" placeholder="@lang('Twitter link')" value="{{ $user->twitter_link }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Instagram Link') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="instagram_link" class="form-control" autocomplete="off" placeholder="@lang('Instagram link')" value="{{ $user->instagram_link }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Guardian Name'):</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="guardian_name" class="form-control" autocomplete="off" placeholder="@lang('Guardian name')" value="{{ $user->guardian_name }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('ID Proof Name') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="id_proof_name" class="form-control" autocomplete="off" placeholder="@lang('ID proof name')" value="{{ $user->id_proof_name }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('ID Proof Number') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="id_proof_number" class="form-control" autocomplete="off" placeholder="@lang('ID proof number')" value="{{ $user->id_proof_number }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-2"><b>@lang('Permanent Address') :</b> </label>
                                                <div class="col-10">
                                                    <input type="text" name="permanent_address" class="form-control form-control-sm" autocomplete="off" placeholder="@lang('Permanent address')" value="{{ $user->permanent_address }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-2"><b>@lang('Current Address') :</b> </label>
                                                <div class="col-10">
                                                    <input type="text" name="current_address" class="form-control form-control-sm" placeholder="@lang('Current address')" value="{{ $user->current_address }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form_element m-0 mt-2">
                                <div class="heading_area">
                                    <p class="px-1 pt-1 pb-0 text-primary"><b>@lang('Bank Details')</b> </p>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Account Name') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="bank_ac_holder_name" class="form-control " placeholder="@lang("Account holder's name")" autocomplete="off" value="{{ $user->bank_ac_holder_name }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Account No') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="bank_ac_no" class="form-control" placeholder="@lang('Account number')" autocomplete="off" value="{{ $user->bank_ac_no }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Bank Name') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="bank_name" class="form-control" placeholder="@lang('Bank name')" autocomplete="off" value="{{ $user->bank_name }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Identifier Code') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="bank_identifier_code" class="form-control" placeholder="@lang('Bank identifier code')" autocomplete="off" value="{{ $user->bank_identifier_code }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Branch') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="bank_branch" class="form-control" placeholder="@lang('Branch')" autocomplete="off" value="{{ $user->bank_branch }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Tax Payer ID') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="tax_payer_id" class="form-control" placeholder="@lang('Tax payer ID')" autocomplete="off" value="{{ $user->tax_payer_id }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($addons->hrm == 1)
                        <div class="col-md-8">
                            <div class="form_element m-0 mt-2">
                                <div class="heading_area">
                                    <p class="px-1 pt-1 pb-0 text-primary"><b>@lang('Human Resource Details')</b> </p>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Employee ID') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" class="form-control" name="emp_id" placeholder="@lang('Employee ID')" value="{{ $user->emp_id }}">
                                                    <span class="error error_emp_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Shift') :</b> </label>
                                                <div class="col-8">
                                                    <select name="shift_id" class="form-control">
                                                        @foreach ($shifts as $shift)
                                                        <option {{ $user->shift_id == $shift->id ? 'SELECTED' : '' }} value="{{ $shift->id }}">{{ $shift->shift_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_shift_id"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Department') :</b> </label>
                                                <div class="col-8">
                                                    <select name="department_id" class="form-control">
                                                        <option value="">@lang('Select Department')</option>
                                                        @foreach ($departments as $department)
                                                        <option {{ $user->department_id == $department->id ? 'SELECTED' : '' }} value="{{ $department->id }}">{{ $department->department_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_department_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Designation') :</b> </label>
                                                <div class="col-8">
                                                    <select name="designation_id" class="form-control">
                                                        <option value="">@lang('Select Designation')</option>
                                                        @foreach ($designations as $designation)
                                                        <option {{ $user->designation_id == $designation->id ? 'SELECTED' : '' }} value="{{ $designation->id }}">{{ $designation->designation_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><span class="text-danger">*</span> <b>@lang('Salary') :</b> </label>
                                                <div class="col-8">
                                                    <input type="number" step="any" name="salary" id="salary" class="form-control" placeholder="@lang('Salary Amount')" autocomplete="off" value="{{ $user->salary }}">
                                                    <span class="error error_salary"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><span class="text-danger">*</span> <b>@lang('Pay Type') :</b> </label>
                                                <div class="col-8">
                                                    <select name="pay_type" class="form-control" id="pay_type">
                                                        <option value="">@lang('Select Pay type')</option>
                                                        <option {{ $user->salary_type == 'Monthly' ? 'SELECTED' : '' }} value="Monthly">@lang('Monthly')</option>
                                                        <option {{ $user->salary_type == 'Yearly' ? 'SELECTED' : '' }} value="Yearly">@lang('Yearly')</option>
                                                        <option {{ $user->salary_type == 'Daliy' ? 'SELECTED' : '' }} value="Daliy">@lang('Daliy')</option>
                                                        <option {{ $user->salary_type == 'Hourly' ? 'SELECTED' : '' }} value="Hourly">@lang('Hourly')</option>
                                                    </select>
                                                    <span class="error error_pay_type"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="col-md-8">
                            <div class="submit-area py-3 mb-4">
                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                <button class="btn btn-sm btn-success submit_button float-end">@lang('Save')</button>
                            </div>
                        </div>
                    </div>
            </section>
        </form>
    </div>
</div>

@endsection
@push('scripts')
<script>
    // Add user by ajax
    $(document).on('submit', '#update_user_form', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                toastr.success(data);
                $('.loading_button').hide();
                window.location = "{{ route('users.index') }}";
            },
            error: function(err) {
                $('.loading_button').hide();
                toastr.error("@lang('Please check again all form fields.')",
                    "@lang('Something went wrong.')");
                $('.error').html('');
                $.each(err.responseJSON.errors, function(key, error) {
                    //console.log(key);
                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    if ($('#allow_login').is(':CHECKED', true)) {
        $('.auth_field_area').show();
        $('.access_branch').show();
        $('.belonging_branch').hide();
    } else {
        $('.auth_field_area').hide();
        $('.access_branch').hide();
        $('.belonging_branch').show();
    }

    $('#allow_login').on('click', function() {
        if ($(this).is(':CHECKED', true)) {
            $('.auth_field_area').show();
            $('.access_branch').show();
            $('.belonging_branch').hide();
        } else {
            $('.auth_field_area').hide();
            $('.access_branch').hide();
            $('.belonging_branch').show();
        }
    });
</script>
@endpush
