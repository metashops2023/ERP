<div class="account_summary_area">
    <div class="heading py-1">
        <h5 class="py-1 pl-1 text-center">@lang('Account Summary')</h5>
    </div>

    <div class="account_summary_table">
        <table class="table modal-table table-sm">
            <tbody>
                <tr>
                    <td class="text-end">
                        <strong>@lang('Opening Balance') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong>
                    </td>

                    <td class="text-end opening_balance" id="sales_opening_balance">0.00</td>
                </tr>

                <tr>
                    <td class="text-end">
                        <strong>@lang('Total Sale') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong>
                    </td>

                    <td class="text-end total_sale" id="sales_total_sale">0.00</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('Total Return') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_return" id="sales_total_return">0.00</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('Total Less') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_less" id="sales_total_less">0.00</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('Total Paid') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_paid" id="sales_total_paid">
                        0.00
                    </td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('Balance Due') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_sale_due" id="sales_total_sale_due">0.00</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('Returnable Due') : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                    <td class="text-end total_sale_return_due" id="sales_total_sale_return_due">0.00</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
