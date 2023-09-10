@extends('layout.master')
@push('stylesheets')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
{{-- @section('title', 'Supplier List - ') --}}
@section('content')
<div class="body-woaper">
    <div class="container-fluid">
        <div class="row">
            <div class="border-class">
                <div class="main__content">
                    <div class="sec-name">
                        <div class="name-head">
                            <span class="fas fa-users"></span>
                            <h5>@lang('Suppliers')</h5>
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
                                <h6>@lang('All Supplier')</h6>
                            </div>

                            <div class="col-md-6">
                                <div class="btn_30_blue float-end">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-square"></i> @lang('Add')</a>
                                </div>

                                <div class="btn_30_blue float-end">
                                    <a href="{{ route('contacts.suppliers.import.create') }}"><i class="fas fa-plus-square"></i> @lang('Import Suppliers')</a>
                                </div>
                            </div>
                        </div>

                        <div class="widget_content">
                            <div class="data_preloader">
                                <h6>
                                    <i class="fas fa-spinner"></i> @lang('Processing')...
                                </h6>
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
                                            <th>@lang('Supplier ID')</th>
                                            <th>@lang('Prefix')</th>
                                            <th>@lang('Name')</th>
                                            <th>@lang('Business')</th>
                                            <th>@lang('Phone')</th>
                                            <th>@lang('Opening Balance')</th>
                                            <th>@lang('Total Purchase')</th>
                                            <th>@lang('Total Paid')</th>
                                            <th>@lang('Purchase Due')</th>
                                            <th>@lang('Total Return')</th>
                                            <th>@lang('Return Due')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Actions')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr class="bg-secondary">
                                            <th colspan="6" class="text-white text-end">@lang('Total') : ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                            <th id="opening_balance" class="text-white text-end"></th>
                                            <th id="total_purchase" class="text-white text-end"></th>
                                            <th id="total_paid" class="text-white text-end"></th>
                                            <th id="total_purchase_due" class="text-white text-end"></th>
                                            <th id="total_return" class="text-white text-end"></th>
                                            <th id="total_purchase_return_due" class="text-white text-end"></th>
                                            <th class="text-white text-start">---</th>
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

<!-- Add Modal ---->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">@lang('Add Supplier')</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <!--begin::Form-->
                <form id="add_supplier_form" action="{{ route('contacts.supplier.store') }}">

                    <div class="form-group row mt-1">
                        <div class="col-md-3">
                            <b>@lang('Name') :</b> <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control  add_input" data-name="Supplier name" id="name" placeholder="@lang('Supplier name')" />
                            <span class="error error_name" style="color: red;"></span>
                        </div>

                        <div class="col-md-3">
                            <b>@lang('Phone') :</b> <span class="text-danger">*</span>
                            <input type="text" name="phone" class="form-control  add_input" data-name="Phone number" id="phone" placeholder="@lang('Phone number')" />
                            <span class="error error_phone"></span>
                        </div>

                        <div class="col-md-3">
                            <b>@lang('Supplier ID') :</b> <i data-bs-toggle="tooltip" data-bs-placement="right" title="Leave empty to auto generate." class="fas fa-info-circle tp"></i>
                            <input type="text" name="contact_id" class="form-control" placeholder="@lang('Contact ID')" />
                        </div>

                        <div class="col-md-3">
                            <b>@lang('Business Name') :</b> <span class="text-danger">*</span>
                            <select name="add_branch_id" id="add_branch_id">
                                @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">
                                    {{ $branch->name . '/' . $branch->branch_code }}
                                </option>
                                @endforeach
                            </select>
                             {{-- <input type="text" name="business_name" class="form-control" placeholder="@lang('Business name')" /> --}}
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-md-3">
                            <b>@lang('Alternative Number') :</b>
                            <input type="text" name="alternative_phone" class="form-control " placeholder="@lang('Alternative phone number')" />
                        </div>

                        <div class="col-md-3">
                            <b>@lang('Landline') :</b>
                            <input type="text" name="landline" class="form-control " placeholder="@lang('landline number')" />
                        </div>

                        <div class="col-md-3">
                            <b>@lang('Email') :</b>
                            <input type="text" name="email" class="form-control " placeholder="@lang('Email address')" />
                        </div>

                        <div class="col-md-3">
                            <b>@lang('Date Of Birth') :</b>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                </div>
                                <input type="text" name="date_of_birth" id="date_of_birth" class="form-control date-of-birth-picker" autocomplete="off" placeholder="@lang('YYYY-MM-DD')">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-md-3">
                            <b>@lang('Tax Number') :</b>
                            <input type="text" name="tax_number" class="form-control " placeholder="@lang('Tax number')" />
                        </div>

                        <div class="col-md-3">
                            <b>@lang('Opening Balance') :</b> <i data-bs-toggle="tooltip" data-bs-placement="right" title="Opening balance will be added in this supplier due." class="fas fa-info-circle tp"></i>
                            <input type="number" name="opening_balance" class="form-control" placeholder="@lang('Opening balance')" />
                        </div>

                        <div class="col-md-3">
                            <label><b>@lang('Pay Term')</b> : </label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="number" step="any" name="pay_term_number" class="form-control" id="pay_term_number" placeholder="@lang('Number')" />
                                </div>

                                <div class="col-md-7">
                                    <select name="pay_term" class="form-control">
                                        <option value="">@lang('Days/Months')</option>
                                        <option value="1">@lang('Days')</option>
                                        <option value="2">@lang('Months')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-md-9">
                            <b>@lang('Address') :</b>
                            <input type="text" name="address" class="form-control " placeholder="@lang('Address')">
                        </div>
                        <div class="col-md-3">
                            <b>@lang('Prefix') <i data-bs-toggle="tooltip" data-bs-placement="right" title="This prefix for barcode." class="fas fa-info-circle tp"></i> :</b>
                            <input type="text" name="prefix" class="form-control " placeholder="@lang('prefix')" />
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-md-3">
                            <b>@lang('City') :</b>
                            <input type="text" name="city" class="form-control " placeholder="@lang('City')" />
                        </div>

                        <div class="col-md-3">
                            <b>@lang('State') :</b>
                            <input type="text" name="state" class="form-control " placeholder="@lang('State')" />
                        </div>

                        <div class="col-md-3">
                            <b>@lang('Country') :</b>
                            <input type="text" name="country" class="form-control " placeholder="@lang('Country')" />
                        </div>

                        <div class="col-md-3">
                            <b>@lang('Zip-Code') :</b>
                            <input type="text" name="zip_code" class="form-control " placeholder="@lang('zip_code')" />
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-md-5">
                            <b>@lang('Shipping Address') :</b>
                            <input type="text" name="shipping_address" class="form-control " placeholder="@lang('Shipping address')" />
                        </div>
                    </div>

                    <div class="form-group row">
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
<!-- Add Modal End---->

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">@lang('Edit Supplier')</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body" id="edit_modal_body"></div>
        </div>
    </div>
