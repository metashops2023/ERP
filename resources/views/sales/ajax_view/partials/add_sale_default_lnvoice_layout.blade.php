@php
    $defaultLayout = DB::table('invoice_layouts')
        ->where('is_default', 1)
        ->first();
@endphp
@if ($defaultLayout->layout_design == 1)
    <div class="sale_print_template d-none">
        @if (app()->isLocale('ar'))
            <div class="invoice" dir="rtl">
            @else
                <div class="invoice">
        @endif
        <div class="container">
            <div class="row">
                <div class="col-12 d-flex justify-content-between heading py-3">
                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <h3>فاتورة ضريبية</h3>
                            <div class="d-flex justify-content-between">
                                <h5>الرقم التسلسلي <br> {{ $sale->invoice_id }}</h5>
                                <h5>التاريخ <br>
                                    {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($sale->date)) }}
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div>
                        <img style="width: 50px;"
                            src="data:image/png;base64, 
                                        {!! base64_encode(
                                            QrCode::format('png')->size(100)->generate(
                                                    $sale->invoice_id .
                                                        ',' .
                                                        json_decode($generalSettings->tax, true)['tax_1_no'] .
                                                        ',' .
                                                        json_decode($generalSettings->business, true)['shop_name'] .
                                                        ',' .
                                                        date(json_decode($generalSettings->business, true)['date_format'], strtotime($sale->date)) .
                                                        ' ' .
                                                        date($timeFormat, strtotime($sale->time)) .
                                                        ',' .
                                                        App\Utils\Converter::format_in_bdt($sale->total_payable_amount) .
                                                        ',' .
                                                        App\Utils\Converter::format_in_bdt($sale->order_tax_amount),
                                                ),
                                        ) !!} ">
                    </div>
                </div>
                <!-- Start Merchant Table -->
                <table class="table mt-2 mb-5" style="border: 1px solid #EEE;">
                    <h5>معلومات البائع</h5>
                    <thead>
                        <tr>
                            <th scope="col">اسم البائع</th>
                            <th scope="col">عنوان البائع</th>
                            <th scope="col">رقم تسجيل ضريبة القسط</th>
                            <th scope="col">رقم السجل التجاري</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ json_decode($generalSettings->business, true)['shop_name'] }}</td>
                            <td>{{ json_decode($generalSettings->business, true)['address'] }}</td>
                            <td>{{ json_decode($generalSettings->tax, true)['tax_1_no'] }}</td>
                            <td>{{ json_decode($generalSettings->tax, true)['tax_2_no'] }}</td>
                        </tr>
                    </tbody>
                </table>
                <!-- End Merchant Table -->

                <!-- Start Customer Table -->
                <table class="table mt-2 mb-5" style="border: 1px solid #EEE;">
                    <h5>معلومات المشتري</h5>
                    <thead>
                        <tr>
                            <th scope="col">اسم البائع</th>
                            <th scope="col">عنوان البائع</th>
                            <th scope="col">رقم السجل التجاري</th>
                            <th scope="col">رقم تسجيل ضريبة القسط</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}</td>
                            <td>{{ $sale->customer ? $sale->customer->address : 'Address' }}</td>
                            <td>----</td>
                            <td>5116515612612</td>
                        </tr>
                    </tbody>
                </table>
                <!-- End Customer Table -->

                <!-- Start Products Table -->
                <table class="table mt-2 mb-5" style="border: 1px solid #EEE;">
                    <thead>
                        <tr>
                            <th scope="col">المنتج</th>
                            <th scope="col">سعر الوحدة</th>
                            <th scope="col">الكمية</th>
                            <th scope="col">المجموع الفرعي بدون ضريبة</th>
                            <th scope="col">نسبة الضريبة </th>
                            <th scope="col">ثيمة الضريبة</th>
                            <th scope="col">المجموع شامل ضريبة القيمة المضافة </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customerCopySaleProducts as $saleProduct)
                            <tr>
                                @php
                                    $variant = $saleProduct->product_variant_id ? ' ' . $saleProduct->variant_name : '';
                                @endphp
                                <td>{{ Str::limit($saleProduct->p_name, 25, '') . $variant }}</td>
                                <td>{{ $saleProduct->unit_price_exc_tax }}</td>
                                <td>{{ (float) $saleProduct->quantity }}</td>
                                <td>{{ (float) $saleProduct->subtotal }}</td>
                                <td>{{ (float) $saleProduct->unit_tax_percent }}</td>
                                <td>{{ (float) $saleProduct->unit_tax_amount }}</td>
                                <td>{{ (float) $saleProduct->unit_price_inc_tax }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- End Products Table -->

                <div class="col-6 text-end">
                    <div class="d-flex justify-content-between">
                        <h3 class="fs-5">المجموع</h3>
                        <p >{{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h3 class="fs-5">ضريبة القيمة المضافة (15%)</h3>
                        <p>{{ App\Utils\Converter::format_in_bdt(($sale->order_tax_amount)) }}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h3 class="fs-5">المجموع مع الضريبة (15%)</h3>
                        <p>{{ App\Utils\Converter::format_in_bdt($sale->total_payable_amount) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@else
    <style>
        @page {
            margin: 8px;
        }
    </style>
    <!-- Tharmal print templete-->
    <div class="sale_print_template d-none">
        <div class="pos_print_template">
            <div class="row">
                <div class="company_info">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    @if ($defaultLayout->show_shop_logo == 1)
                                        @if ($sale->branch)
                                            @if ($sale->branch->logo != 'default.png')
                                                <img style="height: 40px; width:200px;"
                                                    src="{{ asset('uploads/branch_logo/' . $sale->branch->logo) }}">
                                            @else
                                                <span
                                                    style="font-family: 'Anton', sans-serif;font-size:15px;color:black;font-weight: 600;">{{ $sale->branch->name }}</span>
                                            @endif
                                        @else
                                            @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                                                <img style="height: 40px; width:200px;"
                                                    src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}"
                                                    alt="logo" class="logo__img">
                                            @else
                                                <span
                                                    style="font-family: 'Anton', sans-serif;font-size:15px;color:black;font-weight: 600;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                                            @endif
                                        @endif
                                    @endif
                                </th>
                            </tr>

                            @if ($sale->branch)
                                <tr>
                                    <th class="text-center">
                                        <h6>{{ $sale->branch->name . '/' . $sale->branch->branch_code }}</h6>
                                    </th>
                                </tr>

                                <tr>
                                    <th class="text-center">
                                        <span>{{ $sale->branch->city . ', ' . $sale->branch->state . ', ' . $sale->branch->zip_code . ', ' . $sale->branch->country }}</span>
                                    </th>
                                </tr>

                                <tr>
                                    <th class="text-center">
                                        <span><b>@lang('Phone') :</b> {{ $sale->branch->phone }}</span>
                                    </th>
                                </tr>

                                <tr>
                                    <th class="text-center">
                                        <span><b>@lang('Email') :</b> {{ $sale->branch->email }}</span>
                                    </th>
                                </tr>
                            @else
                                <tr>
                                    <th class="text-center">
                                        <span>{{ json_decode($generalSettings->business, true)['address'] }} </span>
                                    </th>
                                </tr>

                                <tr>
                                    <th class="text-center">
                                        <span><b>@lang('Phone') :</b>
                                            {{ json_decode($generalSettings->business, true)['phone'] }} </span>
                                    </th>
                                </tr>

                                <tr>
                                    <th class="text-center">
                                        <span><b>@lang('Email') :</b>
                                            {{ json_decode($generalSettings->business, true)['email'] }} </span>
                                    </th>
                                </tr>
                            @endif
                        </thead>
                    </table>
                </div>

                <div class="customer_info mt-3">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <b>@lang('Date'):</b>
                                    <span>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($sale->date)) . ' ' . date($timeFormat, strtotime($sale->time)) }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <b>@lang('INV NO'): </b> <span>{{ $sale->invoice_id }}</span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <b>@lang('Customer'):</b>
                                    <span>{{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}</span>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="description_area pt-2 pb-1">
                    <table class="w-100">
                        <thead class="t-head">
                            <tr>
                                <th class="text-start">@lang('Description')</th>
                                <th class="text-center">@lang('Qty')</th>
                                <th class="text-center">@lang('Price')</th>
                                <th class="text-end">@lang('Total')</th>
                            </tr>
                        </thead>
                        <thead class="d-body">
                            @foreach ($customerCopySaleProducts as $saleProduct)
                                <tr>
                                    @php
                                        $variant = $saleProduct->product_variant_id ? ' ' . $saleProduct->variant_name : '';
                                    @endphp

                                    <th class="text-start">
                                        {{ $loop->index + 1 }}.
                                        {{ Str::limit($saleProduct->p_name, 25, '') . $variant }}
                                    </th>

                                    <th class="text-center">{{ (float) $saleProduct->quantity }}</th>
                                    <th class="text-center">{{ $saleProduct->unit_price_inc_tax }}</th>
                                    <th class="text-end">{{ $saleProduct->subtotal }}</th>
                                </tr>
                            @endforeach
                        </thead>
                    </table>
                </div>

                <div class="amount_area">
                    <table class="w-100 float-end">
                        <thead>
                            <tr>
                                <th class="text-end">@lang('Net Total') :
                                    {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end">@lang('Discount') :
                                    {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end">@lang('Vat/Tax') :
                                    {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                <th class="text-end">
                                    <span>
                                        ({{ $sale->order_tax_percent }} %)
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"> @lang('Total Payable') :
                                    {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->total_payable_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"> @lang('Total Paid') :
                                    {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->paid) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end">@lang('Change Amount') :
                                    {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->change_amount) }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-end"> @lang('Total Due') :
                                    {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <th class="text-end">
                                    <span>
                                        {{ App\Utils\Converter::format_in_bdt($sale->due) }}
                                    </span>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="footer_text_area mt-2">
                    <table class="w-100 ">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <span>
                                        {{ $defaultLayout->invoice_notice ? $defaultLayout->invoice_notice : '' }}
                                    </span>
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <br>
                                    <span>
                                        {{ $defaultLayout->footer_text ? $defaultLayout->footer_text : '' }}
                                    </span>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="footer_area mt-1">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <img style="width: 170px; height:20px;"
                                        src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
                                </th>
                            </tr>

                            <tr>
                                <th class="text-center">
                                    <span>{{ $sale->invoice_id }}</span>
                                </th>
                            </tr>

                            @if (env('PRINT_SD_SALE') == true)
                                <tr>
                                    <th class="text-center">
                                        <span>@lang('Software By') <b>@lang('MetaShops Pvt'). Ltd.</b> </span>
                                    </th>
                                </tr>
                            @endif
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
