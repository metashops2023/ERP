@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
    </style>
@endpush
{{-- @section('title', 'Sale Settings - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="row mt-5 px-3">
            <div class="border-class">
                <div class="row mt-3">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>@lang('Add Sale Settings')</h6>
                                </div>
                            </div>

                            <form id="sale_settings_form" class="setting_form p-3" action="{{ route('sales.add.sale.settings.store') }}" method="post">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label><strong>@lang('Default Sale Discount') :</strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-percent text-dark input_f"></i></span>
                                            </div>
                                            <input type="text" name="default_sale_discount" class="form-control"
                                                autocomplete="off" value="{{ json_decode($generalSettings->sale, true)['default_sale_discount'] }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label><strong>@lang('Default Sale Tax') :</strong></label>
                                        <select name="default_tax_id" class="form-control">
                                            <option value="null">@lang('None')</option>
                                            @foreach ($taxes as $tax)
                                                <option
                                                    {{ json_decode($generalSettings->sale, true)['default_tax_id'] == $tax->tax_percent ? 'SELECTED' : '' }}
                                                    value="{{ $tax->tax_percent }}">{{ $tax->tax_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label><strong>@lang('Sales Commission Agent'):</strong></label>
                                        <select class="form-control" name="sales_cmsn_agnt">
                                            <option {{ json_decode($generalSettings->sale, true)['sales_cmsn_agnt'] == 'disable' ? 'SELECTED' : '' }}
                                                value="disable">Disable
                                            </option>

                                            <option {{ json_decode($generalSettings->sale, true)['sales_cmsn_agnt'] == 'logged_in_user' ? 'SELECTED' : '' }}
                                                value="logged_in_user">Logged in user
                                            </option>

                                            <option {{ json_decode($generalSettings->sale, true)['sales_cmsn_agnt'] == 'user' ? 'SELECTED' : '' }}
                                                value="user">Select from user&#039;s list
                                            </option>

                                            <option {{ json_decode($generalSettings->sale, true)['sales_cmsn_agnt'] == 'select_form_cmsn_list' ? 'SELECTED' : '' }}
                                                value="select_form_cmsn_list">Select from commission agent&#039;s list
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mt-1">
                                    <div class="col-md-4">
                                        <label><strong>@lang('Default Selling Price Group') :</strong></label>
                                        <select name="default_price_group_id" class="form-control">
                                            <option value="null">@lang('None')</option>
                                            @foreach ($price_groups as $pg)
                                                <option {{ json_decode($generalSettings->sale, true)['default_price_group_id'] == $pg->id ? 'SELECTED' : '' }} value="{{ $pg->id }}">{{ $pg->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
        $('#sale_settings_form').on('submit', function(e) {
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
