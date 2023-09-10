@extends('layout.master')
@push('stylesheets')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
{{-- @section('title', 'Customer List - ') --}}
@section('content')
<div class="body-woaper">
    <div class="container-fluid">
        <div class="row">
            <div class="border-class">
                <div class="main__content">
                    <div class="sec-name">
                        <div class="name-head">
                            <span class="fas fa-people-arrows"></span>
                            <h5>@lang('Customers')</h5>
                        </div>
                    </div>

                    @if ($addons->branches == 1)
                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="sec-name">
                                <div class="col-md-12">
                                    <form id="filter_form" class="px-2">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <label><strong>@lang('Business Location') :</strong></label>
                                                <select name="branch_id" class="form-control submit_able" id="branch_id" autofocus>
                                                    <option value="">@lang('All')</option>
                                                    <!-- <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option> -->
                                                    @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">
                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label><strong></strong></label>
                                                <div class="input-group">
                                                    <button type="submit" class="btn text-white btn-sm btn-secondary float-start"><i class="fas fa-funnel-dollar"></i> @lang('Filter')</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>

                <div class="row margin_row mt-1">
                    <div class="card">
                        <div class="section-header">
                            <div class="col-md-6">
                                <h6>@lang('All Customer')</h6>
                            </div>

                            <div class="col-md-6">
                                <div class="btn_30_blue float-end">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-square"></i> @lang('Add')</a>
                                </div>

                                <div class="btn_30_blue float-end">
                                    <a href="{{ route('contacts.customers.import.create') }}"><i class="fas fa-plus-square"></i> @lang('Import Customers')</a>
                                </div>

                                <div class="btn_30_blue float-end">
                                    <a href="#" class="print_report"><i class="fas fa-print"></i> @lang('Print All')</a>
                                </div>

                            </div>
                        </div>
                        <div class="widget_content">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner"></i> @lang('Processing')...</h6>
                            </div>
                            <div class="form-group" style="padding: 5px;">
                                <div class="custom-control custom-switch pull-right">
                                    <input class="custom-control-input" type="checkbox" id="chkActive">
                                    <label for="chkActive"
                                        class="custom-control-label">{{ __('Show Cancelled') }}</label>
                                </div>
                            </div>
                            <div class="table-responsive" id="data-list">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr class="text-start">
                                            <th>@lang('Customer ID')</th>
                                            <th>@lang('Name')</th>
                                            <th>@lang('Business')</th>
                                            <th>@lang('Phone')</th>
                                            <th>@lang('Group')</th>
                                            <th>@lang('Credit Limit')</th>
                                            <th>@lang('Opening Balance')</th>
                                            <th>@lang('Total Sale')</th>
                                            <th>@lang('Total Paid')</th>
                                            <th>@lang('Sale Due')</th>
                                            <th>@lang('Total Return')</th>
                                            <th>@lang('Return Due')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Actions')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr class="bg-secondary">
                                            <th colspan="7" class="text-white text-end">@lang('Total') : ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                            <th id="opening_balance" class="text-white text-end"></th>
                                            <th id="total_sale" class="text-white text-end"></th>
                                            <th id="total_paid" class="text-white text-end"></th>
                                            <th id="total_sale_due" class="text-white text-end"></th>
                                            <th id="total_return" class="text-white text-end"></th>
                                            <th id="total_sale_return_due" class="text-white text-end"></th>
                                            <th id="total_sale_return_due" class="text-white text-start">---</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <form id="deleted_form" action="" method="post">
                            @method('DELETE')
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">@lang('Add Customer')</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <!--begin::Form-->
                <form id="add_customer_form" action="{{ route('contacts.customer.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row mt-1">
                        <div class="col-md-3">
                            <label><strong>@lang('Name') :</strong> <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control add_input" data-name="Customer name" id="name" placeholder="@lang('Customer name')" />
                            <span class="error error_name"></span>
                        </div>

                        <div class="col-md-3">
                            <label><strong>@lang('Phone') :</strong> <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control add_input" data-name="Phone number" id="phone" placeholder="@lang('Phone number')" />
                            <span class="error error_phone"></span>
                        </div>

                        <div class="col-md-3">
                            <label><strong>@lang('Customer ID') :</strong> <i data-bs-toggle="tooltip" data-bs-placement="right" title="Leave empty to auto generate." class="fas fa-info-circle tp"></i></label>
                            <input type="text" name="contact_id" class="form-control" placeholder="@lang('Customer ID')" />
                        </div>

                        <div class="col-md-3">
                            <label><strong>@lang('Business Name') :</strong></label>
                            <select name="add_branch_id" id="add_branch_id">
                                @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">
                                    {{ $branch->name . '/' . $branch->branch_code }}
                                </option>
                                @endforeach
                            </select>
                            <!-- <input type="text" name="business_name" class="form-control" placeholder="@lang('Business name')" /> -->
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-md-3">
                            <label><strong>@lang('Alternative Number') :</strong> </label>
                            <input type="text" name="alternative_phone" class="form-control" placeholder="@lang('Alternative phone number')" />
                        </div>

                        <div class="col-md-3">
                            <label><strong>@lang('Landline') :</strong></label>
                            <input type="text" name="landline" class="form-control" placeholder="@lang('landline number')" />
                        </div>

                        <div class="col-md-3">
                            <label><strong>@lang('Email') :</strong></label>
                            <input type="text" name="email" class="form-control" placeholder="@lang('Email address')" />
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-md-3">
                            <label><strong>@lang('Tax Number') :</strong></label>
                            <input type="text" name="tax_number" class="form-control" placeholder="@lang('Tax number')" />
                        </div>

                        <div class="col-md-3">
                            <label><strong>@lang('Opening Balance') :</strong> <i data-bs-toggle="tooltip" data-bs-placement="right" title="Opening balance will be added in this customer due." class="fas fa-info-circle tp"></i></label>
                            <input type="number" step="any" name="opening_balance" class="form-control" placeholder="@lang('Opening balance')" value="0.00" />
                        </div>

                        <div class="col-md-3">
                            <label><strong>@lang('Credit Limit') :</strong> <i data-bs-toggle="tooltip" data-bs-placement="right" title="If there is no credit limit of this customer, so leave this field empty." class="fas fa-info-circle tp"></i></label>
                            <input type="number" step="any" name="credit_limit" class="form-control" placeholder="@lang('Credit Limit')" value="" />
                        </div>

                        <div class="col-md-3">
                            <label><strong>@lang('Pay Term') :</strong> </label>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5">
                                        <input type="text" name="pay_term_number" class="form-control" placeholder="@lang('Number')" />
                                    </div>

                                    <div class="col-md-7">
                                        <select name="pay_term" class="form-control">
                                            <option value="1">@lang('Select term')</option>
                                            <option value="2">@lang('Days') </option>
                                            <option value="3">@lang('Months')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-md-3">
                            <label><strong>@lang('Customer Group') :</strong> </label>
                            <select name="customer_group_id" class="form-control" id="customer_group_id">
                                <option value="">@lang('None')</option>
                                @foreach ($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label><strong>@lang('Date Of Birth') :</strong></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="fas fa-calendar-week input_f"></i>
                                    </span>
                                </div>
                                <input type="text" name="date_of_birth" id="date_of_birth" class="form-control" autocomplete="off" placeholder="@lang('YYYY-MM-DD')">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label><strong>@lang('Address') :</strong> </label>
                            <input type="text" name="address" class="form-control" placeholder="@lang('Address')">
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-md-3">
                            <label><strong>@lang('City') :</strong> </label>
                            <input type="text" name="city" class="form-control" placeholder="@lang('City')" />
                        </div>

                        <div class="col-md-3">
                            <label><strong>@lang('State') :</strong> </label>
                            <input type="text" name="state" class="form-control" placeholder="@lang('State')" />
                        </div>

                        <div class="col-md-3">
                            <label><strong>@lang('Country') :</strong> </label>
                            <input type="text" name="country" class="form-control" placeholder="@lang('Country')" />
                        </div>

                        <div class="col-md-3">
                            <label><strong>@lang('Zip-Code') :</strong> </label>
                            <input type="text" name="zip_code" class="form-control" placeholder="@lang('zip_code')" />
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-md-5">
                            <label><strong>@lang('Shipping Address') :</strong> </label>
                            <input type="text" name="shipping_address" class="form-control" placeholder="@lang('Shipping address')" />
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                            <button type="submit" class="c-btn button-success me-0 float-end submit_button">@lang('Save')</button>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">@lang('Edit Customer')</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body" id="edit-modal-form-body"></div>
        </div>
    </div>
</div>

<!-- Money Receipt list Modal-->
<div class="modal fade" id="moneyReceiptListModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog col-60-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">@lang('Payment Receipt Voucher List')</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body" id="receipt_voucher_list_modal_body"></div>
        </div>
    </div>
</div>
<!-- Money Receipt list Modal End-->

<!--add money receipt Modal-->
<div class="modal fade" id="MoneyReciptModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog col-60-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">@lang('Generate Money Receipt Voucher')</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body" id="money_receipt_modal"></div>
        </div>
    </div>
</div>
<!--add money receipt Modal End-->

<!--add money receipt Modal-->
<div class="modal fade" id="changeReciptStatusModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
</div>
<!--add money receipt Modal End-->
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
     $('#chkActive').change(function() {
            table.draw();
        });
    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: [3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> Pdf',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: [3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                }
            },
        ],
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
            ajax: {
                url: "{{ route('contacts.customer.index') }}",
                data: function(d) {
                       d.branch_id = $('#branch_id').val();
                    d = Object.assign(d, {
                        active: $("#chkActive").is(':checked'),
                    });
                },
            },
        "processing": true,
        "serverSide": true,
        aaSorting: [
            [0, 'asc']
        ],
        "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        // "ajax": {
        //     "url": "{{ route('contacts.customer.index') }}",
        //     "data": function(d) {
        //         d.branch_id = $('#branch_id').val();
        //     }
        // },
        columnDefs: [{
            "targets": [0, 7],
            "orderable": false,
            "searchable": false
        }],
        columns: [
            {
                data: 'contact_id',
                name: 'contact_id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'business_name',
                name: 'business_name'
            },
            {
                data: 'phone',
                name: 'phone'
            },
            {
                data: 'group_name',
                name: 'customer_groups.group_name'
            },
            {
                data: 'credit_limit',
                name: 'credit_limit'
            },
            {
                data: 'opening_balance',
                name: 'opening_balance',
                className: 'text-end'
            },
            {
                data: 'total_sale',
                name: 'total_sale',
                className: 'text-end'
            },
            {
                data: 'total_paid',
                name: 'total_paid',
                className: 'text-end'
            },
            {
                data: 'total_sale_due',
                name: 'total_sale_due',
                className: 'text-end'
            },
            {
                data: 'total_return',
                name: 'total_return',
                className: 'text-end'
            },
            {
                data: 'total_sale_return_due',
                name: 'total_sale_return_due',
                className: 'text-end'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'action',
                name: 'action'
            },
        ],
        fnDrawCallback: function() {

            var opening_balance = sum_table_col($('.data_tbl'), 'opening_balance');
            $('#opening_balance').text(bdFormat(opening_balance));
            var total_sale = sum_table_col($('.data_tbl'), 'total_sale');
            $('#total_sale').text(bdFormat(total_sale));
            var total_sale_due = sum_table_col($('.data_tbl'), 'total_sale_due');
            $('#total_sale_due').text(bdFormat(total_sale_due));
            var total_paid = sum_table_col($('.data_tbl'), 'total_paid');
            $('#total_paid').text(bdFormat(total_paid));
            var total_return = sum_table_col($('.data_tbl'), 'total_return');
            $('#total_return').text(bdFormat(total_return));
            var total_sale_return_due = sum_table_col($('.data_tbl'), 'total_sale_return_due');
            $('#total_sale_return_due').text(bdFormat(total_sale_return_due));

            $('.data_preloader').hide();
        }
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

    //Submit filter form by select input changing
    $(document).on('submit', '#filter_form', function(e) {
        e.preventDefault();
        $('.data_preloader').show();
        table.ajax.reload();
    });

    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // call jquery method
    $(document).ready(function() {
        // Add category by ajax
        $('#add_customer_form').on('submit', function(e) {
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.add_input');
            $('.error').html('');
            var countErrorField = 0;

            $.each(inputs, function(key, val) {

                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val();

                if (idValue == '') {

                    countErrorField += 1;
                    var fieldName = $('#' + inputId).data('name');
                    $('.error_' + inputId).html(fieldName + ' is required.');
                }
            });

            if (countErrorField > 0) {

                $('.loading_button').hide();
                return;
            }

            $('.submit_button').prop('type', 'button');
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    toastr.success(data);
                    $('#add_customer_form')[0].reset();
                    table.ajax.reload();
                    $('.loading_button').hide();
                    $('#addModal').modal('hide');
                    $('.submit_button').prop('type', 'submit');
                }
            });
        });

        // Pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.get(url, function(data) {

                $('#edit-modal-form-body').html(data);
                $('#editModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
            'title': "@lang('Delete Confirmation')",
            'content': "@lang('Are you sure, you want to delete?')",
            'buttons': {
                @lang("YES"): {'class': 'yes btn-modal-primary','action': function() {$('#deleted_form').submit();}},
                @lang("NO"): {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
            }
        });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                async: false,
                data: request,
                success: function(data) {

                    table.ajax.reload();
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                }
            });
        });

        // Show sweet alert for delete
        $(document).on('click', '#change_status', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    toastr.success(data);
                    table.ajax.reload();
                }
            });
        });

        $(document).on('click', '#generate_receipt', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#money_receipt_modal').html(data);
                    $('#MoneyReciptModal').modal('show');
                }
            });
        });

        $(document).on('click', '#money_receipt_list', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('.data_preloader').show();
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#receipt_voucher_list_modal_body').html(data);
                    $('#moneyReceiptListModal').modal('show');
                    $('.data_preloader').hide();
                }
            });
        });

        $(document).on('submit', '#money_receipt_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    toastr.success('Successfully money receipt voucher is generated.');
                    $('#MoneyReciptModal').modal('hide');
                    $('#moneyReceiptListModal').modal('hide');
                    $('.loading_button').hide();

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 500,
                        header: null,
                    });
                }
            });
        });

        // Pass editable data to edit modal fields
        $(document).on('click', '#edit_receipt', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.get(url, function(data) {

                $('#money_receipt_modal').html(data);
                $('#MoneyReciptModal').modal('show');
            });
        });

        $(document).on('click', '#print_receipt', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'html',
                success: function(data) {

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 500,
                        header: null,
                    });
                    $('.print_area').remove();
                    return;
                }
            });
        });

        // Show sweet alert for delete
        $(document).on('click', '#change_receipt_status', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('.receipt_preloader').show();

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#changeReciptStatusModal').html(data);
                    $('#changeReciptStatusModal').modal('show');
                    $('.receipt_preloader').hide();
                }
            });
        });

        $(document).on('submit', '#change_voucher_status_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.vcs_input');
            $('.error').html('');

            var countErrorField = 0;

            $.each(inputs, function(key, val) {

                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val();

                if (idValue == '') {

                    countErrorField += 1;
                    var fieldName = $('#' + inputId).data('name');
                    $('.error_vcs_' + inputId).html(fieldName + ' is required.');
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
                    $('#changeReciptStatusModal').modal('hide');
                    $('#moneyReceiptListModal').modal('hide');
                    table.ajax.reload();
                }
            });
        });

        $(document).on('click', '#delete_receipt', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            var tr = $(this).closest('tr');

            $('#receipt_deleted_form').attr('action', url);

            $.confirm({
            'title': "@lang('Delete Confirmation')",
            'content': "@lang('Are you sure, you want to delete?')",
            'buttons': {
                @lang("YES"): {'class': 'yes btn-modal-primary','action': function() {$('#receipt_deleted_form').submit();}},
                @lang("NO"): {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
            }
        });
        });

        //data delete by ajax
        $(document).on('submit', '#receipt_deleted_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                async: false,
                data: request,
                success: function(data) {

                    toastr.error(data);
                    $('#receipt_deleted_form')[0].reset();
                }
            });
        });

        $(document).on('change', '#is_header_less', function() {

            if ($(this).is(':CHECKED', true)) {

                $('.gap-from-top-add').show();
            } else {

                $('.gap-from-top-add').hide();
            }
        });

        // Print single payment details
        $('#print_payment').on('click', function(e) {
            e.preventDefault();
            var body = $('.sale_payment_print_area').html();
            var header = $('.header_area').html();
            var footer = $('.signature_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{asset('assets/css/print/purchase.print.css')}}",
                removeInline: true,
                printDelay: 500,
                header: header,
                footer: footer
            });
        });
    });

    //Print supplier report
    $(document).on('click', '.print_report', function(e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = "{{ route('reports.customer.print') }}";
        var customer_id = $('#customer_id').val();
        console.log(customer_id);
        $.ajax({
            url: url,
            type: 'get',
            data: {
                customer_id
            },
            success: function(data) {

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

    new Litepicker({
        singleMode: true,
        element: document.getElementById('date_of_birth'),
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
        format: 'YYYY-MM-DD',
    });

    document.onkeyup = function() {

        var e = e || window.event; // for IE to cover IEs window event-object

        if (e.ctrlKey && e.which == 13) {

            $('#addModal').modal('show');
            setTimeout(function() {

                $('#name').focus();
            }, 500);
            //return false;
        }
    }
</script>


@endpush
