@php
    use Carbon\Carbon;
@endphp

<div class="modal-header">
    <h6 class="modal-title" id="exampleModalLabel">Register Details (
        {{ Carbon::createFromFormat('Y-m-d H:i:s', $activeCashRegister->created_at)->format('jS M, Y h:i A') }}
        - {{ Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))->format('jS M, Y h:i A') }} )
    </h6>
    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
        class="fas fa-times"></span></a>
</div>
<div class="modal-body">
    <!--begin::Form-->
    <form action="{{ route('sales.cash.register.close') }}" method="POST">
        @csrf
        @if (auth()->user()->permission->register['register_view'] == '1')
            <table class="cash_register_table modal-table table table-sm">
                <tbody>
                    <tr>
                        <td class="text-start">@lang('Opeing Balance') :</td>
                        <td class="text-start">
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ App\Utils\Converter::format_in_bdt($activeCashRegister->cash_in_hand) }}
                        </td>
                    </tr>

                    @foreach ($paymentMethodPayments as $payment)
                        <tr>
                            <td width="50" class="text-start"> {{$payment->name.' Payment' }} :</td>
                            <td width="50" class="text-start">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($payment->total_paid) }}
                            </td>
                        </tr>
                    @endforeach

                    <tr>
                        <td width="50" class="text-start">
                            Total Credit Sale:
                        </td>

                        <td width="50" class="text-start text-danger">
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ App\Utils\Converter::format_in_bdt($totalCredit->sum('total_due')) }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr>

            <p><strong>@lang('Collected Amounts By Account')</strong></p>
            <table class="cash_register_table table modal-table table-sm">
                <tbody>
                    @php
                        $receivedInCashAccount = 0;
                    @endphp
                    @foreach ($accountPayments as $accountType)
                        @if ($accountType->account_type == 1)
                            @php
                                $receivedInCashAccount += $accountType->total_paid;
                            @endphp
                        @endif
                        <tr>
                            <td width="50" class="text-start">
                                {{ $accountType->account_type == 1 ? 'Cash-In-Hand' : 'Bank A/C' }} :
                            </td>
                            <td width="50" class="text-start">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($accountType->total_paid) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <hr>
        @endif

        <div class="form-group row">
            <div class="col-md-4">
                @php
                    $__receivedInCashAccount = $receivedInCashAccount + $activeCashRegister->cash_in_hand
                @endphp
                <label><b>@lang('Closing Amount') :</b></label>
                <input required type="number" name="closed_amount" step="any" class="form-control" value="{{ $__receivedInCashAccount }}">
            </div>
        </div>

        <div class="form-group row mt-1">
            <div class="col-md-12">
                <label><b>@lang('Closing Note') :</b></label>
                <textarea name="closing_note" class="form-control" cols="10" rows="3" placeholder="@lang('Closing Note')"></textarea>
            </div>
        </div>

        <div class="form-group text-end mt-3">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn button-success me-0 float-end">@lang('Close Register')</button>
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
        </div>
    </form>
</div>
