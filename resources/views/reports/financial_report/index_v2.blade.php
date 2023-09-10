@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
{{-- @section('title', 'Profit Loss A/C - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">

                        <div class="sec-name">
                            <div class="name-head">
                                <span class="far fa-money-bill-alt"></span>
                                <h5>@lang('Financial Report')</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <form id="filter_financial_report" class="px-2">
                                            <div class="form-group row">
                                                @if ($addons->branches == 1)
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-2">
                                                            <label><strong>@lang('Business Location') :</strong></label>
                                                            <select name="branch_id"
                                                                class="form-control" id="branch_id" autofocus>
                                                                <option value="">@lang('All')</option>
                                                                <option SELECTED value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
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
                                                    <label><strong>@lang('From Date') :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_f"></i></span>
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
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="datepicker2" class="form-control to_date date" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label><strong></strong></label>
                                                            <div class="input-group">
                                                                <button type="submit" class="btn text-white btn-sm btn-secondary float-start"><i class="fas fa-funnel-dollar"></i> @lang('Filter')</button>
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
                    </div>

                    <div class="row margin_row mt-1">
                        <div class="card col-md-7">

                            <div class="section-header">
                                <div class="col-md-10">
                                    <h6>@lang('Financial Report')</h6>
                                </div>
                            </div>

                            <div class="widget_content mt-2">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="table modal-table table-sm table-bordered">
                                        <tbody>
                                            <tr>
                                                <td class="aiability_area">
                                                    <table class="table table-sm">
                                                        <tbody>
                                                            {{-- Cash Flow from investing --}}
                                                            <tr>
                                                                <th class="text-start bg-secondary text-white" colspan="2">
                                                                    <strong>@lang('ASSET') :</strong>
                                                                </th>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Fixed Asset') :</em>
                                                                </td>
                                                                <td class="text-start"><b><em>0.00</em></b>  </td>
                                                            </tr>

                                                            <tr>
                                                                <th class="text-start bg-secondary text-white" colspan="2">
                                                                    <strong>@lang('SALES') :</strong>
                                                                </th>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Total Sale'):</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em></b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Total Sale Due') :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em></b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Total Sale Return') :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em> </b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th class="text-start bg-secondary text-white" colspan="2">
                                                                    <strong>@lang('PURCHASE') :</strong>
                                                                </th>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                   <em>@lang('Total Purchase') :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em> </b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Total Purchase Due') :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em> </b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Total Purchase Return') :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em> </b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th class="text-start bg-secondary text-white" colspan="2">
                                                                    <strong>@lang('EXPENSES') :</strong>
                                                                </th>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Total Direct Expense') :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em> </b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Total Indirect Expense') :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em> </b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th class="text-start bg-secondary text-white" colspan="2">
                                                                    <strong>@lang('PRODUCTS') :</strong>
                                                                </th>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Closing Stock') :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em> </b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Total Stock Adjustment') :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em> </b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Total Stock Adjustment Recovered Amount') :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em> </b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th class="text-start bg-secondary text-white" colspan="2">
                                                                    <strong>@lang('LOAN') & LIABILITIES :</strong>
                                                                </th>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Total Loan Liabilities') :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em> </b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Total Loan') & Liabilities Due Paid :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em> </b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Payable Loan') & Liabilities Due :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em> </b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th class="text-start bg-secondary text-white" colspan="2">
                                                                    <strong>@lang('LOAN') & ADVANCE :</strong>
                                                                </th>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Total Loan') & Advance :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em> </b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Total Loan') & Advance Due Received :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em> </b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Receivable Loan') & Advance Due :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em> </b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th class="text-start bg-secondary text-white" colspan="2">
                                                                    <strong>@lang('PROFIT LOSS') :</strong>
                                                                </th>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Total Daily Profit') :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em> </b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Total Gross Profit') :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em> </b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Total Net Profit') :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em> </b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th class="text-start bg-secondary text-white" colspan="2">
                                                                    <strong>@lang('ACCOUNT BALANCE') :</strong>
                                                                </th>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Cash-In-Hand Balance') :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em> </b>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-start">
                                                                    <em>@lang('Bank A/C Balance') :</em>
                                                                </td>

                                                                <td class="text-start">
                                                                    <b><em>0.00</em> </b>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    // Setup ajax for csrf token.
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    function getFinancialReport() {

       $('.data_preloader').show();
       var branch_id = $('#branch_id').val();
       var from_date = $('.from_date').val();
       var to_date = $('.to_date').val();
        console.log(branch_id);
        $.ajax({
            url:"{{ route('reports.financial.amounts') }}",
            type: 'GET',
            data : {branch_id, from_date, to_date},
            success:function(data){

                $('#data-list').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getFinancialReport();

    // //Print purchase Payment report
    $(document).on('submit', '#filter_financial_report', function (e) {
        e.preventDefault();
        getFinancialReport();
    });

    //Print financial report
    $(document).on('click', '#print_report', function (e) {
        e.preventDefault();
        var url = "{{ route('reports.financial.report.print') }}";
        var branch_id = $('#branch_id').val();
        var from_date = $('.from_date').val();
        var to_date = $('.to_date').val();
        $.ajax({
            url:url,
            type:'get',
            data: {branch_id, from_date, to_date},
            success:function(data) {
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
        format: 'DD-MM-YYYY',
    });
</script>
@endpush
