@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
        label {font-size: 12px !important;}
        ul.menus_unorder_list {list-style: none;float: left;width: 100%;}
        ul.menus_unorder_list .menu_list {border: 1px solid lightgray;display: block;text-align: center;background: linear-gradient(#8c0437ee, #1e000d);}
        ul.menus_unorder_list .menu_list .menu_btn {color: white;padding: 6px 1px;display: block; font-size: 11px;}
        .menu_active {background: white;color: #504d4d!important;font-weight: 700;}
        .hide-all {
            display: none;
        }
    </style>
@endpush
@section('content')
    <div class="body-woaper mt-5">
        <div class="container-fluid pt-1">
            <div class="form_element">
                <div class="py-2 px-2 form-header">
                    <div class="row">
                        <div class="col-6"><h5>@lang('General Settings')</h5></div>
                    </div>
                </div>

                <div class="element-body">
                    <div class="settings_form_area">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="settings_side_menu">
                                    <ul class="menus_unorder_list">
                                        <li class="menu_list">
                                            <a class="menu_btn menu_active" data-form="business_settings_form"
                                                href="#">@lang('Business Settings')</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="tax_settings_form" href="#">@lang('Tax Settings')</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="dashboard_settings_form" href="#">@lang('Dashboard Settings')</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="prefix_settings_form" href="#">@lang('Prefix Settings')</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="system_settings_form" href="#">@lang('System Settings')</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="point_settings_form" href="#">@lang('Reward Point Settings')</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="module_settings_form" href="#">@lang('Modules Settings')</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="es_settings_form" href="#">@lang('Send Email') & SMS Settings</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <form id="business_settings_form" class="setting_form p-2"
                                    action="{{ route('settings.business.settings') }}" method="post"
                                    enctype="multipart/form-data">
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">@lang('Business Settings') </h6>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>@lang('Business Name') :</strong></label>
                                            <input type="text" name="shop_name" class="form-control bs_input"
                                                autocomplete="off"
                                                value="{{ json_decode($generalSettings->business, true)['shop_name'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Address') :</strong></label>
                                            <input type="text" name="address" class="form-control bs_input"
                                                autocomplete="off" placeholder="Business address"
                                                value="{{ json_decode($generalSettings->business, true)['address'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Phone') :</strong></label>
                                            <input type="text" name="phone" class="form-control bs_input" placeholder="Business phone number"
                                                value="{{ json_decode($generalSettings->business, true)['phone'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-md-4">
                                            <label><strong>@lang('Email') :</strong></label>
                                            <input type="text" name="email" class="form-control bs_input" placeholder="Business email address"
                                                value="{{ json_decode($generalSettings->business, true)['email'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Start Date') :</strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                </div>
                                                <input type="text" name="start_date" class="form-control" autocomplete="off"
                                                    value="{{ json_decode($generalSettings->business, true)['start_date'] }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Default Profit')(%) :</strong><span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="default_profit" class="form-control bs_input"
                                                autocomplete="off" data-name="Default profit" id="default_profit"
                                                value="{{ json_decode($generalSettings->business, true)['default_profit'] }}">
                                            <span class="error error_default_profit"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-md-4">
                                            <label><strong>@lang('Business Logo') :</strong> <small class="red-label-notice">@lang('Required Size') : H : 40px; W: 110px;</small></label>
                                            <input type="file" class="form-control" name="business_logo" id="business_logo">
                                            <small>@lang('Previous logo (if exists) will be replaced')</small><br>

                                            <span class="error error_business_logo"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Currency') :</strong><span class="text-danger">*</span></label>
                                            <select name="currency" class="form-control bs_input" data-name="Currency"
                                                id="currency">
                                                @foreach ($currencies as $currency)
                                                    <option
                                                        {{ json_decode($generalSettings->business, true)['currency'] == $currency->symbol ? 'SELECTED' : '' }}
                                                        value="{{ $currency->symbol }}">
                                                        {{ $currency->country . ' - ' . $currency->currency . '(' . $currency->code . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error error_currency"></span>
                                        </div>

                                        {{-- <div class="col-md-4">
                                            <label><strong>@lang('Financial year start month'):</strong> <span
                                                    class="text-danger">*</span></label>
                                            <select name="financial_year_start" class="form-control bs_input"
                                                data-name="Financial year start month" id="financial_year_start">
                                                @foreach ($months as $month)
                                                    <option value="{{ $month->month }}"
                                                        {{ json_decode($generalSettings->business, true)['financial_year_start'] == $month->month ? 'SELECTED' : '' }}>
                                                        {{ $month->month }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_financial_year_start"></span>
                                        </div> --}}

                                        <div class="col-md-4">
                                            <label><strong>@lang('Stock Accounting Method') : </strong> <span
                                                    class="text-danger">*</span></label>
                                            <select name="stock_accounting_method" class="form-control bs_input"
                                                data-name="Stock Accounting Method" id="stock_accounting_method">
                                                @php
                                                    $stock_accounting_method = json_decode($generalSettings->business, true)['stock_accounting_method'] ?? NULL;
                                                @endphp
                                                @foreach (App\Utils\Util::stockAccountingMethods() as $key => $item)
                                                    <option {{ $stock_accounting_method == $key ? 'SELECTED' : '' }} value="{{ $key }}">{{ $item }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_financial_year_start"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-md-4">
                                            <label><strong>@lang('Date Format'):</strong><span class="text-danger">*</span></label>
                                            <select name="date_format" class="form-control bs_input" data-name="Date format"
                                                id="date_format">
                                                <option value="d-m-Y"
                                                    {{ json_decode($generalSettings->business, true)['date_format'] == 'd-m-Y' ? 'SELECTED' : '' }}>
                                                    dd-mm-yyyy</option>
                                                <option value="m-d-Y"
                                                    {{ json_decode($generalSettings->business, true)['date_format'] == 'm-d-Y' ? 'SELECTED' : '' }}>
                                                    mm-dd-yyyy</option>
                                                <option value="Y-m-d" {{ json_decode($generalSettings->business, true)['date_format'] == 'Y-m-d' ? 'SELECTED' : '' }}>
                                                    yyyy-mm-dd</option>
                                            </select>
                                            <span class="error error_date_format"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Time Format'):</strong><span class="text-danger">*</span></label>
                                            <select name="time_format" class="form-control bs_input" data-name="Time format"
                                                id="time_format">
                                                <option value="12"
                                                    {{ json_decode($generalSettings->business, true)['time_format'] == '12' ? 'SELECTED' : '' }}>
                                                    12 Hour</option>
                                                <option value="24"
                                                    {{ json_decode($generalSettings->business, true)['time_format'] == '24' ? 'SELECTED' : '' }}>
                                                    24 Hour</option>
                                            </select>
                                            <span class="error error_time_format"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Time Zone'):</strong><span class="text-danger">*</span></label>
                                            <select name="timezone" class="form-control bs_input" data-name="Time format"
                                                id="time_format">
                                                <option value="">@lang('TimeZone')</option>
                                                @foreach ($timezones as $timezone)
                                                    <option
                                                        {{ json_decode($generalSettings->business, true)['timezone'] == $timezone->name ? 'SELECTED' : '' }}
                                                        value="{{ $timezone->name }}">{{ $timezone->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_time_format"></span>
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

                                <form id="tax_settings_form" class="setting_form hide-all"
                                    action="{{ route('settings.tax.settings') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">@lang('Tax Settings')</h6>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label>@lang('Tax 1 Name') : <span class="text-danger">*</span></label>
                                            <input type="text" name="tax_1_name" class="form-control" autocomplete="off"
                                                placeholder="GST / VAT / Other"
                                                value="{{ json_decode($generalSettings->tax, true)['tax_1_name'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label>@lang('Tax 1 No') : <span class="text-danger">*</span></label>
                                            <input type="text" name="tax_1_no" class="form-control" autocomplete="off"
                                                value="{{ json_decode($generalSettings->tax, true)['tax_1_no'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label>@lang('Tax 2 Name') : <span class="text-danger">*</span></label>
                                            <input type="text" name="tax_2_name" class="form-control" autocomplete="off"
                                                placeholder="GST / VAT / Other"
                                                value="{{ json_decode($generalSettings->tax, true)['tax_2_name'] }}">
                                        </div>

                                        <div class="col-md-4 mt-2">
                                            <label>@lang('Tax 2 No') : <span class="text-danger">*</span></label>
                                            <input type="text" name="tax_2_no" class="form-control" autocomplete="off"
                                                value="{{ json_decode($generalSettings->tax, true)['tax_2_no'] }}">
                                        </div>

                                        <div class="col-md-8">
                                            <div class="row mt-5">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->tax, true)['is_tax_en_purchase_sale'] == '1' ? 'CHECKED' : '' }} name="is_tax_en_purchase_sale" id="is_tax_en_purchase_sale">
                                                        &nbsp; Enable inline tax in purchase and sell
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

                                <form id="dashboard_settings_form" class="setting_form hide-all"
                                    action="{{ route('settings.dashboard.settings') }}" method="post">
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">@lang('Dashboard Settings')</h6>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label><strong>@lang('View Stock Expiry Alert For') :</strong> <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" name="view_stock_expiry_alert_for"
                                                    class="form-control dbs_input" id="dbs_view_stock_expiry_alert_for"
                                                    data-name="Day amount" autocomplete="off"
                                                    value="{{ json_decode($generalSettings->dashboard, true)['view_stock_expiry_alert_for'] }}">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text input-group-text-sm"
                                                        id="basic-addon1">@lang('Days')</span>
                                                </div>
                                            </div>
                                            <span class="error error_dbs_view_stock_expiry_alert_for"></span>
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

                                <form id="prefix_settings_form" class="setting_form hide-all"
                                    action="{{ route('settings.prefix.settings') }}" method="post">
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">@lang('Prefix Settings')</h6>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>@lang('Purchase Invoice') :</strong></label>
                                            <input type="text" name="purchase_invoice" class="form-control"
                                                autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['purchase_invoice'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Sale Invoice') :</strong></label>
                                            <input type="text" name="sale_invoice" class="form-control" autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['sale_invoice'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Purchase Return') :</strong></label>
                                            <input type="text" name="purchase_return" class="form-control"
                                                autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['purchase_return'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Stock Transfer') :</strong></label>
                                            <input type="text" name="stock_transfer" class="form-control" autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['stock_transfer'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>@lang('Stock Adjustment') :</strong></label>
                                            <input type="text" name="stock_djustment" class="form-control"
                                                autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['stock_djustment'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Sale Return') :</strong></label>
                                            <input type="text" name="sale_return" class="form-control" autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['sale_return'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Expenses') :</strong></label>
                                            <input type="text" name="expenses" class="form-control" autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['expenses'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>@lang('Expense Payment') :</strong></label>
                                            <input type="text" name="expense_payment" class="form-control"
                                                autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['expense_payment'] }}"
                                                >
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Purchase Payment') :</strong></label>
                                            <input type="text" name="purchase_payment" class="form-control"
                                                autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['purchase_payment'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Sale Payment') :</strong></label>
                                            <input type="text" name="sale_payment" class="form-control" autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['sale_payment'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>@lang('Supplier ID'):</strong></label>
                                            <input type="text" name="supplier_id" class="form-control"
                                                autocomplete="off" value="{{ json_decode($generalSettings->prefix, true)['supplier_id'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Customer ID') :</strong></label>
                                            <input type="text" name="customer_id" class="form-control" autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['customer_id'] }}">
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

                                <form id="system_settings_form" class="setting_form hide-all"
                                action="{{ route('settings.system.settings') }}" method="post">
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">@lang('System Settings')</h6>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>@lang('Theme Color') :</strong></label>
                                            <select name="theme_color" class="form-control" id="theme_color">
                                                <option {{ json_decode($generalSettings->system, true)['theme_color'] == 'dark-theme' ? 'SELECTED' : '' }} value="dark-theme">@lang('Default Theme')</option>
                                                <option  {{ json_decode($generalSettings->system, true)['theme_color'] == 'red-theme' ? 'SELECTED' : '' }} value="red-theme">@lang('Red Theme')</option>
                                                <option {{ json_decode($generalSettings->system, true)['theme_color'] == 'blue-theme' ? 'SELECTED' : '' }} value="blue-theme">@lang('Blue Theme')</option>
                                                <option {{ json_decode($generalSettings->system, true)['theme_color'] == 'light-theme' ? 'SELECTED' : '' }} value="light-theme">@lang('Light Theme')</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('entries') :</strong></label>
                                            <select name="datatable_page_entry" class="form-control" id="datatable_page_entry">
                                                <option {{ json_decode($generalSettings->system, true)['datatable_page_entry'] == 10 ? 'SELECTED' : '' }} value="10">10</option>
                                                <option {{ json_decode($generalSettings->system, true)['datatable_page_entry'] == 25 ? 'SELECTED' : '' }} value="25">25</option>
                                                <option {{ json_decode($generalSettings->system, true)['datatable_page_entry'] == 50 ? 'SELECTED' : '' }} value="50">50</option>
                                                <option {{ json_decode($generalSettings->system, true)['datatable_page_entry'] == 100 ? 'SELECTED' : '' }} value="100">100</option>
                                                <option {{ json_decode($generalSettings->system, true)['datatable_page_entry'] == 500 ? 'SELECTED' : '' }} value="500">500</option>
                                                <option {{ json_decode($generalSettings->system, true)['datatable_page_entry'] == 1000 ? 'SELECTED' : '' }} value="1000">1000</option>
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

                                <form id="point_settings_form" class="setting_form hide-all"
                                    action="{{ route('settings.reward.point.settings') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <h6 class="text-primary mb-3"><b>@lang('Reward Point Settings')</b></h6>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-4">
                                            <div class="row ">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ json_decode($generalSettings->reward_poing_settings, true)['enable_cus_point'] == '1' ? 'CHECKED' : '' }} name="enable_cus_point"> &nbsp; <b>@lang('Enable Reward Point')</b>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Reward Point Display Name') :</strong></label>
                                            <input type="text" name="point_display_name" class="form-control" autocomplete="off" value="{{ json_decode($generalSettings->reward_poing_settings, true)['point_display_name'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <h6 class="text-primary mb-1"><b>@lang('Earning Settings')</b></h6>
                                        <div class="col-md-4">
                                            <label><strong>@lang('Amount spend for unit point') : </strong>
                                                <i data-bs-toggle="tooltip" data-bs-placement="left" title="Example: If you set it as 10, then for every $10 spent by customer they will get one reward points. If the customer purchases for $1000 then they will get 100 reward points." class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="amount_for_unit_rp" class="form-control" autocomplete="off" value="{{ json_decode($generalSettings->reward_poing_settings, true)['amount_for_unit_rp'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Minimum order total to earn reward') :</strong> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Example: If you set it as 100 then customer will get reward points only if there invoice total is greater or equal to 100. If invoice total is 99 then they wonâ€™t get any reward points.You can set it as minimum 1." class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="min_order_total_for_rp" class="form-control" autocomplete="off" value="{{ json_decode($generalSettings->reward_poing_settings, true)['min_order_total_for_rp'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Maximum points per order') :</strong> <i data-bs-toggle="tooltip" data-bs-placement="right" title="Maximum reward points customers can earn in one invoice. Leave it empty if you donâ€™t want any such restrictions." class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="max_rp_per_order" class="form-control" autocomplete="off" value="{{ json_decode($generalSettings->reward_poing_settings, true)['max_rp_per_order'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">

                                        <h6 class="text-primary mb-1"><b>@lang('Redeem Points Settings')</b></h6>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Redeem amount per unit point') : </strong>
                                                <i data-bs-toggle="tooltip" data-bs-placement="top" title="example: If 1 point is $1 then enter the value as 1. If 2 points is $1 then enter the value as 0.50" class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="redeem_amount_per_unit_rp" class="form-control" autocomplete="off" value="{{ json_decode($generalSettings->reward_poing_settings, true)['redeem_amount_per_unit_rp'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Minimum order total to redeem points') : </strong>
                                                <i data-bs-toggle="tooltip" data-bs-placement="right" title="Minimum order total for which customers can redeem points. Leave it blank if you donâ€™t need this restriction or you need to give something for free." class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="min_order_total_for_redeem" class="form-control" autocomplete="off" value="{{ json_decode($generalSettings->reward_poing_settings, true)['min_order_total_for_redeem'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('Minimum redeem point') : </strong>
                                                <i data-bs-toggle="tooltip" data-bs-placement="top" title="Minimum redeem points that can be used per invoice. Leave it blank if you donâ€™t need this restriction." class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="min_redeem_point" class="form-control" autocomplete="off" value="{{ json_decode($generalSettings->reward_poing_settings, true)['min_redeem_point'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-4">
                                            <label><strong>@lang('Maximum redeem point per order') : </strong>
                                                <i data-bs-toggle="tooltip" data-bs-placement="right" title="Maximum points that can be used in one order. Leave it blank if you donâ€™t need this restriction." class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="max_redeem_point" class="form-control" autocomplete="off" value="{{ json_decode($generalSettings->reward_poing_settings, true)['max_redeem_point'] }}">
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

                                <form id="module_settings_form" class="setting_form hide-all"
                                    action="{{ route('settings.module.settings') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary"><b>@lang('Module Settings')</b></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-4">
                                            <div class="row ">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->modules, true)['purchases'] == '1' ? 'CHECKED' : '' }}
                                                        name="purchases" autocomplete="off"> &nbsp; <b>@lang('Purchases')</b>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->modules, true)['add_sale'] == '1' ? 'CHECKED' : '' }}
                                                        name="add_sale" autocomplete="off"> &nbsp; <b>@lang('Add Sale')</b>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->modules, true)['pos'] == '1' ? 'CHECKED' : '' }}
                                                        name="pos" autocomplete="off"> &nbsp; <b>@lang('POS')</b>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-4">
                                            <div class="row ">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->modules, true)['transfer_stock'] == '1' ? 'CHECKED' : '' }}
                                                        name="transfer_stock" autocomplete="off">
                                                    &nbsp; <b>@lang('Transfers Stock')</b>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->modules, true)['stock_adjustment'] == '1' ? 'CHECKED' : '' }}
                                                        name="stock_adjustment" autocomplete="off"> &nbsp; <b>@lang('Stock Adjustment')</b>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->modules, true)['expenses'] == '1' ? 'CHECKED' : '' }}
                                                        name="expenses" autocomplete="off"> &nbsp; <b>@lang('Expenses')</b>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-4">
                                            <div class="row ">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->modules, true)['accounting'] == '1' ? 'CHECKED' : '' }}
                                                        name="accounting" autocomplete="off"> &nbsp; <b>@lang('Accounting')</b>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->modules, true)['contacts'] == '1' ? 'CHECKED' : '' }}
                                                        name="contacts" autocomplete="off"> &nbsp; <b>@lang('Contacts')</b>
                                                </p>
                                            </div>
                                        </div>

                                        @if ($addons->hrm == 1)
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox"
                                                            {{ json_decode($generalSettings->modules, true)['hrms'] == '1' ? 'CHECKED' : '' }}
                                                            name="hrms" autocomplete="off"> &nbsp; <b>@lang('Human Resource Management')</b>
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-4">
                                            <div class="row ">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ json_decode($generalSettings->modules, true)['requisite'] == '1' ? 'CHECKED' : '' }} name="requisite" autocomplete="off"> &nbsp; <b>@lang('Requisite')</b>
                                                </p>
                                            </div>
                                        </div>

                                        @if ($addons->manufacturing == 1)
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox"
                                                            @if (isset(json_decode($generalSettings->modules, true)['manufacturing']))
                                                                {{ json_decode($generalSettings->modules, true)['manufacturing'] == '1' ? 'CHECKED' : '' }}
                                                            @endif
                                                            name="manufacturing" autocomplete="off">
                                                        &nbsp;<b>@lang('Manufacture')</b>
                                                    </p>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($addons->service == 1)
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox"
                                                            @if (isset(json_decode($generalSettings->modules, true)['service']))
                                                                {{ json_decode($generalSettings->modules, true)['service'] == '1' ? 'CHECKED' : '' }}
                                                            @endif
                                                            name="service" autocomplete="off">
                                                        &nbsp;<b>@lang('Service')</b>
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 text-end">
                                            <button type="button" class="btn loading_button d-none"><i
                                                class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                            <button class="btn btn-sm btn-success submit_button float-end">@lang('Save Change')</button>
                                        </div>
                                    </div>
                                </form>

                                <form id="es_settings_form" class="setting_form hide-all"
                                    action="{{ route('settings.send.email.sms.settings') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">@lang('Send Email') & SMS Settings</h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4 mt-1">
                                            <div class="row mt-4">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ json_decode($generalSettings->send_es_settings, true)['send_inv_via_email'] == '1' ? 'CHECKED' : '' }} name="send_inv_via_email"> &nbsp; <b>@lang('Send Invoice After Sale Via Email')</b>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mt-1">
                                            <div class="row mt-4">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ json_decode($generalSettings->send_es_settings, true)['send_notice_via_sms'] == '1' ? 'CHECKED' : '' }} name="send_notice_via_sms"> &nbsp; <b>@lang('Send Notification After Sale Via SMS')</b>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mt-1">
                                            <div class="row mt-4">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ json_decode($generalSettings->send_es_settings, true)['cmr_due_rmdr_via_email'] == '1' ? 'CHECKED' : '' }} name="cmr_due_rmdr_via_email"> &nbsp; <b>@lang('Customer Due Remainder Via Email')</b>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4 mt-1">
                                            <div class="row mt-4">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" name="cmr_due_rmdr_via_sms" {{ json_decode($generalSettings->send_es_settings, true)['cmr_due_rmdr_via_sms'] == '1' ? 'CHECKED' : '' }}> &nbsp; <b>@lang('Customer Due Remainder Via SMS')</b>
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
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            //$('.setting_form').hide();
            $(document).on('click', '.menu_btn', function(e) {
                e.preventDefault();
                var form_name = $(this).data('form');
                $('.setting_form').hide(500);
                $('#' + form_name).show(500);
                $('.menu_btn').removeClass('menu_active');
                $(this).addClass('menu_active d-block');
            });
        });

        $('#business_settings_form').on('submit', function(e) {
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.bs_input');
            inputs.removeClass('is-invalid');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {

                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val()
                if (idValue == '') {

                    countErrorField += 1;
                    $('#' + inputId).addClass('is-invalid');
                    var fieldName = $('#' + inputId).data('name');
                    $('.error_' + inputId).html(fieldName + ' is required.');
                }
            });

            if (countErrorField > 0) {

                $('.loading_button').hide();
                return;
            }

            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });

        $('#tax_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.bs_input');
            inputs.removeClass('is-invalid');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {
                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val()
                if (idValue == '') {
                    countErrorField += 1;
                    $('#' + inputId).addClass('is-invalid');
                    var fieldName = $('#' + inputId).data('name');
                    $('.error_' + inputId).html(fieldName + ' is required.');
                }
            });

            if (countErrorField > 0) {
                $('.loading_button').hide();
                return;
            }

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

        $('#dashboard_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.dbs_input');
            inputs.removeClass('is-invalid');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {
                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val()
                if (idValue == '') {
                    countErrorField += 1;
                    $('#' + inputId).addClass('is-invalid');
                    var fieldName = $('#' + inputId).data('name');
                    $('.error_' + inputId).html(fieldName + ' is required.');
                }
            });

            if (countErrorField > 0) {
                $('.loading_button').hide();
                return;
            }

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

        $('#prefix_settings_form').on('submit', function(e) {
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

        $('#system_settings_form').on('submit', function(e) {
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
                    window.location = "{{ url()->current() }}";
                }
            });
        });

        $('#point_settings_form').on('submit', function(e) {
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

        $('#module_settings_form').on('submit', function(e) {
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

        $('#es_settings_form').on('submit', function(e) {
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
