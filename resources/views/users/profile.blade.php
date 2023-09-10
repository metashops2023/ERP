@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
        b {font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
        @media only screen and (max-width: 770px) {.sm-mt-65 {margin-top: 65px !important;}.row {--bs-gutter-x: 0.5rem !important;}}
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <section class="mt-5">
                <div class="container-fluid">
                    <div class="row sm-mt-65">
                        <div class="col-md-8 pe-1">
                            <div class="form_element m-0 mt-4">
                                <div class="section-header">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5>@lang('Change Password')</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form id="reset_password_form" action="{{ route('password.updateCurrent') }}"
                                    method="post">
                                    @csrf
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-2"><span
                                                            class="text-danger">*</span> <b>@lang('Current Password') :</b> </label>
                                                    <div class="col-10">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i
                                                                        class="fas fa-lock input_i"></i></span>
                                                            </div>
                                                            <input type="password" name="current_password"
                                                                class="form-control" autocomplete="off"
                                                                placeholder="@lang('Current password')">
                                                        </div>
                                                        <span class="error error_password"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-2"><span
                                                            class="text-danger">*</span> <b>@lang('New Password') :</b> </label>
                                                    <div class="col-10">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i
                                                                        class="fas fa-lock input_i"></i></span>
                                                            </div>
                                                            <input type="password" name="password" class="form-control"
                                                                autocomplete="off" placeholder="@lang('New password')">
                                                        </div>
                                                        <span class="error error_password"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-2"><span
                                                            class="text-danger">*</span> <b>@lang('Confirm password') :</b> </label>
                                                    <div class="col-10">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i
                                                                        class="fas fa-lock input_i"></i></span>
                                                            </div>
                                                            <input type="password" name="password_confirmation"
                                                                class="form-control" autocomplete="off"
                                                                placeholder="@lang('Confirm new password')">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="button-area mt-2">
                                            <button type="button" class="btn loading_button d-none"><i
                                                    class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                            <button class="btn btn-sm btn-primary submit_button float-end">@lang('Save')</button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>

                        <form id="update_profile_form" action="{{ route('users.profile.update') }}" method="POST">
                            @csrf
                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="px-1 pt-1 pb-0 text-primary"><b>@lang('Update Profile')</b> </p>
                                    </div>

                                    <div class="element-body" style="margin-top:400px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-3"><b>@lang('Prefix') :</b> </label>
                                                    <div class="col-9">
                                                        <input type="text" name="prefix" class="form-control"
                                                            placeholder="Mr / Mrs / Miss"
                                                            value="{{ auth()->user()->prefix }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><span class="text-danger">*
                                                        </span><b>@lang('First Name') :</b> </label>

                                                    <div class="col-8">
                                                        <input type="text" name="first_name" class="form-control"
                                                            placeholder="@lang('First Name')" id="first_name"
                                                            value="{{ auth()->user()->name }}">
                                                        <span class="error error_first_name"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Last Name') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="last_name" class="form-control"
                                                            placeholder="Last Name"
                                                            value="{{ auth()->user()->last_name }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><span
                                                            class="text-danger">*</span> <b>@lang('Email') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="email" id="email" class="form-control"
                                                            placeholder="exmple@email.com"
                                                            value="{{ auth()->user()->email }}">
                                                        <span class="error error_email"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Default Language') :</b>
                                                    </label>
                                                    <div class="col-8">
                                                        <select name="language" class="form-control">
                                                            <option
                                                                {{ auth()->user()->language == 'en' ? 'SELECTED' : '' }}
                                                                value="en">
                                                                @lang('English')</option>
                                                            <option
                                                                {{ auth()->user()->language == 'bn' ? 'SELECTED' : '' }}
                                                                value="bn">
                                                                @lang('Bangla')</option>
                                                        </select>
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

                                    <div class="element-body" style="margin-top: 100px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"> <b>@lang('Date of birth') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="date_of_birth" class="form-control"
                                                            autocomplete="off" placeholder="Date of birth"
                                                            value="{{ auth()->user()->date_of_birth }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Gender') :</b> </label>
                                                    <div class="col-8">
                                                        <select name="gender" class="form-control">
                                                            <option value="">@lang('Select Gender')</option>
                                                            <option
                                                                {{ auth()->user()->gender == 'Male' ? 'SELECTED' : '' }}
                                                                value="Male">Male
                                                            </option>
                                                            <option
                                                                {{ auth()->user()->gender == 'Female' ? 'SELECTED' : '' }}
                                                                value="Female">
                                                                @lang('Female')</option>
                                                            <option
                                                                {{ auth()->user()->gender == 'Others' ? 'SELECTED' : '' }}
                                                                value="Others">
                                                                @lang('Others')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Marital Status') :</b> </label>
                                                    <div class="col-8">
                                                        <select name="marital_status" class="form-control">
                                                            <option value="">@lang('Marital Status')</option>
                                                            <option
                                                                {{ auth()->user()->marital_status == 'Married' ? 'SELECTED' : '' }}
                                                                value="Married">@lang('Married')</option>
                                                            <option
                                                                {{ auth()->user()->marital_status == 'Unmarried' ? 'SELECTED' : '' }}
                                                                value="Unmarried">@lang('Unmarried')</option>
                                                            <option
                                                                {{ auth()->user()->marital_status == 'Divorced' ? 'SELECTED' : '' }}
                                                                value="Divorced">@lang('Divorced')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Blood Group') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="blood_group" class="form-control"
                                                            placeholder="@lang('Blood group')" autocomplete="off"
                                                            value="{{ auth()->user()->blood_group }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Phone') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="phone" class="form-control"
                                                            autocomplete="off" placeholder="Phone number"
                                                            value="{{ auth()->user()->phone }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Facebook Link') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="facebook_link" class="form-control"
                                                            autocomplete="off" placeholder="Facebook link"
                                                            value="{{ auth()->user()->facebook_link }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Twitter Link') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="twitter_link" class="form-control"
                                                            autocomplete="off" placeholder="Twitter link"
                                                            value="{{ auth()->user()->twitter_link }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Instagram Link') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="instagram_link" class="form-control"
                                                            autocomplete="off" placeholder="Instagram link"
                                                            value="{{ auth()->user()->instagram_link }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Guardian Name'):</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="guardian_name" class="form-control"
                                                            autocomplete="off" placeholder="Guardian name"
                                                            value="{{ auth()->user()->guardian_name }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('ID Proof Name') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="id_proof_name" class="form-control"
                                                            autocomplete="off" placeholder="ID proof name"
                                                            value="{{ auth()->user()->id_proof_name }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('ID Proof Number') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="id_proof_number" class="form-control"
                                                            autocomplete="off" placeholder="ID proof number"
                                                            value="{{ auth()->user()->id_proof_number }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-2"><b>@lang('Permanent Address') :</b>
                                                    </label>
                                                    <div class="col-10">
                                                        <input type="text" name="permanent_address"
                                                            class="form-control form-control-sm" autocomplete="off"
                                                            placeholder="Permanent address"
                                                            {{ auth()->user()->permanent_address }}>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-2"><b>@lang('Current Address') :</b> </label>
                                                    <div class="col-10">
                                                        <input type="text" name="current_address"
                                                            class="form-control form-control-sm"
                                                            placeholder="Current address"
                                                            {{ auth()->user()->current_address }}>
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

                                    <div class="element-body" style="margin-top:444px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Account Name') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="bank_ac_holder_name" class="form-control "
                                                            placeholder="@lang("Account holder's name")" autocomplete="off"
                                                            value="{{ auth()->user()->bank_ac_holder_name }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Account No') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="bank_ac_no" class="form-control"
                                                            placeholder="@lang('Account number')" autocomplete="off"
                                                            value="{{ auth()->user()->bank_ac_no }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Bank Name') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="bank_name" class="form-control"
                                                            placeholder="@lang('Bank name')" autocomplete="off"
                                                            value="{{ auth()->user()->bank_name }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Identifier Code') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="bank_identifier_code" class="form-control"
                                                            placeholder="@lang('Bank identifier code')" autocomplete="off"
                                                            value="{{ auth()->user()->bank_identifier_code }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Branch') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="bank_branch" class="form-control"
                                                            placeholder="@lang('Branch')" autocomplete="off"
                                                            value="{{ auth()->user()->bank_branch }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Tax Payer ID') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="tax_payer_id" class="form-control"
                                                            placeholder="@lang('Tax payer ID')" autocomplete="off"
                                                            value="{{ auth()->user()->tax_payer_id }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="submit-area py-3 mb-4">
                                    <button type="button" class="btn loading_button d-none"><i
                                            class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                    <button class="btn btn-sm btn-primary submit_button float-end">@lang('Save')</button>
                                </div>
                            </div>
                        </form>
                    </div>
            </section>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Add user by ajax
        $(document).on('submit', '#update_profile_form', function(e) {
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
                    window.location = "{{ route('dashboard.dashboard') }}";
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

        // Change password form submit by ajax
        $(document).on('submit', '#reset_password_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    $('.loading_button').hide();
                    if ($.isEmptyObject(data.errorMsg)) {
                        toastr.success(data.successMsg);
                        // window.location = "{{ route('login') }}";
                    } else {
                        toastr.error(data.errorMsg);
                        $('.error').html('');
                        $('.form-control').removeClass('is-invalid');
                    }
                },
                error: function(err) {
                    $('.loading_button').hide();
                    toastr.error("@lang('Please check again all form fields.')",
                    "@lang('Something went wrong.')");
                    $('.error').html('');
                    $('.form-control').removeClass('is-invalid');
                    $.each(err.responseJSON.errors, function(key, error) {
                        $('.error_' + key + '').html(error[0]);
                        $('#' + key).addClass('is-invalid');
                    });
                }
            });
        });
    </script>
@endpush
