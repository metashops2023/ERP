@extends('layout.master')
@push('stylesheets')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        .data_preloader{top:2.3%}
        /* Search Product area style */
        .selectProduct {background-color: #746e70;color: #fff !important;}
        .search_area{position: relative;}
        .search_result {position: absolute;width: 100%;border: 1px solid #E4E6EF;background: white;z-index: 1;padding: 8px;
            margin-top: 1px;}
        .search_result ul li {width: 100%;border: 1px solid lightgray;margin-top: 3px;}
        .search_result ul li a {color: #6b6262;font-size: 12px;display: block;padding: 3px;}
        .search_result ul li a:hover {color: white;background-color: #999396;}
        /* Search Product area style end */
    </style>
@endpush
{{-- @section('title', 'Sold Products - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-shopping-cart"></span>
                                <h5>@lang('Sold Product List')</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <form id="filter_form">
                                            <div class="form-group row">
                                                <div class="col-md-2 search_area">
                                                    <label><strong>@lang('Search Product') :</strong></label>
                                                    <input type="text" name="search_product" id="search_product" class="form-control" placeholder="@lang('Search Product By name')" autofocus autocomplete="off">
                                                    <input type="hidden" name="product_id" id="product_id" value="">
                                                    <input type="hidden" name="variant_id" id="variant_id" value="">
                                                    <div class="search_result d-none">
                                                        <ul id="list" class="list-unstyled">
                                                            <li><a id="select_product" data-p_id="" data-v_id="" href="">@lang('Samsung A')30</a></li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                @if ($addons->branches == 1)
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-2">
                                                            <label><strong>@lang('Business Location') :</strong></label>
                                                            <select name="branch_id" class="form-control submit_able" id="branch_id" autofocus>
                                                                <option value="">@lang('All')</option>
                                                                <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">
                                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @else
                                                        <input type="hidden" name="branch_id" id="branch_id" value="{{ auth()->user()->branch_id }}">
                                                    @endif
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Customer') :</strong></label>
                                                    <select name="customer_id" class="form-control submit_able" id="customer_id" autofocus>
                                                        <option value="">@lang('All')</option>
                                                        <option value="NULL">@lang('Walk-In-Customer')</option>
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}">{{ $customer->name.' ('.$customer->phone.')' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Category') :</strong></label>
                                                    <select name="category_id" class="form-control submit_able"
                                                        id="category_id">
                                                        <option value="">@lang('All')</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">{{$category->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Sub-Category') :</strong></label>
                                                    <select name="sub_category_id" class="form-control" id="sub_category_id">
                                                        <option value="">@lang('All')</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Sold By') :</strong></label>
                                                    <select name="sold_by" id="sold_by" class="form-control">
                                                        <option value="">@lang('All')</option>
                                                        <option value="1">@lang('Add Sale')</option>
                                                        <option value="2">@lang('POS')</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-2">
                                                    <label><strong>@lang('From Date') :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_i"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="datepicker"
                                                            class="form-control from_date"
                                                            autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('To Date') :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_i"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="datepicker2"
                                                            class="form-control to_date"
                                                            autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong></strong></label>
                                                    <div class="input-group">
                                                        <button type="button" id="filter_button" class="btn text-white btn-sm btn-secondary float-start"><i class="fas fa-funnel-dollar"></i> @lang('Filter')</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row margin_row mt-1">
                            <div class="card">
                                <div class="section-header">
                                    <div class="col-md-10">
                                        <h6>@lang('Sold Product List')</h6>
                                    </div>
                                    @if (auth()->user()->permission->purchase['purchase_add'] == '1')
                                        <div class="col-md-2">
                                            <a href="#" class="btn btn-sm btn-primary float-end" id="print_report"><i class="fas fa-print"></i> @lang('Print')</a>
                                        </div>
                                    @endif
                                </div>

                                <div class="widget_content">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6>
                                    </div>
                                    <div class="table-responsive" id="data-list">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th>@lang('Date')</th>
                                                    <th>@lang('Product')</th>
                                                    <th>@lang('P.Code')</th>
                                                    <th>@lang('Customer')</th>
                                                    <th>@lang('Invoice ID')</th>
                                                    <th>@lang('Sold By')</th>
                                                    <th>@lang('Quantity')</th>
                                                    <th>@lang('Unit Price')({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                    <th>@lang('Subtotal')({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="6" class="text-end text-white">@lang('Total') :{{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                                    <th class="text-white">(<span id="total_qty"></span>)</th>
                                                    <th class="text-white">---</th>
                                                    <th class="text-white"> <span id="total_subtotal"></span></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="sale_details"></div>
@endsection
@push('scripts')
<script src="/assets/plugins/custom/select_li/selectli.js"></script>
<script type="text/javascript" src="/assets/plugins/custom/moment/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary'},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary'},
        ],
        "processing": true,
        "serverSide": true,
        language: {
                search: "@lang('Search')",
                emptyTable: "@lang('EmptyTable')",
                infoEmpty: "@lang('EmptyTable')",
                sInfo: "@lang('Showing _START_ to _END_ of _TOTAL_ entries')",
                sInfoEmpty: "@lang('Showing 0 to 0 of 0 entries')",
                sLengthMenu: "@lang('Show _MENU_ entries')",
                paginate: {
                    next: "@lang('Next')",
                    previous: "@lang('Previous')"

                },
            },
        "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        //aaSorting: [[0, 'desc']],
        "ajax": {
            "url": "{{ route('sales.product.list') }}",
            "data": function(d) {
                d.product_id = $('#product_id').val();
                d.variant_id = $('#variant_id').val();
                d.branch_id = $('#branch_id').val();
                d.category_id = $('#category_id').val();
                d.sub_category_id = $('#sub_category_id').val();
                d.customer_id = $('#customer_id').val();
                d.from_date = $('.from_date').val();
                d.to_date = $('.to_date').val();
                d.sold_by = $('#sold_by').val();
            }
        },
        columns: [
            {data: 'date', name: 'sales.date'},
            {data: 'product', name: 'products.name'},
            {data: 'sku', name: 'products.product_code'},
            {data: 'customer', name: 'customers.name'},
            {data: 'invoice_id', name: 'sales.invoice_id'},
            {data: 'sold_by', name: 'sales.created_by', className:'text-end'},
            {data: 'quantity', name: 'quantity', className:'text-end'},
            {data: 'unit_price_inc_tax', name: 'unit_price_inc_tax', className:'text-end'},
            {data: 'subtotal', name: 'subtotal', className:'text-end'},
        ],fnDrawCallback: function() {
            var total_qty = sum_table_col($('.data_tbl'), 'qty');
            $('#total_qty').text(bdFormat(total_qty));
            var total_subtotal = sum_table_col($('.data_tbl'), 'subtotal');
            $('#total_subtotal').text(bdFormat(total_subtotal));
            $('.data_preloader').hide();
        },
    });

    function sum_table_col(table, class_name) {
        var sum = 0;
        table.find('tbody').find('tr').each(function() {
            if (parseFloat($(this).find('.' + class_name).data('value'))) {
                sum += parseFloat(
                    $(this).find('.' + class_name).data('value')
                );
            }
        });
        return sum;
    }

    $('#category_id').on('change', function() {
        var category_id = $(this).val();
        $.get("{{ url('product/all/sub/category/') }}"+"/"+category_id, function(subCategories) {
            $('#sub_category_id').empty();
            $('#sub_category_id').append('<option value="">@lang('Select Sub-Category')</option>');
            $.each(subCategories, function(key, val) {
                $('#sub_category_id').append('<option value="' + val.id + '">' + val.name + '</option>');
            });
        });
    });

    //Submit filter form by select input changing
    $(document).on('click', '#filter_button', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        table.ajax.reload();
    });

    //Submit filter form by date-range field blur
    $(document).on('click', '#search_product', function () {
        $(this).val('');
        $('#product_id').val('');
        $('#variant_id').val('');
    });

    $('#search_product').on('input', function () {
        $('.search_result').hide();

        var product_name = $(this).val();

        if (product_name === '') {

            $('.search_result').hide();
            $('#product_id').val('');
            $('#variant_id').val('');
            return;
        }

        var url = "{{ route('common.ajax.call.search.products.only.for.report.filter', ':product_name') }}";
        var route = url.replace(':product_name', product_name);

        $.ajax({
            url : route,
            async : true,
            type : 'get',
            success : function(data){

                if (!$.isEmptyObject(data.noResult)) {

                    $('.search_result').hide();
                } else {

                    $('.search_result').show();
                    $('#list').html(data);
                }
            }
        });
    });

    $(document).on('click', '#select_product', function (e) {
        e.preventDefault();
        var product_name = $(this).html();
        $('#search_product').val(product_name.trim());
        var product_id = $(this).data('p_id');
        var variant_id = $(this).data('v_id');
        $('#product_id').val(product_id);
        $('#variant_id').val(variant_id);
        $('.search_result').hide();
    });

    $('body').keyup(function(e){
        e.preventDefault();
        if (e.keyCode == 13 || e.keyCode == 9) {
            $(".selectProduct").click();
            $('.search_result').hide();
            $('#list').empty();
        }
    });

    // Show details modal with data
    $(document).on('click', '.details_button', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.get(url, function(data) {

            $('#sale_details').html(data);
            $('.data_preloader').hide();
            $('#detailsModal').modal('show');
        });
    });

    $(document).on('mouseenter', '#list>li>a',function () {
        $('#list>li>a').removeClass('selectProduct');
        $(this).addClass('selectProduct');
    });

    //Print purchase report
    $(document).on('click', '#print_report', function (e) {
        e.preventDefault();
        var url = "{{ route('reports.product.sales.print') }}";
        var branch_id = $('#branch_id').val();
        var product_id = $('#product_id').val();
        var variant_id = $('#variant_id').val();
        var customer_id = $('#customer_id').val();
        var from_date = $('from_date').val();
        var to_date = $('to_date').val();
        $.ajax({
            url:url,
            type:'get',
            data: {branch_id, product_id, customer_id, variant_id, from_date, to_date},
            success:function(data){
                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                    removeInline: false,
                    printDelay: 700,
                    header: null,
                });
            }
        });
    });

        // Make print
    $(document).on('click', '.print_btn',function (e) {
        e.preventDefault();
        var body = $('.sale_print_template').html();
        var header = $('.heading_area').html();
        $(body).printThis({
            debug: false,
            importCSS: true,
            importStyle: true,
            loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
            removeInline: false,
            printDelay: 500,
            header : null,
            footer : null,
        });
    });

    $(document).on('click', '.print_challan_btn',function (e) {
        e.preventDefault();
        var body = $('.challan_print_template').html();
        var header = $('.heading_area').html();
        $(body).printThis({
            debug: false,
            importCSS: true,
            importStyle: true,
            loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
            removeInline: false,
            printDelay: 800,
            header: null,
            footer: null,
        });
    });

    // Print Packing slip
    $(document).on('click', '#print_packing_slip', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(data){
                $('.data_preloader').hide();
                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
                    removeInline: false,
                    printDelay: 700,
                    header: null,
                });
            }
        });
    });
</script>

<script type="text/javascript">
    new Litepicker({
        singleMode: true,
        element: document.getElementById('datepicker'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY'
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('datepicker2'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY'
    });
</script>
@endpush
