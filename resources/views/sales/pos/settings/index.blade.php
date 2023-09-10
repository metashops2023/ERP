@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
    </style>
@endpush
@section('title', 'Pos Settings - ')
@section('content')
    <div class="body-woaper">
        <div class="row mt-5 px-3">
            <div class="border-class">
                <div class="row mt-3">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>POS Settings</h6>
                                </div>
                            </div>
    
                            <form id="pos_settings_form" class="setting_form p-3"
                            action="{{ route('sales.pos.settings.store') }}" method="post">
                                @csrf
                            
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <div class="row ">
                                            <p class="checkbox_input_wrap mt-3">
                                                <input type="checkbox"
                                                    {{ json_decode($generalSettings->pos, true)['is_enabled_multiple_pay'] == '1' ? 'CHECKED' : '' }}
                                                    name="is_enabled_multiple_pay"> &nbsp; <b>Enable Multiple Pay</b> 
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-3">
                                                <input type="checkbox"
                                                    {{ json_decode($generalSettings->pos, true)['is_enabled_draft'] == '1' ? 'CHECKED' : '' }}
                                                    name="is_enabled_draft"> &nbsp; <b>Enable Draft</b> 
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-3">
                                                <input type="checkbox" {{ json_decode($generalSettings->pos, true)['is_enabled_quotation'] == '1' ? 'CHECKED' : '' }} name="is_enabled_quotation"> &nbsp; <b>Enable Quotation</b> 
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <div class="row ">
                                            <p class="checkbox_input_wrap mt-3">
                                                <input type="checkbox"
                                                    {{ json_decode($generalSettings->pos, true)['is_enabled_suspend'] == '1' ? 'CHECKED' : '' }}
                                                    name="is_enabled_suspend"> &nbsp; <b>Enable Suspend</b> 
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-3">
                                                <input type="checkbox" {{ json_decode($generalSettings->pos, true)['is_enabled_discount'] == '1' ? 'CHECKED' : '' }} name="is_enabled_discount"> &nbsp; <b>Enable Order Discount</b> 
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-3">
                                                <input type="checkbox"
                                                    {{ json_decode($generalSettings->pos, true)['is_enabled_order_tax'] == '1' ? 'CHECKED' : '' }} name="is_enabled_order_tax"> &nbsp; <b>Enable order tax</b> 
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <div class="row ">
                                            <p class="checkbox_input_wrap mt-3">
                                                <input type="checkbox"
                                                    {{ json_decode($generalSettings->pos, true)['is_show_recent_transactions'] == '1' ? 'CHECKED' : '' }} name="is_show_recent_transactions" autocomplete="off"> &nbsp; <b>Show recent transactions</b> 
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-3">
                                                <input type="checkbox"
                                                    {{ json_decode($generalSettings->pos, true)['is_enabled_credit_full_sale'] == '1' ? 'CHECKED' : '' }}
                                                    name="is_enabled_credit_full_sale"> &nbsp; <b>Enable Full Credit Sale </b> 
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-3">
                                                <input type="checkbox"
                                                    {{ json_decode($generalSettings->pos, true)['is_enabled_hold_invoice'] == '1' ? 'CHECKED' : '' }}
                                                    name="is_enabled_hold_invoice"> &nbsp; <b>Enable Hold Invoice</b> 
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-12 text-end">
                                        <button type="button" class="btn loading_button d-none"><i
                                            class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                        <button class="btn btn-sm btn-success submit_button float-end">Save Change</button>
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
        $('#pos_settings_form').on('submit', function(e) {
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
