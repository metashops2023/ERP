<html>
<body style="background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
  <table style="max-width:670px;margin:50px auto 10px;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px green;">
    <thead>
      <tr>
        <th style="text-align:left;">
            @if ($transfer->branch)
                <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $transfer->branch->logo) }}">
            @else
                <img style="height: 60px; width:200px;" src="{{ asset('uploads/business_logo/'.json_decode($generalSettings->business, true)['business_logo']) }}">
            @endif
        </th>
      </tr>
      <tr>
        <th style="text-align:left;">
            <p><b>@lang('Receive Stock Details')</b></p>
        </th>
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
                <b style="color:green;font-weight:normal;margin:0">
                  @if ($transfer->status == 1)
                    Pending
                  @elseif($transfer->status == 2)
                    Partial
                  @elseif($transfer->status == 3)
                    Completed
                  @endif
                </b>
            </p>
            <p style="font-size:14px;margin:0 0 6px 0;">
                <span style="font-weight:bold;display:inline-block;min-width:146px">@lang('Reference ID') :</span>
                {{ $transfer->invoice_id }}
            </p>
        </td>
      </tr>
      <tr>
        <td style="height:35px;"></td>
      </tr>
      <tr>
        <td style="width:50%;padding:20px;vertical-align:top">
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;">
              <span style="display:block;font-weight:bold;font-size:13px"><strong>@lang('Warehouse (From)'):</strong></span>
            </p>
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;">
                <span style="display:block;font-weight:bold;font-size:13px">@lang('Name') : </span>
                {{ $transfer->warehouse->warehouse_name.'/'.$transfer->warehouse->warehouse_code }}
            </p>
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;">
                <span style="display:block;font-weight:bold;font-size:13px;">@lang('Address') :</span>
                 {{ $transfer->warehouse->address }}</p>
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;">
                <span style="display:block;font-weight:bold;font-size:13px;">@lang('Phone') :</span> {{ $transfer->warehouse->phone }}
            </p>
        </td>

        <td style="width:50%;padding:20px;vertical-align:top">
            <h6 style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">@lang('Business Location(To)')</span> </h6>
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;">
              <span style="display:block;font-weight:bold;font-size:13px;"> @lang('Name') :</span>
              {{ $transfer->branch ? $transfer->branch->name.'/'.$transfer->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}
            </p>
            <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span style="display:block;font-weight:bold;font-size:13px;">@lang('Phone') :</span> {{ $transfer->branch ? $transfer->branch->phone : json_decode($generalSettings->business, true)['phone'] }}</p>

            <p style="margin:0 0 10px 0;padding:0;font-size:14px;">
              <span style="display:block;font-weight:bold;font-size:13px;">@lang('Address') :</span>
              @if ($transfer->branch)
                  {{ $transfer->branch->city }},
                  {{ $transfer->branch->state }},
                  {{ $transfer->branch->zip_code }},
                  {{ $transfer->branch->country }}.
              @else
                  {{ json_decode($generalSettings->business, true)['address'] }}
              @endif
            </p>
        </td>

      </tr>
      <tr>
        <td colspan="2" style="font-size:20px;padding:30px 15px 0 15px;">@lang('Description')</td>
      </tr>
      @foreach ($transfer->transfer_products as $transfer_product)
        @php
            $panding_qty = $transfer_product->quantity - $transfer_product->received_qty;
        @endphp
        <tr>
            <td colspan="2" style="padding:15px;">
                <p style="font-size:14px;margin:0;padding:10px;border:solid 1px #ddd;font-weight:bold;">
                <span style="display:block;font-size:13px;font-weight:normal;">
                  @php
                    $variant = $transfer_product->variant ? ' ('.$transfer_product->variant->variant_name.')' : '';
                  @endphp
                  {{ $transfer_product->product->name.$variant }}
                </span>Send Stock- {{ $transfer_product->quantity.' ('.$transfer_product->unit.')' }}
                    <b style="font-size:12px;font-weight:300;"> /Pending Qty-{{ bcadd($panding_qty, 0, 2).' ('.$transfer_product->unit.')' }}/Receive Qty-{{ $transfer_product->received_qty.' ('.$transfer_product->unit.')' }}</b>
                </p>
            </td>
        </tr>
      @endforeach
    </tbody>
    @if ($mail_note)
      <tfoot>
        <tr>
          <td colspan="2" style="font-size:14px;padding:50px 15px 0 15px;">
            <strong style="display:block;margin:0 0 10px 0;">@lang('Mail Note'): </strong> <br>
                {{ $mail_note }}
              <br>
          </td>
        </tr>
      </tfoot>
    @endif
  </table>
</body>
</html>
