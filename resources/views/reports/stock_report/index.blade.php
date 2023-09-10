@extends('layout.master')
@push('stylesheets')
    <style>
        .sale_and_purchase_amount_area table tbody tr th,td {color: #32325d;}
        .report_data_area {position: relative;}
        .data_preloader{top:2.3%}
        .sale_and_purchase_amount_area table tbody tr th{text-align: left;}
        .sale_and_purchase_amount_area table tbody tr td{text-align: left;}
    </style>
    <link href="/assets/css/tab.min.css" rel="stylesheet" type="text/css"/>
@endpush
{{-- @section('title', 'Stock Report - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-desktop"></span>
                                <h5>@lang('Stock Report')</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="card">
                                <div class="tab_list_area">
                                    <ul class="list-unstyled">
                                        <li>
                                            <a id="tab_btn" data-show="branch_stock" class="tab_btn tab_active" href="#"><i class="fas fa-info-circle"></i> @lang('Business Location Stock')</a>
                                        </li>

                                        <li>
                                            <a id="tab_btn" data-show="warehouse_stock" class="tab_btn" href="#">
                                            <i class="fas fa-scroll"></i> @lang('Warehouse Stock')</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="tab_contant branch_stock">
                            <div class="row">
                                <div class="card py-2">
                                    <div class="col-md-12">
                                        <form id="branch_stock_filter_form" class="px-2">
                                            @csrf
                                            <div class="form-group row">
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
                                                    @endif
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Category') :</strong></label>
                                                    <select id="category_id" name="category_id" class="form-control">
                                                        <option value="">@lang('All')</option>
                                                        @foreach ($categories as $c)
                                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Brand') :</strong></label>
                                                    <select id="brand_id" name="brand_id" class="form-control">
                                                        <option value="">@lang('All')</option>
                                                        @foreach ($brands as $b)
                                                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Unit') :</strong></label>
                                                    <select id="unit_id" name="unit_id" class="form-control">
                                                        <option value="">@lang('All')</option>
                                                        @foreach ($units as $u)
                                                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Tax') :</strong></label>
                                                    <select id="tax_id" name="tax_id" class="form-control">
                                                        <option value="">@lang('All')</option>
                                                        @foreach ($taxes as $t)
                                                            <option value="{{ $t->id }}">{{ $t->tax_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label><strong></strong></label>
                                                            <div class="input-group">
                                                                <button type="submit" id="filter_button" class="btn text-white btn-sm btn-secondary float-start"><i class="fas fa-funnel-dollar"></i> @lang('Filter')</button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label></label>
                                                            <div class="input-group">
                                                                <a href="#" class="btn btn-sm btn-primary float-end" id="branch_stock_print_report"><i class="fas fa-print "></i> @lang('Print')</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin_row mt-1">
                                <div class="card">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6>
                                    </div>
                                    <div class="table-responsive" id="data_list1">
                                        <table class="display data_tbl data__table b_data_tbl">
                                            <thead>
                                                <tr class="text-start">
                                                    <th>@lang('P.Code')</th>
                                                    <th>@lang('Product')</th>
                                                    <th>@lang('Business Location')</th>
                                                    <th>@lang('Unit Price')</th>
                                                    <th>@lang('Current Stock')</th>
                                                    <th>@lang('Stock Value') <b><small>(@lang('By Unit Cost'))</small></b></th>
                                                    <th>@lang('Total Sold')</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th class="text-white text-end" colspan="3">@lang('Total') : </th>
                                                    <th class="text-white text-end">---</th>
                                                    <th class="text-white text-end" id="stock"></th>
                                                    <th class="text-white text-end" id="stock_value"></th>
                                                    <th class="text-white text-end" id="total_sale"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab_contant warehouse_stock">
                            <div class="row">
                                <div class="card py-2">
                                    <div class="col-md-12">
                                        <form id="warehouse_stock_filter_form" class="px-2">
                                            @csrf
                                            <div class="form-group row">
                                                @if ($addons->branches == 1)
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-2">
                                                            <label><strong>@lang('Business Location') :</strong></label>
                                                            <select name="branch_id" class="form-control" id="w_branch_id" autofocus>
                                                                <option value="">@lang('All')</option>
                                                                <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">
                                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                @endif

                                                <div class="col-md-2">
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <label><strong>@lang('Warehouse') :</strong></label>
                                                        <select name="warehouse_id" class="form-control submit_able" id="warehouse_id" autofocus>
                                                            <option value="">@lang('Select Business Location First')</option>
                                                        </select>
                                                    @else
                                                        @php
                                                            $wh = DB::table('warehouse_branches')
                                                                ->where('warehouse_branches.branch_id', auth()->user()->branch_id)
                                                                ->orWhere('warehouse_branches.is_global', 1)
                                                                ->leftJoin('warehouses', 'warehouse_branches.warehouse_id', 'warehouses.id')
                                                                ->select(
                                                                    'warehouses.id',
                                                                    'warehouses.warehouse_name',
                                                                    'warehouses.warehouse_code',
                                                                )->get();
                                                        @endphp

                                                        <label><strong>@lang('Warehouse') :</strong></label>
                                                        <select name="warehouse_id" class="form-control submit_able" id="warehouse_id" autofocus>
                                                            <option value="">@lang('All')</option>
                                                            @foreach ($wh as $row)
                                                                <option value="{{ $row->id }}">{{ $row->warehouse_name.'/'.$row->warehouse_code }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Category') :</strong></label>
                                                    <select id="w_category_id" name="category_id" class="form-control common_submitable">
                                                        <option value="">@lang('All')</option>
                                                        @foreach ($categories as $c)
                                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Brand') :</strong></label>
                                                    <select id="w_brand_id" name="brand_id" class="form-control common_submitable">
                                                        <option value="">@lang('All')</option>
                                                        @foreach ($brands as $b)
                                                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Unit') :</strong></label>
                                                    <select id="w_unit_id" name="unit_id" class="form-control common_submitable">
                                                        <option value="">@lang('All')</option>
                                                        @foreach ($units as $u)
                                                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Tax') :</strong></label>
                                                    <select id="w_tax_id" name="tax_id" class="form-control common_submitable">
                                                        <option value="">@lang('All')</option>
                                                        @foreach ($taxes as $t)
                                                            <option value="{{ $t->id }}">{{ $t->tax_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-2 offset-8">
                                                    <a href="#" class="btn btn-sm btn-primary float-end mt-1" id="w_print_report"><i class="fas fa-print"></i> @lang('Print')</a>
                                                </div>

                                                <div class="col-md-2">
                                                    <button type="submit" id="filter_button" class="btn text-white btn-sm btn-secondary mt-1 float-end"><i class="fas fa-funnel-dollar"></i> @lang('Filter')</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin_row mt-1">
                                <div class="card">
                                    <div class="data_preloader" id="w_data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6></div>
                                    <div class="table-responsive" id="data_list">
                                        <table class="display data_tbl data__table w_data_tbl w-100">
                                            <thead>
                                                <tr class="text-start">
                                                    <th>@lang('P.Code')</th>
                                                    <th>@lang('Product')</th>
                                                    <th>@lang('Business Location')</th>
                                                    <th>@lang('Warehouse')</th>
                                                    <th>@lang('Unit Price')</th>
                                                    <th>@lang('Current Stock')</th>
                                                    <th>@lang('Current Stock Value') <b><small>((@lang('By Unit Cost')))</small></b></th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tr class="bg-secondary">
                                                <th class="text-white text-end" colspan="4">@lang('Total') : </th>
                                                <th class="text-white text-end">---</th>
                                                <th class="text-white text-end" id="w_stock"></th>
                                                <th class="text-white text-end" id="w_stock_value"></th>
                                            </tr>
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
@endsection
@push('scripts')

<script>
    var branch_stock_table = $('.b_data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary'},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary'}
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
        "ajax": {
            "url": "{{ route('reports.stock.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.category_id = $('#category_id').val();
                d.brand_id = $('#brand_id').val();
                d.unit_id = $('#unit_id').val();
                d.tax_id = $('#tax_id').val();
            }
        },
        columnDefs: [{
            "targets": [4, 5, 6],
            "orderable": false,
            "searchable": false
        }],
        columns: [
            { data: 'product_code', name: 'products.product_code' },
            { data: 'name', name: 'products.name'},
            { data: 'branch', name: 'branches.name'},
            { data: 'price', name: 'products.product_price' , className : 'text-end'},
            { data: 'stock', name: 'stock' , className : 'text-end'},
            { data: 'stock_value', name: 'stock_value' , className : 'text-end'},
            { data: 'total_sale', name: 'total_sale', className : 'text-end' },

        ],fnDrawCallback: function() {
            var stock = sum_table_col($('.b_data_tbl'), 'stock');
            $('#stock').text(bdFormat(stock));
            var stock_value = sum_table_col($('.b_data_tbl'), 'stock_value');
            $('#stock_value').text(bdFormat(stock_value));
            var total_sale = sum_table_col($('.b_data_tbl'), 'total_sale');
            $('#total_sale').text(bdFormat(total_sale));
            $('.data_preloader').hide();
        },
    });

    $(document).on('submit', '#branch_stock_filter_form', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        branch_stock_table.ajax.reload();
    });

    var warehouse_stock_table = $('.w_data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary'},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary'}
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
        "ajax": {
            "url": "{{ route('reports.stock.warehouse.stock') }}",
            "data": function(d) {
                d.branch_id = $('#w_branch_id').val();
                d.warehouse_id = $('#warehouse_id').val();
                d.category_id = $('#w_category_id').val();
                d.brand_id = $('#w_brand_id').val();
                d.unit_id = $('#w_unit_id').val();
                d.tax_id = $('#w_tax_id').val();
            }
        },
        columnDefs: [{
            "targets": [4, 5, 6],
            "orderable": false,
            "searchable": false
        }],
        columns: [
            { data: 'product_code', name: 'products.product_code' },
            { data: 'name', name: 'products.name'},
            { data: 'branch', name: 'branches.name'},
            { data: 'warehouse', name: 'warehouses.warehouse_name'},
            { data: 'price', name: 'products.product_price' , className : 'text-end'},
            { data: 'stock', name: 'stock' , className : 'text-end'},
            { data: 'stock_value', name: 'stock_value' , className : 'text-end'},

        ],fnDrawCallback: function() {
            var stock = sum_table_col($('.w_data_tbl'), 'stock');
            $('#w_stock').text(bdFormat(stock));
            var stock_value = sum_table_col($('.w_data_tbl'), 'stock_value');
            $('#w_stock_value').text(bdFormat(stock_value));
            $('#w_data_preloader').hide();
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

    $(document).on('submit', '#warehouse_stock_filter_form', function (e) {
        e.preventDefault();
        $('#w_data_preloader').show();
        warehouse_stock_table.ajax.reload();
    });

    $(document).on('change', '#w_branch_id', function () {
        var branch_id = $(this).val();
        $.ajax({
            url:"{{ url('common/ajax/call/branch/warehouse') }}"+"/"+branch_id,
            type:'get',
            success:function(data){

                $('#warehouse_id').empty();
                $('#warehouse_id').append('<option value="">@lang('All')</option>');
                $.each(data, function (key, val) {

                    $('#warehouse_id').append('<option value="'+val.id+'">'+val.warehouse_name+'/'+val.warehouse_code+'</option>');
                });
            }
        });
    })

    $(document).on('click', '.tab_btn', function(e) {
        e.preventDefault();
        $('.tab_btn').removeClass('tab_active');
        $('.tab_contant').hide();
        var show_content = $(this).data('show');
        $('.' + show_content).show();
        $(this).addClass('tab_active');
    });

        //Print purchase Payment report
    $(document).on('click', '#branch_stock_print_report', function (e) {
        e.preventDefault();

        var url = "{{ route('reports.stock.print.branch.stock') }}";

        var branch_id = $('#branch_id').val();
        var category_id = $('#category_id').val();
        var brand_id = $('#brand_id').val();
        var unit_id = $('#unit_id').val();
        var tax_id = $('#tax_id').val();

        $.ajax({
            url:url,
            type:'get',
            data: {branch_id, category_id, brand_id, unit_id, tax_id},
            success:function(data){
                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
                    removeInline: false,
                    printDelay: 1000,
                });
            }
        });
    });
</script>


@endpush
