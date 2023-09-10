<style>
    .payment_top_card {background: #d7dfe8;}
    .payment_top_card span {font-size: 12px;font-weight: 400;}
    .payment_top_card li {font-size: 12px;}
    .payment_top_card ul {padding: 6px;border: 1px solid #dcd1d1;}
    .payment_list_table {position: relative;}
    .payment_details_contant{background: azure!important;}
    h6.checkbox_input_wrap {border: 1px solid #495677;padding: 0px 7px;}
</style>
<div class="info_area mb-2">
    <div class="row">
        <div class="col-md-6">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li class="text-navy-blue"><strong>@lang('Customer') : </strong>
                        <span class="card_text customer_name">
                            {{ $customer->name }}
                        </span>
                    </li>
                    <li class="text-navy-blue"><strong>@lang('Phone') : </strong>
                        <span class="card_text customer_name">
                            {{ $customer->phone }}
                        </span>
                    </li>
                    <li class="text-navy-blue">
                        <strong>@lang('Business') : </strong>
                        <span class="card_text customer_business">{{ $customer->business_name }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li class="text-navy-blue"><strong>@lang('Total Sale') : </strong>
                        <span class="card_text">
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ $customer->total_sale }}
                        </span>
                    </li>
                    <li class="text-navy-blue"><strong>@lang('Total Paid') : </strong>
                        <span class="card_text">
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ $customer->total_paid }}
                        </span>
                    </li>
                    <li class="text-navy-blue"><strong>@lang('Total Due') : </strong>
                        <span class="card_text">
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ $customer->total_sale_due }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="receipt_list_table">
    <div class="data_preloader receipt_list_preloader">
        <h6><i class="fas fa-spinner"></i> @lang('Processing')...</h6>
    </div>
    <div class="table-responsive">
        <div class="data_preloader receipt_preloader">
            <h6><i class="fas fa-spinner"></i> @lang('Processing')...</h6>
        </div>
        <table class="display data_tbl data__table table-striped">
            <thead>
                <tr>
                    <th>@lang('Date')</th>
                    <th>@lang('From')</th>
                    <th>@lang('Voucher No')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody id="receipt_list_body">
                @if (count($customer->receipts) > 0)
                    @foreach ($customer->receipts as $receipt)
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($receipt->date)) }}</td>

                            <td>
                                @if ($receipt->branch)
                                    {{ $receipt->branch->name }}/{{ $receipt->branch->branch_code }}
                                @else
                                    Head Office
                                @endif
                            </td>

                            <td>{{ $receipt->invoice_id }}</td>

                            <td>
                                {{ json_decode($generalSettings->business, true)['currency'] . ' ' . $receipt->amount }}
                            </td>

                            <td>
                                <div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                         @lang('Action')
                                    </button>

                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        @if ($receipt->status != 'Completed')
                                            <a class="dropdown-item" id="print_receipt" href="{{ route('money.receipt.voucher.print', $receipt->id) }}"><i class="fas fa-print text-primary"></i> @lang('Print')</a>
                                            <a class="dropdown-item" id="edit_receipt" href="{{ route('money.receipt.voucher.edit', $receipt->id) }}"><i class="fas fa-edit text-primary"></i> @lang('Edit')</a>
                                            <a class="dropdown-item" id="change_receipt_status" href="{{ route('money.receipt.voucher.status.change.modal', $receipt->id) }}"><i class="far fa-file-alt text-primary"></i> @lang('Change Status')</a>
                                        @endif
                                        <a class="dropdown-item" id="delete_receipt" href="{{ route('money.receipt.voucher.delete', $receipt->id) }}"><i class="far fa-trash-alt text-primary"></i> @lang('Delete')</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">@lang('No Data Found')</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <form id="receipt_deleted_form" action="" method="post">
            @method('DELETE')
            @csrf
        </form>
    </div>
</div>

<div class="form-group row mt-3">
    <div class="col-md-12">
        <a href="{{ route('money.receipt.voucher.create', [$customer->id]) }}" id="generate_receipt" class="c-btn button-success float-end">@lang('Generate New')</a>
        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
    </div>
</div>

