@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
    </style>
@endpush
{{-- @section('title', 'Purchase Settings - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="row mt-5 px-3">
            <div class="border-class">
                <div class="row mt-3">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>@lang('Purchase Settings')</h6>
                                </div>
                            </div>

                            <form id="purchase_settings_form" class="setting_form p-3" action="{{ route('purchase.settings.store') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <div class="setting_form_heading">
                                        <h6 class="text-primary">@lang('Purchase Settings')</h6>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-7">
                                        <div class="row mt-2">
                                            <p class="checkbox_input_wrap">
                                                <input type="checkbox"
                                                    {{ json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1' ? 'CHECKED' : '' }}
                                                    name="is_edit_pro_price"> &nbsp; <b>@lang('Enable editing  product price from purchase screen')</b>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="row mt-2">
                                            <p class="checkbox_input_wrap">
                                                <input type="checkbox"
                                                    {{ json_decode($generalSettings->purchase, true)['is_enable_status'] == '1' ? 'CHECKED' : '' }}
                                                    name="is_enable_status"> &nbsp; <b>@lang('Enable Purchase Status')</b>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <div class="row mt-2">
                                            <p class="checkbox_input_wrap">
                                                <input type="checkbox"
                                                    {{ json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1' ? 'CHECKED' : '' }}
                                                    name="is_enable_lot_no"> &nbsp; <b>@lang('Enable Lot number')</b>
                                            </p>
                                        </div>
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
         $('#purchase_settings_form').on('submit', function(e) {
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
