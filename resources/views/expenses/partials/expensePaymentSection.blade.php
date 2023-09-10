<section class="">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="form_element m-0 mt-3">
                    <div class="element-body">
                        <div class="row mt-1">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <label class=" col-4">
                                        <b>Paying :
                                            ({{ json_decode($generalSettings->business, true)['currency'] }})
                                        </b>
                                    </label>

                                    <div class="col-8">
                                        <input required type="number" step="any" name="paying_amount" class="form-control" id="paying_amount" autocomplete="off">
                                        <span class="error error_paying_amount"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group mt-1">
                                    <label class="col-4"><b>@lang('Pay Method') :</b></label>
                                    <div class="col-8">
                                        <select name="payment_method_id" class="form-control" id="payment_method_id">
                                            @foreach ($methods as $method)
                                                <option
                                                    data-account_id="{{ $method->methodAccount ? $method->methodAccount->account_id : '' }}"
                                                    value="{{ $method->id }}">
                                                    {{ $method->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <label class="col-4"><b>@lang('Credit Account') :</b></label>
                                    <div class="col-8">
                                        <select required name="account_id" class="form-control" id="account_id">
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">
                                                    @php
                                                        $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                                                        $bank = $account->bank ? ', BK : '.$account->bank : '';
                                                        $ac_no = $account->account_number ? ', A/c No : '.$account->account_number : '';
                                                        $balance = ', BL : '.$account->balance;
                                                    @endphp
                                                    {{ $account->name.$accountType.$bank.$ac_no.$balance }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="error error_account_id"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <label class="col-4"><b>@lang('Total Due') :</b> </label>
                                    <div class="col-8">
                                        <input readonly name="total_due" type="number" step="any" id="total_due" class="form-control text-danger" value="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <label class=" col-2"><b>@lang('Payment Note') :</b></label>

                                    <div class="col-10">
                                        <input type="text" name="payment_note" class="form-control form-control-sm" id="payment_note" placeholder="@lang('Payment note')">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="submit-area py-3 mb-4">
                    <button type="button" class="btn loading_button d-none">
                        <i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b>
                    </button>

                    <button data-action="save" id="save" class="btn btn-sm btn-success submit_button float-end">
                        @lang('Save')
                    </button>

                    <button data-action="sale_and_print" id="save_and_print" class="btn btn-sm btn-success submit_button float-end me-1">
                        @lang('Save & Print')
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
