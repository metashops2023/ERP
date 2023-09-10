@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block; margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray; padding: 1px 5px; border-radius: 3px; font-size: 11px;}
    </style>
@endpush
{{-- @section('title', 'Email Settings - ') --}}
@section('content')
<div class="body-woaper">
    <div class="row mt-5 px-3">
        <div class="border-class">
            <div class="row mt-3">
                <div class="col-md-8">
                    <div class="card ms-1">
                        <div class="section-header">
                            <div class="col-md-6">
                                <h6>@lang('Email Settings')</h6>
                            </div>
                        </div>

                        <form id="email_settings_form" class="setting_form p-3" action="{{ route('communication.email.settings.store') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="setting_form_heading">
                                    <h6 class="text-primary">@lang('Email Settings')</h6>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label><strong>@lang('MAIL MAILER') : </strong></label>
                                    <input type="text" name="MAIL_MAILER" class="form-control es_input"
                                        placeholder="@lang('MAIL MAILER')" autocomplete="off"
                                        value="{{ env('MAIL_MAILER') }}">
                                </div>

                                <div class="col-md-4">
                                    <label><strong>@lang('MAIL HOST') :</strong></label>
                                    <input type="text" name="MAIL_HOST" class="form-control es_input"
                                        placeholder="@lang('MAIL HOST')" autocomplete="off"
                                        value="{{ env('MAIL_HOST') }}">
                                </div>

                                <div class="col-md-4">
                                    <label><strong>@lang('MAIL PORT') :</strong></label>
                                    <input type="text" name="MAIL_PORT" class="form-control  es_input"
                                        placeholder="@lang('MAIL PORT')" autocomplete="off"
                                        value="{{ env('MAIL_PORT') }}">
                                </div>
                            </div>

                            <div class="form-group row mt-1">
                                <div class="col-md-4">
                                    <label><strong>@lang('MAIL_USERNAME') :</strong></label>
                                    <input type="text" name="MAIL_USERNAME" class="form-control es_input"
                                        placeholder="@lang('MAIL USERNAME')" autocomplete="off"
                                        value="{{ env('MAIL_USERNAME') }}">
                                </div>

                                <div class="col-md-4">
                                    <label><strong>@lang('MAIL PASSWORD') :</strong></label>
                                    <input type="text" name="MAIL_PASSWORD" class="form-control es_input"
                                        placeholder="@lang('MAIL PASSWORD')" autocomplete="off"
                                        value="{{ env('MAIL_PASSWORD') }}">
                                </div>

                                <div class="col-md-4">
                                    <label><strong>@lang('MAIL ENCRYPTION') :</strong></label>
                                    <input type="text" name="MAIL_ENCRYPTION" class="form-control  es_input"
                                        placeholder="@lang('MAIL ENCRYPTION')" autocomplete="off"
                                        value="{{ env('MAIL_ENCRYPTION') }}">
                                </div>
                            </div>

                            <div class="form-group row mt-1">
                                <div class="col-md-4">
                                    <label><strong>@lang('MAIL FROM ADDRESS') :</strong></label>
                                    <input type="text" name="MAIL_FROM_ADDRESS" class="form-control es_input"
                                        placeholder="@lang('MAIL FROM ADDRESS')" autocomplete="off"
                                        value="{{ env('MAIL_FROM_ADDRESS') }}">
                                </div>

                                <div class="col-md-4">
                                    <label><strong>@lang('MAIL FROM NAME') :</strong></label>
                                    <input type="text" name="MAIL_FROM_NAME" class="form-control es_input"
                                        placeholder="@lang('MAIL FROM NAME')" autocomplete="off"
                                        value="{{ env('MAIL_FROM_NAME') }}">
                                </div>

                                <div class="col-md-4 mt-1">
                                    <div class="row mt-4">
                                        <p class="checkbox_input_wrap">
                                            <input type="checkbox"
                                                {{  env('MAIL_ACTIVE') == 'true' ? 'CHECKED' : '' }}
                                                name="MAIL_ACTIVE" autocomplete="off"> &nbsp; <b>@lang('Is Active')</b>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mt-1">

                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12 text-end">
                                    <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                    <button class="btn btn-sm btn-success submit_button float-end">@lang('Save Change')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
    <script>
         $('#email_settings_form').on('submit', function(e) {
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
                }
            });
        });
    </script>
@endpush
