@extends('layout.master')
@push('stylesheets')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>

@endpush
{{-- @section('title', 'Cash Register Reports - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-cash-register"></span>
                                <h5>@lang('Cash Register Reports')</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <form id="filter_form" action="" method="get">
                                            <div class="form-group row">
                                                @if ($addons->branches == 1)
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-2">
                                                            <label><strong>@lang('Business Location') :</strong></label>
                                                            <select name="branch_id" class="form-control submit_able" id="branch_id" autofocus>
                                                                <option value="">@lang('All')</option>
                                                                <option value="NULL">
                                                                    {{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)
                                                                </option>

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
                                                    <label><strong>@lang('User') :</strong></label>
                                                    <select name="user_id" class="form-control submit_able" id="user_id" autofocus>
                                                        @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                            <option value="">@lang('All')</option>
                                                        @else
                                                            <option value="">@lang('All')</option>
                                                            @foreach ($branchUsers as $user)
                                                                <option value="{{ $user->id }}">{{ $user->prefix.' '.$user->name.' '.$user->last_name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('Status') :</strong></label>
                                                    <select name="status" class="form-control submit_able" id="status">
                                                        <option value="">@lang('All')</option>
                                                        <option value="1">@lang('Open')</option>
                                                        <option value="2">@lang('Closed')</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('From Date') :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">
                                                                <i class="fas fa-calendar-week input_f"></i>
                                                            </span>
                                                        </div>

                                                        <input type="text" name="from_date" id="datepicker"
                                                            class="form-control from_date date"
                                                            autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('To Date') :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">
                                                                <i class="fas fa-calendar-week input_f"></i>
                                                            </span>
                                                        </div>

                                                        <input type="text" name="to_date" id="datepicker2" class="form-control to_date date" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label><strong></strong></label>
                                                            <div class="input-group">
                                                                <button type="submit" id="filter_button" class="btn text-white btn-sm btn-secondary float-start">
                                                                    <i class="fas fa-funnel-dollar"> @lang('Filter') </i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 mt-3">
                                                            <a href="#" class="btn btn-sm btn-primary float-end " id="print_report"><i class="fas fa-print "></i> @lang('Print')</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12">
                                <div class="report_data_area">
                                    <div class="data_preloader">
                                        <h6>
                                            <i class="fas fa-spinner text-primary"></i> Processing...
                                        </h6>
                                    </div>

                                    <div class="card">
                                        <div class="table-responsive" id="data-list">
                                            <table class="display data_tbl data__table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-start">@lang('Open Time')</th>
                                                        <th class="text-start">@lang('Closed Time')</th>
                                                        <th class="text-start">@lang('Business Location')</th>
                                                        <th class="text-start">@lang('User')</th>
                                                        <th class="text-start">@lang('Closing Note')</th>
                                                        <th class="text-start">@lang('Status')</th>
                                                        <th class="text-start">@lang('Closing Amount')</th>
                                                        <th class="text-start">@lang('Action')</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr class="bg-secondary">
                                                        <th colspan="6" class="text-end text-white">Total :
                                                            {{ json_decode($generalSettings->business, true)['currency'] }}
                                                        </th>
                                                        <th id="closed_amount" class="text-end text-white"></th>
                                                        <th></th>
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
    </div>

    <div class="modal fade" id="cashRegisterDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" id="cash_register_details_content"></div>
        </div>
    </div>
@endsection
@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    var cr_table = $('.data_tbl').DataTable({
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
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
        "ajax": {
            "url": "{{ route('reports.cash.registers.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.user_id = $('#user_id').val();
                d.status = $('#status').val();
                d.from_date = $('.from_date').val();
                d.to_date = $('.to_date').val();
            }
        },
        columnDefs: [{
            "targets": [1, 5, 7],
            "orderable": false,
            "searchable": false
        }],
        columns: [
            {data: 'created_at', name: 'created_at'},
            {data: 'closed_time', name: 'closed_time'},
            {data: 'branch', name: 'branches.name'},
            {data: 'user', name: 'admin_and_users.name'},
            {data: 'closing_note', name: 'closing_note'},
            {data: 'status', name: 'status', className: 'text-end'},
            {data: 'closed_amount', name: 'closed_amount', className: 'text-end'},
            {data: 'action'},

        ],fnDrawCallback: function() {
            $('.data_preloader').hide();
        }
    });

    //Submit filter form by select input changing
    $(document).on('submit', '#filter_form', function (e) {
        e.preventDefault();
        cr_table.ajax.reload();
        $('.data_preloader').show();
    });

    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
        $(document).on('change', '#branch_id', function () {
            var branch_id = $(this).val();
            $('#user_id').empty();
            $('#user_id').append('<option value="">@lang('All')</option>');
            $.ajax({
                url:"{{ url('common/ajax/call/branch/authenticated/users/') }}"+"/"+branch_id,
                type: 'get',
                dataType: 'json',
                success:function(users){
                    $('#user_id').empty();
                    $('#user_id').append('<option value="">@lang('All')</option>');

                    $.each(users, function(key, val){
                        var prefix = val.prefix ? val.prefix : '';
                        var last_name = val.last_name ? val.last_name : '';
                        $('#user_id').append('<option value="'+val.id+'">'+prefix+' '+val.name+' '+last_name+'</option>');
                    });
                }
            });
        });
    @endif

    $(document).on('click', '#register_details_btn',function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(data){
                $('#cash_register_details_content').html(data);
                $('#cashRegisterDetailsModal').modal('show');
            }
        });
    });

    //Print purchase Payment report
    $(document).on('click', '#print_report', function (e) {
        e.preventDefault();
        var url = "{{ route('reports.get.cash.register.report.print') }}";
        var branch_id = $('#branch_id').val();
        var user_id = $('#user_id').val();
        var status = $('#status').val();
        var from_date = $('.from_date').val();
        var to_date = $('.to_date').val();
        $.ajax({
            url:url,
            type:'get',
            data: {branch_id, user_id, status, from_date, to_date},
            success:function(data){
                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
                    removeInline: false,
                    printDelay: 500,
                    header: "",
                    pageTitle: "",
                    // footer: 'Footer Text',
                    formValues: false,
                    canvas: false,
                    beforePrint: null,
                    afterPrint: null
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
        format: 'DD-MM-YYYY',
    });
</script>
@endpush
