<style>
    .payment_top_card {background: #d7dfe8;}
    .payment_top_card span {font-size: 12px;font-weight: 400;}
    .payment_top_card li {font-size: 12px;}
    .payment_top_card ul {padding: 6px;border: 1px solid #dcd1d1;}
    .payment_list_table {position: relative;}
    .payment_details_contant{background: azure!important;}
    h6.checkbox_input_wrap {border: 1px solid #495677; padding: 0px 7px;}
</style>
<div class="info_area mb-2">
    <div class="row">
        <div class="col-md-6">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong>@lang('Customer') : </strong>
                        <span class="card_text customer_name">
                            {{ $receipt->cus_name }}
                        </span>
                    </li>
                    <li><strong>@lang('Phone') : </strong>
                        <span class="card_text customer_name">
                            {{ $receipt->cus_phone }}
                        </span>
                    </li>
                    <li>
                        <strong>@lang('Business') : </strong>
                        <span class="card_text customer_business">{{ $receipt->cus_business }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<form id="money_receipt_form" action="{{ route('money.receipt.voucher.update', $receipt->id) }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-3">
            <label><b>@lang('Receiving Amount') :</b> </label>
            <input type="text" name="amount" class="form-control" placeholder="@lang('Receiving Amount')" value="{{ $receipt->amount }}"/>
        </div>

        <div class="col-md-3">
            <label for="p_date"><strong>@lang('Date') :</strong></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i
                            class="fas fa-calendar-week input_i"></i></span>
                </div>
                <input type="text" name="date" class="form-control" id="mr_date"
                    autocomplete="off" data-name="Date" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}">
            </div>
        </div>

        <div class="col-md-3">
            <label><b>@lang('Account Details') :</b> </label>
            <input type="text" name="ac_details" class="form-control" placeholder="@lang('Account Details')" value="{{ $receipt->ac_details }}"/>
        </div>

        <div class="col-md-3">
            <label><b>@lang('Receiver') :</b> </label>
            <input type="text" name="receiver" class="form-control" placeholder="@lang('Receiver Name')" value="{{ $receipt->receiver }}"/>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <label><strong>@lang('Paper Note') :</strong></label>
            <textarea name="note" class="form-control" id="note" cols="30" rows="3"
                placeholder="@lang('Paper Note')">{{ $receipt->note }}</textarea>
        </div>
    </div>

    <div class="extra_label">
        <div class="form-group row mt-2">
            <div class="col-md-3">
                <p> <input type="checkbox" {{ $receipt->is_customer_name ? 'CHECKED' : '' }} name="is_customer_name" id="is_customer_name" value="1"> &nbsp; <b>@lang('Show Customer Name')</b> </p>
            </div>

            <div class="col-md-2">
                <p> <input type="checkbox" {{ $receipt->is_date ? 'CHECKED' : '' }} name="is_date" value="1"> &nbsp; <b>@lang('Show Date')</b></p>
            </div>

            <div class="col-md-3 mt-2">
                <p> <input type="checkbox" {{ $receipt->is_header_less ? 'CHECKED' : '' }} name="is_header_less" id="is_header_less" value="1"> &nbsp; <b>@lang('Is Header Less For Pad Print')?</b> </p>
            </div>

            <div class="col-md-4 gap-from-top-add {{ $receipt->is_header_less == 1 ? '' : 'd-none' }}">
                <label><b>@lang('Gap From Top') :</b> </label>
                <input type="text" name="gap_from_top" class="form-control" placeholder="@lang('Gap From Top')" value="{{ $receipt->gap_from_top}}"/>
            </div>
        </div>
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn button-success float-end">@lang('Save')</button>
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
        </div>
    </div>
</form>

<script>
    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
    new Litepicker({
        singleMode: true,
        element: document.getElementById('mr_date'),
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
        format: _expectedDateFormat,
    });
</script>
