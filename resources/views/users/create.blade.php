@extends('layout.master')
@push('stylesheets')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
<style>
    .form_element {
        border: 1px solid #7e0d3d;
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
        <form id="add_user_form" action="{{ route('users.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <section class="mt-5">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form_element m-0 mt-4">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>@lang('Add User')</h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Prefix') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="prefix" class="form-control" placeholder="@lang('Mr / Mrs / Miss')" autofocus>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><span class="text-danger">*
                                                    </span><b>@lang('First Name') :</b> </label>

                                                <div class="col-8">
                                                    <input type="text" name="first_name" class="form-control" placeholder="@lang('First Name')" id="first_name">
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
                                                    <input type="text" name="last_name" class="form-control" placeholder="@lang('Last Name')">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><span class="text-danger">*</span> <b>@lang('Email') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="email" id="email" class="form-control" placeholder="@lang('exmple@email.com')">
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
                                                <input type="checkbox" checked name="allow_login" id="allow_login">
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
                                                        <input type="text" name="username" id="username" class="form-control " placeholder="@lang('Username')" autocomplete="off">
                                                        <span class="error error_username"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"> <span class="text-danger">*</span> <b>@lang('Role') :</b> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Admin has access to all branch." class="fas fa-info-circle tp"></i> </label>
                                                    <div class="col-8">
                                                        <select name="role_id" id="role_id" class="form-control">
                                                            <option value="">@lang('Select Role')</option>
                                                        </select>
                                                        <span class="error error_role_id"></span>
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
                                        <div class="col-md-6 access_branch" style="display:none;">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Access Location') :</b> </label>
                                                <div class="col-8">
                                                    <select name="branch_id" id="branch_id" class="form-control">
                                                        <!-- <option value="">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option> -->
                                                        <option value="">@lang('Select shop')</option>
                                                        @foreach ($branches as $b)
                                                        <option value="{{ $b->id }}">{{$b->name.'/'.$b->branch_code}}</option>
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
                                                        @foreach ($branches as $b)
                                                        <option value="{{ $b->id }}">{{$b->name.'/'.$b->branch_code}}</option>
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
                                                    <input type="text" name="sales_commission_percent" class="form-control" placeholder="@lang('Sales Commission Percentage (%)')" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Max Discount')(%) : </b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="max_sales_discount_percent" class="form-control" placeholder="@lang('Max sales discount percent')" autocomplete="off">
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
                                                    <input type="text" name="date_of_birth" id="date_of_birth" class="form-control" autocomplete="off" placeholder="@lang('Date of birth')">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Gender') :</b> </label>
                                                <div class="col-8">
                                                    <select name="gender" class="form-control">
                                                        <option value="">@lang('Select Gender')</option>
                                                        <option value="Male">@lang('Male')</option>
                                                        <option value="Female">@lang('Female')</option>
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
                                                        <option value="Married">@lang('Married')</option>
                                                        <option value="Unmarried">@lang('Unmarried')</option>
                                                        <option value="Divorced">@lang('Divorced')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Blood Group') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="blood_group" class="form-control" placeholder="@lang('Blood group')" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Phone') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="phone" class="form-control" autocomplete="off" placeholder="@lang('Phone number')">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Facebook Link') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="facebook_link" class="form-control" autocomplete="off" placeholder="@lang('Facebook link')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Twitter Link') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="twitter_link" class="form-control" autocomplete="off" placeholder="@lang('Twitter link')">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Instagram Link') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="instagram_link" class="form-control" autocomplete="off" placeholder="@lang('Instagram link')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Guardian Name'):</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="guardian_name" class="form-control" autocomplete="off" placeholder="@lang('Guardian name')">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('ID Proof Name') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="id_proof_name" class="form-control" autocomplete="off" placeholder="@lang('ID proof name')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('ID Proof Number') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="id_proof_number" class="form-control" autocomplete="off" placeholder="@lang('ID proof number')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-2"><b>@lang('Permanent Address') :</b> </label>
                                                <div class="col-10">
                                                    <input type="text" name="permanent_address" class="form-control form-control-sm" autocomplete="off" placeholder="@lang('Permanent address')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-2"><b>@lang('Current Address') :</b> </label>
                                                <div class="col-10">
                                                    <input type="text" name="current_address" class="form-control form-control-sm" placeholder="@lang('Current address')">
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
                                                    <input type="text" name="bank_ac_holder_name" class="form-control " placeholder="@lang('Account holder\'s name')" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Account No') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="bank_ac_no" class="form-control" placeholder="@lang('Account number')" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Bank Name') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="bank_name" class="form-control" placeholder="@lang('Bank name')" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Identifier Code') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="bank_identifier_code" class="form-control" placeholder="@lang('Bank identifier code')" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Branch') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="bank_branch" class="form-control" placeholder="@lang('Branch')" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Tax Payer ID') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="tax_payer_id" class="form-control" placeholder="@lang('Tax payer ID')" autocomplete="off">
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
                                    <p class="px-1 pt-1 pb-0 text-primary">@lang('Human Resource Details')</p>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('Employee ID') :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" class="form-control" name="emp_id" placeholder="@lang('Employee ID')">
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
                                                        <option value="{{ $shift->id }}">{{ $shift->shift_name }}</option>
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
                                                        <option value="{{ $department->id }}">{{ $department->department_name }}</option>
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
                                                        <option value="{{ $designation->id }}">{{ $designation->designation_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"> <b>@lang('Salary') :</b> </label>
                                                <div class="col-8">
                                                    <input type="number" step="any" name="salary" id="salary" class="form-control" placeholder="@lang('Salary Amount')" autocomplete="off">
                                                    <span class="error error_salary"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"> <b>@lang('Pay Type') :</b> </label>
                                                <div class="col-8">
                                                    <select name="pay_type" class="form-control" id="pay_type">
                                                        <option value="">@lang('Select Pay type')</option>
                                                        <option value="Monthly">@lang('Monthly')</option>
                                                        <option value="Yearly">@lang('Yearly')</option>
                                                        <option value="Daliy">@lang('Daliy')</option>
                                                        <option value="Hourly">@lang('Hourly')</option>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    function setRoles() {
        $.ajax({
            url: "{{ route('users.all.roles') }}",
            success: function(roles) {
                $.each(roles, function(key, val) {
                    $('#role_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                });
            }
        });
    }
    setRoles();
    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
    new Litepicker({
        singleMode: true,
        element: document.getElementById('date_of_birth'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: _expectedDateFormat,
    });
    // Add user by ajax
    $(document).on('submit', '#add_user_form', function(e) {
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

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    $('#allow_login').on('click', function() {
        $selected_role = $("#role_id option:selected").text();
        if ($(this).is(':CHECKED', true) && $selected_role == "User") {
            $('.access_branch').show();
            $('.belonging_branch').hide();
        } else if ($selected_role == "Vendor") {
            $('.access_branch').hide();
            $('.belonging_branch').hide();
        }

        if ($(this).is(':CHECKED', true)) {
            $('.auth_field_area').show();
        } else {
            $('.auth_field_area').hide();
        }
    });

    $("#role_id").on("change", function() {
        if ($("#role_id option:selected").text() == "User") {
            $('.access_branch').show();
            $('.belonging_branch').hide();
        } else {
            $('.access_branch').hide();
            $('.belonging_branch').hide();
        }
    })
</script>
@endpush