</div>
<!-- Edit Modal End-->
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
                url: "{{ route('contacts.supplier.index') }}",
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
        // ajax: "{{ route('contacts.supplier.index') }}",
        "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        columnDefs: [{
            "targets": [0, 12],
            "orderable": false,
            "searchable": false
        }],
        columns: [
            {
                data: 'contact_id',
                name: 'contact_id'
            },
            {
                data: 'prefix',
                name: 'prefix'
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
                data: 'opening_balance',
                name: 'opening_balance',
                className: 'text-end'
            },
            {
                data: 'total_purchase',
                name: 'total_purchase',
                className: 'text-end'
            },
            {
                data: 'total_paid',
                name: 'total_paid',
                className: 'text-end'
            },
            {
                data: 'total_purchase_due',
                name: 'total_purchase_due',
                className: 'text-end'
            },
            {
                data: 'total_return',
                name: 'total_return',
                className: 'text-end'
            },
            {
                data: 'total_purchase_return_due',
                name: 'total_purchase_return_due',
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
            var total_purchase = sum_table_col($('.data_tbl'), 'total_purchase');
            $('#total_purchase').text(bdFormat(total_purchase));
            var total_purchase_due = sum_table_col($('.data_tbl'), 'total_purchase_due');
            $('#total_purchase_due').text(bdFormat(total_purchase_due));
            var total_paid = sum_table_col($('.data_tbl'), 'total_paid');
            $('#total_paid').text(bdFormat(total_paid));
            var total_return = sum_table_col($('.data_tbl'), 'total_return');
            $('#total_return').text(bdFormat(total_return));
            var total_purchase_return_due = sum_table_col($('.data_tbl'), 'total_purchase_return_due');
            $('#total_purchase_return_due').text(bdFormat(total_purchase_return_due));
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
        // Add Supplier by ajax
        $('#add_supplier_form').on('submit', function(e) {
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

                    toastr.success("@lang('Supplier added successfully.')");
                    $('#add_supplier_form')[0].reset();
                    $('.loading_button').hide();
                    $('#addModal').modal('hide');
                    $('.submit_button').prop('type', 'submit');
                    table.ajax.reload();
                },
                error: function() {

                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url, function(data) {
                $('#edit_modal_body').html(data);
                $('#editModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        // edit category by ajax
        $(document).on('submit', '#edit_supplier_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.edit_input');
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

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.loading_button').hide();
                    $('#edit_supplier_form')[0].reset();
                    table.ajax.reload();
                    $('#editModal').modal('hide');
                }
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
        //console.log(e);
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
