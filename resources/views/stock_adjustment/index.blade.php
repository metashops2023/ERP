@extends('layout.master')
@push('stylesheets')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
{{-- @section('title', 'All Stock Adjustment - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-sliders-h"></span>
                                <h5>@lang('Stock Adjustments')</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <form id="filter_form">
                                            <div class="form-group row">
                                                @if ($addons->branches == 1)
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-2">
                                                            <label><strong>@lang('Business Location') :</strong></label>
                                                            <select name="branch_id"
                                                                class="form-control submit_able" id="branch_id" autofocus>
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
                                                    <label><strong>@lang('Type') :</strong></label>
                                                    <select name="type" id="type" class="form-control submit_able" autofocus>
                                                        <option value="">@lang('All')</option>
                                                        <option value="1">@lang('Normal')</option>
                                                        <option value="2">@lang('Abnormal')</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('From Date') :</strong></label>
                                                    <div class="input-group">
                                                        <input name="from_date" class="form-control submit_able_input from_date" id="datepicker">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('To Date') :</strong></label>
                                                    <div class="input-group">
                                                        <input name="to_date" class="form-control submit_able_input to_date" id="datepicker2">
                                                    </div>
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
                    </div>

                    <div class="row margin_row mt-1">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-10">
                                    <h6>@lang('All Adjustment')</h6>
                                </div>

                                <div class="col-md-2">
                                    <div class="btn_30_blue float-end">
                                        <a href="{{ route('stock.adjustments.create') }}"><i class="fas fa-plus-square"></i> @lang('Add')</a>
                                    </div>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th class="text-start">@lang('Actions')</th>
                                                <th class="text-start">@lang('Date')</th>
                                                <th class="text-start">@lang('Voucher No')</th>
                                                <th class="text-start">@lang('Adjustment location')</th>
                                                <th class="text-start">@lang('Business location')</th>
                                                <th class="text-start">@lang('Reason')</th>
                                                <th class="text-start">@lang('Created By')</th>
                                                <th class="text-start">@lang('Type')</th>
                                                <th class="text-start">@lang('Total Amount')</th>
                                                <th class="text-start">@lang('Total Recovered Amount')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="8" class="text-white text-end">@lang('Total') : ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                <th id="net_total_amount" class="text-white text-end"></th>
                                                <th id="recovered_amount" class="text-white text-end"></th>
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

    <div class="adjustment_details"> </div>
@endsection
@push('scripts')
    <script type="text/javascript" src="/assets/plugins/custom/moment/moment.min.js"></script>
    <script src="/assets/plugins/custom/print_this/printThis.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        adjustment_table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'print',text: '<i class="fas fa-print"></i> @lang("Print")',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
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
                "url": "{{ route('stock.adjustments.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.type = $('#status').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columnDefs: [{"targets": [0, 3, 4, 9],"orderable": false,"searchable": false}],
            columns: [
                {data: 'action'},
                {data: 'date', name: 'date'},
                {data: 'invoice_id', name: 'invoice_id'},
                {data: 'adjustment_location', name: 'adjustment_location'},
                {data: 'business_location', name: 'branches.name'},
                {data: 'reason', name: 'reason'},
                {data: 'created_by', name: 'admin_and_users.name'},
                {data: 'type', name: 'type'},
                {data: 'net_total_amount', name: 'net_total_amount', className: 'text-end'},
                {data: 'recovered_amount', name: 'recovered_amount', className: 'text-end'},

            ],fnDrawCallback: function() {
                var net_total_amount = sum_table_col($('.data_tbl'), 'net_total_amount');
                $('#net_total_amount').text(bdFormat(net_total_amount));
                var recovered_amount = sum_table_col($('.data_tbl'), 'recovered_amount');
                $('#recovered_amount').text(bdFormat(recovered_amount));
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
        $(document).on('submit', '#filter_form', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            adjustment_table.ajax.reload();
        });

        // Pass sale details in the details modal
        function adjustmentDetails(url) {
            $('.data_preloader').show();
            $.ajax({
                url:url,
                type:'get',
                success:function(data){
                    $('.adjustment_details').html(data);
                    $('.data_preloader').hide();
                    $('#detailsModal').modal('show');
                }
            });
        }

        // Show details modal with data
        $(document).on('click', '.details_button', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            adjustmentDetails(url);
        });

        $(document).on('click', '#delete',function(e){
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
        $(document).on('submit', '#deleted_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){
                    adjustment_table.ajax.reload();
                    toastr.success(data);
                }
            });
        });

        // Make print
        $(document).on('click', '.print_btn', function (e) {
           e.preventDefault();
            var body = $('.adjustment_print_template').html();
            var header = $('.heading_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
                removeInline: false,
                printDelay: 500,
                header : null,
            });
        });
    </script>

    <script>
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
        })

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
        })
    </script>
@endpush
