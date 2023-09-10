@php
$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Print Barcode')</title>
    <link rel="stylesheet" href="{{ asset('backend/asset/css/bootstrap.min.css') }}">

    <style>
        p {margin: 0px;padding: 0px;font-size: 7px;}
        p.sku {font-size: 7px;margin: 0px;padding: 0;font-weight: 700;margin-bottom: 1px;}
        .company_name {margin: 0;}
        .div {page-break-after: always;}
        .company_name {font-size: 10px !important;font-weight: bolder;margin: 0;padding: 0;}
        .barcode {margin-bottom: -2px;}

        @page {
            /* size: auto; */
            .print_area: {
                height: 100%;
                width: 100%;
            }

            /* size: {{ $br_setting->paper_width }}in {{ $br_setting->paper_height }}in; */
            size: 38mm 25mm;
            margin: 5px 0px;
            /* margin: 0mm 15mm 0mm 15mm; */
        }


        html {
            /* background-color: #FFFFFF; */
            margin: 0px;
            /* this affects the margin on the html before sending to printer */
        }

        body {
            /* border: solid 1px blue; */
            /* margin: 0mm 15mm 0mm 15mm; */
            /* margin you want for the content */
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        .product_name {font-size: 9px;font-weight: 600;}
        .product_price {font-size: 10px;letter-spacing: 0px !important;}
        .product_code {font-size: 10px;font-weight: 600;}
        th {padding: 0px;letter-spacing: 1px;}

    </style>
</head>

<body>
    <div class="print_area">
        @if ($br_setting->is_continuous == 1)
            @php $index = 0; @endphp
            @foreach ($req->product_ids as $product)

                @php
                    $qty = isset($req->left_qty[$index]) ? $req->left_qty[$index] : 0;
                    $barcodeType = isset($req->barcode_type[$index]) ? isset($req->barcode_type[$index]) : 'code128';
                @endphp
                @for ($i = 0; $i < $qty; $i++)
                    <div class="row justify-content-center div justify-center">
                        <div class="barcode_area text-center" style="margin-bottom: {{ $br_setting->top_margin }}in;">
                            <div class="barcode">
                                <div class="company_name row">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="company_name">
                                                    @if (isset($req->is_business_name))
                                                        {{ auth()->user()->branch ? auth()->user()->branch->name : json_decode($generalSettings->business, true)['shop_name'] }}
                                                    @endif
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>

                                <div class="row justify-content-center">
                                    <img style="width: 45mm; height:7mm;"
                                        src="data:image/png;base64,{{ base64_encode($generator->getBarcode($req->product_code[$index], $generator::TYPE_CODE_128)) }}">
                                </div>

                                <div class="row justify-content-center">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="product_code">
                                                    {{ $req->product_code[$index] }}
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="product_details_area row">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="product_name">
                                                @if (isset($req->is_product_name))
                                                    {{-- @php
                                                        $variant = isset($req->is_product_variant) ? isset($req->product_variant[$index]): '';
                                                    @endphp --}}

                                                   @if(isset($req->is_product_variant))
                                                       @if(isset($req->product_variant[$index]))

                                                        @php
                                                            $variant=$req->product_variant[$index]
                                                        @endphp

                                                        @else
                                                        @php
                                                            $variant=''
                                                        @endphp

                                                        @endif


                                                    {{ Str::limit($req->product_name[$index] . '' . $variant, 14, '') }}
                                                    :{{ isset($req->is_supplier_prefix) ? $req->supplier_prefix[$index] : '' }}
                                                    @endif
                                                @endif
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="product_price">
                                                @if (isset($req->is_price))
                                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                                    {{ App\Utils\Converter::format_in_bdt($req->product_price[$index]) }}
                                                    {{ isset($req->is_tax) ? '+ ' . $req->product_tax[$index] . '% VAT' : '' }}
                                                @endif
                                            </th>
                                        </tr>
                                    </thead>
                                </table>

                            </div>
                        </div>
                    </div>
                @endfor
                @php $index++; @endphp
            @endforeach
        @else
            <div class="row justify-content-center">
                @php $index = 0; @endphp
                @foreach ($req->product_ids as $product)
                    @php $qty = isset($req->left_qty[$index]) ? $req->left_qty[$index] : 0; @endphp
                    @for ($i = 0; $i < $qty; $i++)
                        <div class="barcode_area text-center" style="margin-bottom: {{ $br_setting->top_margin }}in;width:auto;">
                            <div class="barcode">
                                <div class="company_name row">
                                    <small class="p-0 m-0">
                                        <strong>
                                            @if (isset($req->is_business_name))
                                                {{ auth()->user()->branch ? auth()->user()->branch->name : json_decode($generalSettings->business, true)['shop_name'] }}
                                            @endif
                                        </strong>
                                    </small>
                                </div>

                                <div class="row justify-content-center">
                                    <img style="width: 35mm; height:10mm;"
                                        src="data:image/png;base64,{{ base64_encode($generator->getBarcode($req->product_code[$index], $generator::TYPE_CODE_128)) }}">
                                </div>
                                <div class="row justify-content-center">
                                    <p class="sku">{{ $req->product_code[$index] }}</p>
                                </div>
                            </div>

                            <div class="product_details_area row">
                                @if (isset($req->is_product_name))
                                    <p class="pro_details">
                                        @php
                                            $variant = isset($req->is_product_variant) ? $req->product_variant[$index] : '';
                                        @endphp
                                        {{ Str::limit($req->product_name[$index] . ' ' . $variant, 40) }}
                                        :{{ isset($req->is_supplier_prefix) ? $req->supplier_prefix[$index] : '' }}
                                    </p>
                                @endif

                                @if (isset($req->is_price))
                                    <p class="price_details">
                                        <b>Price :
                                            {{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                        {{ bcadd($req->product_price[$index], 0, 2) }}
                                        {{ isset($req->is_tax) ? '+ ' . bcadd($req->product_tax[$index], 0, 2) . '% Tax' : '' }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endfor
                    @php $index++; @endphp
                @endforeach
            </div>
        @endif
    </div>

    {{-- <button class="btn btn-success" onclick="window.print()">@lang('Print')</button> --}}
</body>
<!--Jquery Cdn-->
<script src="{{ asset('backend/asset/cdn/js/jquery-3.6.0.js') }}"></script>
<!--Jquery Cdn End-->
<script>
    function auto_print() {
        window.print();
    }
    setTimeout(function() {
        auto_print();
    }, 300);
</script>

</html>
