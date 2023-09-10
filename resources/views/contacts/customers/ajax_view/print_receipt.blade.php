@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp 
 <!--Money Receipt design-->
 <div class="print_area">
    <div class="print_content">
        @if ($receipt->is_header_less == 0)
            <div class="heading_area">
                <div class="row">
                    <div class="col-6">
                        @if ($receipt->logo)
                            @if ($receipt->logo != 'default.png')
                                <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $receipt->logo) }}">
                            @else 
                                <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ $receipt->branch_name }}</span>
                            @endif
                        @else 
                            @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                                <img src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                            @else 
                                <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                            @endif
                        @endif
                    </div>

                    <div class="col-6">
                        <div class="heading text-end">
                            <h4>@lang('Money Receipt')</h4>
                            @if ($receipt->branch_name)
                            <h6 class="company_name">
                                <b>{{ $receipt->branch_name . '/' . $receipt->branch_code }}</b>
                            </h6>
                                <p class="company_address">
                                    {{ $receipt->city ? $receipt->city : '' }},{{ $receipt->state ? $receipt->state : '' }},{{ $receipt->zip_code ? $receipt->zip_code : '' }},{{ $receipt->country ? $receipt->country : '' }}.
                                </p>
                                <p><strong>@lang('Phone') :</strong> {{ $receipt->phone }}</p>
                                <p><strong>@lang('Email') :</strong> {{ $receipt->email }}</p>
                            @else 
                                <h6 class="company_name"><b>{{ json_decode($generalSettings->business, true)['shop_name'] }}</b></h6>
                                <p class="company_address">{{ json_decode($generalSettings->business, true)['address'] }}</p>
                                <p>@lang('Phone') : {{ json_decode($generalSettings->business, true)['phone'] }}</p>
                                <p>@lang('Email') : {{ json_decode($generalSettings->business, true)['email'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div><br>
        @endif

        @if ($receipt->is_header_less == 1)
            @for ($i = 0; $i < $receipt->gap_from_top; $i++)
                <br>
            @endfor
        @endif

        <div class="row">
            <div class="col-4">
                <p><b>@lang('Voucher No')</b> : {{ $receipt->invoice_id }}</p>
            </div>

            <div class="col-4 text-center">
                @if ($receipt->is_header_less == 1)
                    <h6><b>@lang('Money Receipt')</b></h6>
                @endif
            </div>

            <div class="col-4 text-end">
                <p> <b>@lang('Date')</b> : {{ $receipt->is_date ? date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($receipt->date)) : '.......................................' }}</p>
            </div>
        </div><br>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <p> <b> @lang('Received With Thanks From') </b> : {{ $receipt->is_customer_name ? $receipt->cus_name : ''}}</p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <p><b>@lang('Amount Of Money')</b> : {{ $receipt->amount > 0 ? json_decode($generalSettings->business, true)['currency'].' '.App\Utils\Converter::format_in_bdt($receipt->amount) : ''}}</p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <p><b>@lang('In Words')</b> : 
                            @if ($receipt->amount > 0)
                                <span style="text-transform: uppercase;" id="inWord2"></span>.
                            @endif 
                        </p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>
            
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <p> <b>@lang('Paid To')</b>  : {{ $receipt->receiver }}</p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <p><b>@lang('On Account Of')</b>  : {{ $receipt->ac_details }}</p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <p><b>@lang('Pay Method') </b> : Cash/Card/Bank-Transfer/Cheque/Advanced</p>
            </div>
        </div><br>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12 text-center">
                    <p><b>{{ $receipt->note }}</b></p>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col-md-6">
                <div class="details_area">
                    <h6 class="borderTop">@lang('Customer')'s signature </h6>
                </div>
            </div>
            <div class="col-md-6">
                <div class="details_area text-end">
                    <h6 class="borderTop"> @lang('Signature Of Authority') </h6>
                </div>
            </div>
        </div>

        <div class="row page_break">
            <div class="col-12 text-center">
                <img style="width: 170px; height:30px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($receipt->invoice_id, $generator::TYPE_CODE_128)) }}">
                @if (env('PRINT_SD_SALE') == true)
                    <small class="d-block">@lang('Software By') <b>@lang('MetaShops Pvt'). Ltd.</b></small>
                @endif
            </div>
        </div>
    </div>
    
    <div class="print_content">
        @if ($receipt->is_header_less == 0)
            <div class="heading_area">
                <div class="row">
                    <div class="col-6">
                        @if ($receipt->logo)
                            @if ($receipt->logo != 'default.png')
                                <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $receipt->logo) }}">
                            @else 
                                <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ $receipt->branch_name }}</span>
                            @endif
                        @else 
                            @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                                <img src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                            @else 
                                <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                            @endif
                        @endif
                    </div>

                    <div class="col-6">
                        <div class="heading text-end">
                            <h3>@lang('Money Receipt')</h3>
                            @if ($receipt->branch_name)
                                <h6 class="company_name"><b>
                                    {{ $receipt->branch_name . '/' . $receipt->branch_code }}</b>
                                </h6>
                                <p class="company_address">
                                    {{ $receipt->city ? $receipt->city : '' }},{{ $receipt->state ? $receipt->state : '' }},{{ $receipt->zip_code ? $receipt->zip_code : '' }},{{ $receipt->country ? $receipt->country : '' }}.
                                </p>
                                <p><strong>@lang('Phone') :</strong> {{ $receipt->phone }}</p>
                                <p><strong>@lang('Email') :</strong> {{ $receipt->email }}</p>
                            @else 
                                <h6 class="company_name"><b>{{ json_decode($generalSettings->business, true)['shop_name'] }}</b></h6>
                                <p class="company_address">{{ json_decode($generalSettings->business, true)['address'] }}</p>
                                <p>@lang('Phone') : {{ json_decode($generalSettings->business, true)['phone'] }}</p>
                                <p>@lang('Email') : {{ json_decode($generalSettings->business, true)['email'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div><br>
        @endif

        @if ($receipt->is_header_less == 1)
            @for ($i = 0; $i < $receipt->gap_from_top; $i++)
                <br>
            @endfor
        @endif

        <div class="row">
            <div class="col-md-4 col-sm-4 col-lg-4">
                <p><b>@lang('Voucher No')</b> : {{ $receipt->invoice_id }}</p>
            </div>

            <div class="col-4 text-center">
                @if ($receipt->is_header_less == 1)
                    <h6><b>@lang('Money Receipt')</b></h6>
                @endif
            </div>

            <div class="col-4 text-end">
                <p><b>@lang('Date')</b> : {{ $receipt->is_date ? date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($receipt->date)) : '.......................................' }}</p>
            </div>
        </div><br>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <p> <b> @lang('Received With Thanks From') </b> : 
                            {{ $receipt->is_customer_name ? $receipt->cus_name : ''}}
                        </p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <p><b>@lang('Amount Of Money')</b> : {{ $receipt->amount > 0 ? json_decode($generalSettings->business, true)['currency'].' '.App\Utils\Converter::format_in_bdt($receipt->amount) : ''}}</p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <p><b>@lang('In Words')</b> : 
                            @if ($receipt->amount > 0)
                                <span style="text-transform: uppercase;" id="inWord1"></span>.
                            @endif
                        </p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br> 
            
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <p><b>@lang('Paid To')</b> : {{ $receipt->receiver }}</p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <p><b>@lang('On Account Of')</b>  : {{ $receipt->ac_details }}</p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <p><b>@lang('Pay Method')</b>  : Cash/Card/Bank-Transfer/Cheque/Advanced</p>
            </div>
        </div><br>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12 text-center">
                <p><b>{{ $receipt->note }}</b></p>
            </div>
        </div><br><br>

        <div class="row">
            <div class="col-md-6">
                <div class="details_area">
                    <h6 class="borderTop">@lang('Customer')'s signature </h6>
                </div>
            </div>
            <div class="col-md-6">
                <div class="details_area text-end">
                    <h6 class="borderTop"> @lang('Signature Of Authority') </h6>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-center">
                <img style="width: 170px; height:30px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($receipt->invoice_id, $generator::TYPE_CODE_128)) }}">
                @if (env('PRINT_SD_SALE') == true)
                    <small class="d-block">@lang('Software By') <b>@lang('MetaShops Pvt'). Ltd.</b></small>
                @endif
            </div>
        </div>
    </div>
</div>
<!--Money Receipt design end-->

<script>
    var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
    var b = ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];
  
    function inWords (num) {
          if ((num = num.toString()).length > 9) return 'overflow';
          n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
          if (!n) return; var str = '';
          str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
          str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
          str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
          str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
          str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + 'only ' : '';
          return str;
    }
    document.getElementById('inWord1').innerHTML = inWords(parseInt("{{ $receipt->amount }}"));
    document.getElementById('inWord2').innerHTML = inWords(parseInt("{{ $receipt->amount }}"));
  </script>