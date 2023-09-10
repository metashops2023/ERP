@php 
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';                          
@endphp 
<html>
<body style="background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
  <table style="max-width:670px;margin:50px auto 10px;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px green;">
    <thead>
      <tr>
        <th style="text-align:left;">
            @if ($sale->branch)
                <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch->logo) }}">
            @else 
                <img style="height: 60px; width:200px;" src="{{ asset('uploads/business_logo/'.json_decode($generalSettings->business, true)['business_logo']) }}">
            @endif
        </th>
        <th style="text-align:right;font-weight:400;">{{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date)) . ' ' . date($timeFormat, strtotime($sale->time)) }}</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="height:35px;"></td>
      </tr>
      <tr>
        <td colspan="2" style="border: solid 1px #ddd; padding:10px 20px;">
            <p style="font-size:14px;margin:0 0 6px 0;">
                <span style="font-weight:bold;display:inline-block;min-width:150px">@lang('Paid Status') :</span>
                @php
                    $payable = $sale->total_payable_amount - $sale->sale_return_amount;
                @endphp
                @if ($sale->due <= 0)
                    <b style="color:green;font-weight:normal;margin:0">@lang('Paid')</b>
                @elseif ($sale->due > 0 && $sale->due < $payable) 
                    <b style="color:orange;font-weight:normal;margin:0">@lang('Partial')</b> 
                @elseif($payable==$sale->due)
                    <b style="color:red;font-weight:normal;margin:0">@lang('Due')</b> 
                @endif
            </p>
            <p style="font-size:14px;margin:0 0 6px 0;">
                <span style="font-weight:bold;display:inline-block;min-width:146px">@lang('Invoice ID') :</span> 
                {{ $sale->invoice_id }}
            </p>
            <p style="font-size:14px;margin:0 0 0 0;">
                <span style="font-weight:bold;display:inline-block;min-width:146px">@lang('Total Payable') : </span>
                {{ json_decode($generalSettings->business, true)['currency'] }} {{ number_format($sale->total_payable_amount, 2) }}
            </p>
            <p style="font-size:14px;margin:0 0 0 0;">
                <span style="font-weight:bold;display:inline-block;min-width:146px">@lang('Total Paid') : </span> 
                {{ json_decode($generalSettings->business, true)['currency'] }} {{ $sale->paid }}
            </p>
            <p style="font-size:14px;margin:0 0 0 0;">
                <span style="font-weight:bold;display:inline-block;min-width:146px">@lang('Due') : </span> 
                {{ json_decode($generalSettings->business, true)['currency'] }} {{ $sale->due }}
            </p>
        </td>
      </tr>
      <tr>
        <td style="height:35px;"></td>
      </tr>
      <tr>
        <td style="width:50%;padding:20px;vertical-align:top">
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;">
                <span style="display:block;font-weight:bold;font-size:13px">@lang('Customer Name'): </span> 
                {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
            </p>
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;">
                <span style="display:block;font-weight:bold;font-size:13px;">@lang('Address') :</span> 
                {{ $sale->customer ? $sale->customer->address : '' }}</p>
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;">
                <span style="display:block;font-weight:bold;font-size:13px;">@lang('Phone') :</span> {{ $sale->customer ? $sale->customer->phone : '' }}
            </p>
        </td>

        @if ($sale->branch)
            <td style="width:50%;padding:20px;vertical-align:top">
                <h6 style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span> </h6>
                <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">  
                    {{ $sale->branch->name . '/' . $sale->branch->branch_code }},
                    {{ $defaultLayout->branch_city == 1 ? $sale->branch->city : '' }},
                    {{ $defaultLayout->branch_state == 1 ? $sale->branch->state : '' }},
                    {{ $defaultLayout->branch_zipcode == 1 ? $sale->branch->zip_code : '' }},
                    {{ $defaultLayout->branch_country == 1 ? $sale->branch->country : '' }}.</span> 
                </p>
                <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">@lang('Phone') :</span> {{ $sale->branch->phone }}</p>
            </td>
        @else 
            <td style="width:50%;padding:20px;vertical-align:top">
                <h6 style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span> </h6>
                <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">{{ json_decode($generalSettings->business, true)['address'] }}</span> </p>
                <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">@lang('Phone') :</span> {{ json_decode($generalSettings->business, true)['phone'] }}</p>
            </td>
        @endif
      </tr>
      <tr>
        <td colspan="2" style="font-size:20px;padding:30px 15px 0 15px;">@lang('Description')</td>
      </tr>
      @foreach ($sale->sale_products as $sale_product)
        <tr>
            <td colspan="2" style="padding:15px;">
                <p style="font-size:14px;margin:0;padding:10px;border:solid 1px #ddd;font-weight:bold;">
                <span style="display:block;font-size:13px;font-weight:normal;">
                    {{ $sale_product->product->name }}
                    @if ($sale_product->variant)
                        -{{ $sale_product->variant->variant_name }}
                    @endif
                </span>Price- {{ $sale_product->unit_price_inc_tax }}
                    <b style="font-size:12px;font-weight:300;"> /Qty-{{ $sale_product->quantity }}({{ $sale_product->unit }})/Subtotal-{{ $sale_product->subtotal }}</b>
                </p>
            </td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2" style="font-size:14px;padding:50px 15px 0 15px;">
          <strong style="display:block;margin:0 0 10px 0;">@lang('Regards')</strong> <br>  
            If you need any support, Feel free to contact us.
            <br><br>
            <b>@lang('Phone'):</b> {{ json_decode($generalSettings->business, true)['phone'] }}<br>
            <b>@lang('Email'):</b> {{ json_decode($generalSettings->business, true)['email'] }}
        </td>
      </tr>
    </tfoot>
  </table>
</body>
</html>