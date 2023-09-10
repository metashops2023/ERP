@extends('layout.master')
@push('stylesheets')

@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-balance-scale"></span>
                                <h5>@lang('Balance Sheet')</h5>
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
                                                            <select name="branch_id"
                                                                class="form-control submit_able" id="branch_id" autofocus>
                                                                <option SELECTED value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
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
                                                                <button type="submit"
                                                                    class="btn text-white btn-sm btn-secondary float-start">
                                                                    <i class="fas fa-funnel-dollar"></i> @lang('Filter')</button>
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

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name mt-1">
                                    <div class="col-md-12">
                                        <div class="data_preloader mt-5 pt-5"> <h6><i class="fas fa-spinner"></i> @lang('Processing')...</h6></div>
                                        <div class="balance_sheet_area">
                                            <div class="print_header d-none">
                                                <div class="text-center pb-3">
                                                    <h5>
                                                        @if (auth()->user()->branch_id)

                                                            {{ auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code }}
                                                        @else

                                                            {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                                        @endif
                                                    </h5>
                                                    <h6 class="mt-2"><strong>@lang('BALANCE SHEET')</h6>
                                                </div>
                                            </div>
                                            <div id="data-list">
                                                <table class="table modal-table table-sm table-bordered">
                                                    <thead>
                                                        <tr class="bg-primary">
                                                            <th class="liability text-white">@lang('Liability')</th>
                                                            <th class="assets text-white">@lang('Assets')</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="aiability_area">
                                                                <table class="table table-sm">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="text-start"><strong>@lang('Supplier Due') :</strong></td>
                                                                            <td class=" text-end">
                                                                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                                <span class="supplier_due"></span>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="text-start"><strong>@lang('Customer Return Due') :</strong></td>
                                                                            <td class="text-end">
                                                                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                                <span class="customer_return_due"></span>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="text-start">
                                                                                <strong>@lang('Payable Loan & Liabilities') :</strong>
                                                                            </td>

                                                                            <td class="text-end">
                                                                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                                <span class="payable_ll"></span>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="text-start">
                                                                                <strong>@lang('Capital A/C') :</strong>
                                                                            </td>

                                                                            <td class="text-end">
                                                                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                                <span class="payable_ll"></span>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="text-start"><strong>@lang('Opening Stock') :</strong></td>
                                                                            <td class="text-end">
                                                                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                                <span class="payable_ll"></span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>

                                                            <td class="asset_area">
                                                                <table class="table table-sm">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="text-start"><strong>@lang('Cash-In-Hand') :</strong></td>
                                                                            <td class="text-end">
                                                                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                                <span class="cash_in_hand"></span>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="text-start"><strong>@lang('Bank A/C Balance') :</strong></td>
                                                                            <td class="text-end">
                                                                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                                <span class="bank_balance"></span>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="text-start"><strong>@lang('Customer Due') :</strong></td>
                                                                            <td class="text-end">
                                                                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                                <span class="customer_due"></span>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="text-start"><strong>@lang('Supplier Return Due') :</strong></td>
                                                                            <td class="text-end">
                                                                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                                <span class="supplier_return_due"></span>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="text-start"><strong>@lang('Current Stock Value') :</strong></td>
                                                                            <td class=" text-end">
                                                                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                                <span class="stock_value"></span>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="text-start"><strong>@lang('Investments') :</strong></td>
                                                                            <td class=" text-end">
                                                                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                                <span class="investment"></span>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="text-start"><strong>@lang('Receivable Loan')&Advance :</strong></td>
                                                                            <td class=" text-end">
                                                                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                                <span class="receiveable_la"></span>
                                                                            </td>
                                                                        </tr>

                                                                        <tr class="bg-info">
                                                                            <td class="text-end text-white"><strong>@lang('Total Current Asset') :</strong></td>
                                                                            <td class=" text-end">
                                                                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                                <span class="total_physical_asset"></span>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="text-end text-white"></td>
                                                                            <td class="text-end"></td>
                                                                        </tr>

                                                                        <tr class="bg-secondary">
                                                                            <th colspan="2" class="text-start"><strong>@lang('Fixed Asset') :</strong></th>
                                                                        </tr>

                                                                        <tr class="account_balance_list_area">
                                                                            <td colspan="2">
                                                                                <table class="table table-sm">
                                                                                    <tbody class="account_balance_list">
                                                                                        <tr>
                                                                                            <td class="text-start" colspan="2">
                                                                                                Furniture :
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td class="text-start" colspan="2">
                                                                                                Vechels :
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr class="bg-primary">
                                                            <td class="total_liability_area">
                                                                <table class="table table-sm">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="text-start"><strong>@lang('Total Liability') :</strong> </td>
                                                                            <td class="text-end">
                                                                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                                <span class="total_liability"></span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </th>
                                                            <td class="total_asset_area">
                                                                <table class="table table-sm">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="text-start"><strong>@lang('Total Asset') :</strong></td>
                                                                            <td class="text-end">
                                                                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                                                                <span class="total_asset"></span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="print_btn_area">
                                            <a id="print_btn" href="#" class="btn btn-sm btn-primary float-end"><i class="fas fa-print"></i> @lang('Print')</a>
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
@endsection
@push('scripts')
<script src="/assets/plugins/custom/print_this/printThis.js"></script>
<script>

    function getBalanceAmounts(){
        $('.data_preloader').show();
        var branch_id = $('#branch_id').val();
        $.ajax({
            url:"{{route('accounting.balance.sheet.amounts')}}",
            type : 'GET',
            data : { branch_id : branch_id},
            success:function(data){
                $('#data-list').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getBalanceAmounts();

    $(document).on('submit', '#filter_form', function (e) {
        e.preventDefault();
        getBalanceAmounts();
    });

    // Print single payment details
    $('#print_btn').on('click', function (e) {
        e.preventDefault();
        var body = $('.balance_sheet_area').html();
        var header = $('.print_header').html();
        var footer = $('.print_footer').html();
        $(body).printThis({
            debug: false,
            importCSS: true,
            importStyle: true,
            loadCSS: "{{asset('assets/css/print/balance.sheet.print.css')}}",
            removeInline: false,
            printDelay: 600,
            header: header,
            footer: footer
        });
    });
</script>
@endpush
