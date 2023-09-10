<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('Change Money Receipt Voucher Status (Voucher No') : {{ $receipt->invoice_id }} )</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
        <div class="modal-body">
            <!--begin::Form-->
            <form id="change_voucher_status_form" action="{{ route('money.receipt.voucher.status.change', $receipt->id) }}" method="POST">
                @csrf
                <div class="row mt-2">
                    <div class="col-md-4">
                        <label><b>@lang('Received Amount') :</b> <span class="text-danger">*</span> </label>
                        <input type="number" step="any" name="amount" class="form-control form-control-sm vcs_input" id="received_amount" data-name="Received amount" placeholder="@lang('Received Amount')"/>
                        <span class="error error_vcs_received_amount"></span>
                    </div>

                    <div class="col-md-4">
                        <label><strong>@lang('Status') :</strong> </strong> <span class="text-danger">*</span> </label>
                        <select disabled name="status" class="form-control form-control-sm mr_input" data-name="Money receipt status" id="vcs_status">
                            <option value="Pending">@lang('Pending')</option>
                            <option selected value="Completed">@lang('Completed')</option>
                        </select>
                        <span class="error error_vcs_status"></span>
                    </div>

                    <div class="col-md-4">
                        <label for="p_date"><strong>@lang('Date') :</strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i
                                        class="fas fa-calendar-week text-dark"></i></span>
                            </div>
                            <input type="date" name="date" class="form-control form-control-sm date-picker p_input"
                                autocomplete="off" id="p_date" data-name="Date" value="{{ date('Y-m-d') }}">
                        </div>
                        <span class="error error_p_date"></span>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-4">
                        <label><strong>@lang('Payment Method') :</strong> </strong></label>
                        <select name="payment_method" class="form-control form-control-sm" id="vcs_status">
                            <option value="Cash">@lang('Cash')</option>
                            <option value="Advanced">@lang('Advanced')</option>
                            <option value="Card">@lang('Card')</option>
                            <option value="Cheque">@lang('Cheque')</option>
                            <option value="Bank-Transfer">@lang('Bank-Transfer')</option>
                            <option value="Other">@lang('Other')</option>
                            <option value="Custom">@lang('Custom Field')</option>
                        </select>
                    </div>

                    <div class="col-md-8">
                        <label><strong>@lang('Account') :</strong> </strong> </label>
                        <select name="account_id" class="form-control form-control-sm">
                            <option value="">@lang('None')</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }} (A/C:
                                    {{ $account->account_number }}) (Balance: {{ $account->balance }})</option>
                            @endforeach
                        </select>
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
        </div>
    </div>
</div>
