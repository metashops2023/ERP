<div class="account_summary_area">
    <div class="heading">
        <h5 class="py-1 pl-1 text-center">@lang('Account Summary')</h5>
    </div>

    <div class="account_summary_table">
        <table class="table modal-table table-sm">
            <tbody>
                <tr>
                    <td class="text-end"><strong>@lang('Opening Balance') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end opening_balance" id="purchase_opening_balance"></td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('Total Purchase') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_purchase" id="purchase_total_purchase"></td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('Total Paid') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end text-success total_paid" id="purchase_total_paid"></td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('Total Return') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_return" id="purchase_total_return"></td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('Total Less') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_less" id="purchase_total_less"></td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('Balance Due') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end text-danger total_purchase_due" id="purchase_total_purchase_due"></td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('Total Returnable/Refundable Amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_purchase_return_due" id="purchase_total_purchase_return_due"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
