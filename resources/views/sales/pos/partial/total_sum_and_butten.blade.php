<style>
    .cash_receive_input{
      background-color: white;
      border: 1px solid #ced4da;
      letter-spacing: -3px!important;
      padding: 0px 3px 0px 0px!important;
      font-weight: 700!important;
    }
</style>

<div class="col-lg-3 p-1">
    <div class="pos-right-inner">
        <div class="check-out-woaper">
            <div class="function-sec">
                <div class="row">
                    <!-- <div class="col-4 px-2 py-1">
                        <div style="background-color:#d9804f;border-radius:5px;">
                            <a href="#"
                            style="width:100%;display:block;"
                                @if (json_decode($generalSettings->pos, true)['is_enabled_draft'] == '1')
                                    data-button_type="0"
                                    data-action_id="2"
                                    id="submit_btn"
                                @else
                                    onclick="
                                        event.preventDefault();
                                        toastr.error('Creating draft is disabled in POS.');
                                    "
                                @endif
                                class="function-card" tabindex="-1">@lang('Draft')<p>F2</p>
                            </a>
                        </div>
                    </div> -->

                    <!-- <div class="col-4 px-2 py-1">
                    <div style="background-color:#d9804f;border-radius:5px;">
                            <a href="#"
                            style="width:100%;display:block;"
                                @if (json_decode($generalSettings->pos, true)['is_enabled_quotation'] == '1')
                                    data-action_id="4"
                                    data-button_type="0"
                                    id="submit_btn"
                                @else
                                    onclick="
                                        event.preventDefault();
                                        toastr.error('Creating quotaion is disabled in POS.');
                                    "
                                @endif
                                class="function-card" tabindex="-1">@lang('Quotation')<p>F4</p>
                            </a>
                        </div>
                    </div> -->

                    <!-- <div class="col-4 px-2 py-1">
                    <div style="background-color:#d9804f;border-radius:5px;">
                            <a style="width:100%;display:block;" href="#" class=" function-card" id="exchange_btn" data-bs-toggle="modal" data-bs-target="#exchangeModal" tabindex="-1">
                                @lang('Exchange')<p>F6</p>
                            </a>
                        </div>
                    </div> -->

                    <div class="col-4 px-2 py-1">
                    <div style="background-color:#09aa29;border-radius:6px;">
                            <a style="font-size:14px;font-weight:500;width:100%;display:block;" href="#" class="function-card" id="show_stock" tabindex="-1">@lang('Stock')<i style="margin-left:5px" class="fas fa-box-open"></i><p style="font-size:12px;font-weight:500">@lang('Alt+C')</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4 px-2 py-1">
                        <div style="background-color:#09aa29;border-radius:6px;">
                            <a style="font-size:14px;font-weight:500;;width:100%;display:block;"
                                @if (json_decode($generalSettings->pos, true)['is_enabled_hold_invoice'] == '1')
                                    data-button_type="0"
                                    data-action_id="5"
                                    id="submit_btn"
                                @else
                                    onclick="
                                        event.preventDefault();
                                        toastr.error('Hold invoice is disabled in POS.');
                                    "
                                @endif
                                class="function-card" tabindex="-1">@lang('Hold Invoice')<i style="margin-left:5px" class="fa fa-file"></i><p style="font-size:12px;font-weight:500;">F8</p>
                            </a>
                        </div>
                    </div>

                    <!-- <div class="col-4 px-2 py-1">
                    <div style="background-color:#d9804f;border-radius:5px;">
                            <a style="width:100%;display:block;"
                                @if (json_decode($generalSettings->pos, true)['is_enabled_hold_invoice'] == '1')
                                    id="pick_hold_btn"
                                @else
                                    onclick="
                                        event.preventDefault();
                                        toastr.error('Hold invoice is disabled in POS.');
                                    "
                                @endif
                                class="function-card" tabindex="-1">@lang('Pick Hold') <p>F9</p>
                            </a>
                        </div>
                    </div> -->

                    <!-- <div class="col-4 px-2 py-1">
                    <div style="background-color:#d9804f;border-radius:5px;">
                            <a style="width:100%;display:block;" href="{{ route('settings.general.index') }}" class=" function-card" tabindex="-1">
                                @lang('Setup') <p>@lang('Ctrl+Q')</p>
                            </a>
                        </div>
                    </div> -->

                    <!-- <div class="col-4 px-2 py-1">
                    <div style="background-color:#d9804f;border-radius:5px;">
                            <a style="width:100%;display:block;"
                                @if (json_decode($generalSettings->pos, true)['is_enabled_suspend'] == '1')
                                    data-button_type="0"
                                    data-action_id="6"
                                    id="submit_btn"
                                @else
                                    onclick="
                                        event.preventDefault();
                                        toastr.error('Suspend is disabled in POS.');
                                    "
                                @endif
                                class="function-card" tabindex="-1">@lang('Suspend')<p>@lang('Alt+A')</p>
                            </a>
                        </div>
                    </div> -->

                    <div class="col-4 px-2 py-1">
                    <div style="background-color:#09aa29;border-radius:6px;">
                            <a style="font-size:14px;font-weight:500;width:100%;display:block;" class="function-card" onclick="cancel(); return false;" tabindex="-1">
                                @lang('Cancel')<i style="margin-left:5px" class="fa fa-file"></i> <p style="font-size:12px;font-weight:500">@lang('Ctrl+M')</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <hr><br><br><br>

            <div class="wrapper_input_btn" style="height:80%;margin:-40px -5px;width:95%;">
                <div class="checkout-input-sec">
                    <div class="row">
                    <div class="col-sm-9 mb-2">
                            <input style="border:none;text-align:left;background-color:#e6e6e6;height:35px;" readonly type="number" class="form-control pos-amounts" name="net_total_amount" id="net_total_amount" value="0.00" tabindex="-1">
                        </div>
                        <label style="text-align:right;background-color:#e6e6e6;margin-left:-18px;height:26px;height:35px;" for="inputEmail3" class="col-sm-3 col-form-label text-white"><b style="color:#666;font-size: 14px;line-height: 1;">@lang('Total')</b></label>
                    </div>

                    @if (json_decode($generalSettings->pos, true)['is_enabled_discount'] == '1')
                        <div class="row">
                            <div class="col-sm-9 mb-2">
                                <div class="row">
                                    <div class="col-md-6" >
                                        <select style="background-color:#e6e6e6;;height:35px;" name="order_discount_type" id="order_discount_type" class="form-control pos-amounts">
                                            <option value="1">0.00</option>
                                            <option value="2">%</option>
                                        </select>
                                        {{-- <input name="order_discount_type" class="form-control" id="order_discount_type" value="1"> --}}
                                    </div>

                                    <div class="col-md-6">
                                        <input style="border:none;background-color:#e6e6e6;text-align:left;height:35px;" name="order_discount" type="number" step="any" class="form-control pos-amounts" id="order_discount" value="0.00">
                                    </div>
                                </div>

                                <input name="order_discount_amount" type="number" class="d-none" id="order_discount_amount"
                                    value="0.00" tabindex="-1">
                            </div>
                            <label style="text-align:right;background-color:#e6e6e6;margin-left:-18px;height:26px;height:35px;" class="col-sm-3 col-form-label text-white"><b style="color:#666;font-size: 14px;line-height: 1;">@lang('Discount')</b></label>
                        </div>
                    @else
                        <input name="order_discount" type="hidden" id="order_discount" value="0.00" tabindex="-1">
                        <input name="order_discount_amount" type="number" class="d-none" id="order_discount_amount"
                            value="0.00" tabindex="-1">
                        <input name="order_discount_type" class="d-none" id="order_discount_type" value="1">
                    @endif

                    @if (json_decode($generalSettings->pos, true)['is_enabled_order_tax'] == '1')
                        <div class="row">
                            <div class="col-sm-9 mb-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <select style="background-color:#e6e6e6;;height:35px;" name="order_tax" class="form-control pos-amounts" id="order_tax"></select>
                                    </div>

                                    <div class="col-md-6">
                                        <input style="border:none;background-color:#e6e6e6;text-align:left;height:35px;" type="number" class="form-control pos-amounts" name="order_tax_amount" id="order_tax_amount"
                                        value="0.00">
                                    </div>
                                </div>
                            </div>
                            <label class="col-sm-3 col-form-label text-white" style="text-align:right;background-color:#e6e6e6;margin-left:-18px;height:26px;height:35px;"><b style="color:#666;font-size: 14px;line-height: 1;">@lang('Vat/Tax')</label>
                        </div>
                    @else
                        <input name="order_tax" type="hidden" id="order_tax" value="0.00" tabindex="-1">
                        <input type="hidden" name="order_tax_amount" id="order_tax_amount" value="0.00" tabindex="-1">
                    @endif

                    <div class="row">
                        <div class="col-sm-9 mb-2">
                            <input style="border:none;background-color:#e6e6e6;text-align:left;height:35px;" readonly class="form-control pos-amounts" type="number" step="any" name="previous_due"
                                id="previous_due" value="0.00" tabindex="-1">
                        </div>
                        <label style="text-align:right;background-color:#e6e6e6;margin-left:-18px;height:26px;height:35px;" class="col-sm-3 col-form-label text-white"><b style="color:#666;font-size: 14px;line-height: 1;">@lang('Pre') Due</b></label>


                        <div class="col-sm-9 mb-2">
                            <input style="border:none;background-color:#e6e6e6;text-align:left;height:35px;" readonly class="form-control pos-amounts" type="number" step="any"
                                name="total_payable_amount" id="total_payable_amount" value="0.00" tabindex="-1">

                            <input class="d-none" type="number" step="any" name="total_invoice_payable"
                                id="total_invoice_payable" value="0.00" tabindex="-1">
                        </div>
                        <label style="text-align:right;background-color:#e6e6e6;margin-left:-18px;height:26px;height:35px;" class="col-sm-3 col-form-label text-white"><b style="color:#666;font-size: 12px;line-height: 1;">@lang('Payable')</b></label>
                    </div>

                    <div class="row">
                        <div class="col-sm-9 mb-2">
                            {{-- <input  type="number" step="any" name="paying_amount" id="paying_amount" value="0"
                                class="form-control pos-amounts" autocomplete="off"> --}}

                            <div class="input-group">

                                <input style="border:none;background-color:#e6e6e6;text-align:left;height:35px;"  type="number" step="any" name="paying_amount" id="paying_amount" value="0"
                                class="form-control pos-amounts input_i" autocomplete="off">
                            </div>
                        </div>
                        <label style="text-align:right;background-color:#e6e6e6;margin-left:-18px;height:26px;height:35px;" class="col-sm-3 col-form-label "><b style="color:#666;font-size: 12px;line-height: 1;">@lang('Cash Receive')</b></label>
                    </div>

                    <div class="row">
                        <div class="col-sm-9 mb-2">
                            <input style="border:none;background-color:#e6e6e6;text-align:left;height:35px;" readonly type="text" name="change_amount" id="change_amount" value="0.00"
                                class="form-control pos-amounts" tabindex="-1">
                        </div>
                        <label style="text-align:right;background-color:#e6e6e6;margin-left:-18px;height:26px;height:35px;" class="col-sm-3 col-form-label text-white"><b style="color:#666;font-size: 12px;line-height: 1;">@lang('Change Amount')</b></label>
                    </div>

                    <div class="row">
                        <div class="col-sm-9 mb-2">
                            <input style="border:none;background-color:#e6e6e6;text-align:left;height:35px;" type="text" readonly name="total_due" id="total_due" value="0.00"
                                class="form-control pos-amounts" style="color:#d9804f;font-weight:bold;" tabindex="-1">
                        </div>
                        <label style="text-align:right;background-color:#e6e6e6;margin-left:-16px;height:26px;height:35px;" class="col-sm-3 col-form-label"><b style="color:#666;font-size: 12px;line-height: 1;"> @lang('Due') </b></label>
                    </div>
                </div>

                <div class="sub-btn-sec">
                    <div class="row p-2">
                        <div class="col-12 d-flex justify-content-around">

                        <div class="col-5 me-5">
                          <div style="background-color:transparent;border:1px solid #e6e6e6;margin-left:5px;">
                                <a style="width:100%;display:block;color:#666;line-height: 2.5;font-size: 12px;text-align:right;margin-left:-5px" href="#" class="btn-pos" id="reedem_point_button" tabindex="-1">@lang('Reedem Point')</a>
                            </div>
                          </div>

                           <div class="col-5 ms-0">
                           <div  style="background-color:transparent;border:1px solid #e6e6e6;margin-right:5px;">
                                <a style="width:100%;display:block;color:#666;line-height: 2.5;font-size: 12px;text-align:right;margin-left:-5px" href="#" class="btn-pos"
                                    @if (json_decode($generalSettings->pos, true)['is_enabled_credit_full_sale'] == '1')
                                        data-button_type="0"
                                        id="full_due_button"
                                    @else
                                        onclick="
                                            event.preventDefault();
                                            toastr.error('Full credit sale is disabled.');
                                        "
                                    @endif
                                    tabindex="-1"> @lang('Credit Sale')</a>
                            </div>
                           </div>


                        </div>

                        <div class="col-6 btn-bottom ms-auto p-2 ">
                            <div style="background-color:#ff7e27;border-radius:5px;height:90%;" >
                                <a style="width:100%;display:block;" href="#" class="function-card other_payment_method cash-btn" tabindex="-1">
                                    <div class="d-flex justify-content-around" style="margin:-10px 5px">
                                    <div><p style="font-weight:bold;font-size:12px">Ctrl+F</p></div>
                                    <div><strong style="font-size:13px"> @lang('Other Method')<i style="font-size:14px;margin-left:5px" class="fas fa-credit-card"></i></strong></div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-6 btn-bottom me-auto p-2">
                            <div style="background-color:#09aa29;border-radius:5px;height:90%;">
                                <a style="width:100%;display:block;margin-left:-5px" href="#" class="function-card cash-btn" id="submit_btn" data-button_type="1"
                                    data-action_id="1" tabindex="-1">
                                    <div class="d-flex justify-content-around" style="margin:-10px 5px">
                                    <div><p style="font-weight:bold;font-size:12px">F10</p></div>
                                    <div><strong style="font-size:13px;"> @lang('Cash') </strong><i style="font-size:14px;margin-left:5px" class="far fa-money-bill-alt"></i></div>
                                    </div>
                                </a>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var actionMessage = 'Data inserted Successfull.';

    $('#pos_submit_form').on('submit', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var request = $(this).serialize();
        var url = $(this).attr('action');
        $('.submit_preloader').show();
        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){
                $('.loading_button').hide();
                $('.submit_preloader').hide();

                if(!$.isEmptyObject(data.errorMsg)){

                    toastr.error(data.errorMsg,'Attention');
                    return;
                }else if(data.suspendMsg){

                    toastr.success(data.suspendMsg);
                    afterSubmitForm();
                    document.getElementById('search_product').focus();
                }else if(data.holdInvoiceMsg){

                    toastr.success(data.holdInvoiceMsg);
                    afterSubmitForm();
                    document.getElementById('search_product').focus();
                }else {

                    toastr.success(actionMessage);
                    afterSubmitForm();
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{asset('public/assets/css/print/sale.print.css')}}",
                        removeInline: false,
                        printDelay: 1000,
                        header: null,
                    });
                    document.getElementById('search_product').focus();
                }
            },error: function(err) {

                $('.loading_button').hide();
                $('.submit_preloader').hide();
                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                }else if (err.status == 500) {

                    toastr.error('Server error. Please contact the support team.');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {
                    toastr.error(error[0]);
                });
            }
        });
    });

    @if (json_decode($generalSettings->pos, true)['is_enabled_hold_invoice'] == '1')
        //Key shorcut for pic hold invoice
        shortcuts.add('f9',function() {
            $('#hold_invoice_preloader').show();
            pickHoldInvoice();
        });

        // Pick hold invoice
        $(document).on('click', '#pick_hold_btn',function (e) {
            e.preventDefault();
            $('#hold_invoice_preloader').show();
            pickHoldInvoice();
        });

        function pickHoldInvoice() {
            $('#holdInvoiceModal').modal('show');
            $.ajax({
                url:"{{url('sales/pos/pick/hold/invoice/')}}",
                type:'get',
                success:function(data){
                    $('#hold_invoices').html(data);
                    $('#hold_invoice_preloader').hide();
                }
            });
        }
    @endif

    function showStock() {
        $('#stock_preloader').show();
        $('#showStockModal').modal('show');
        $.ajax({
            url:"{{route('sales.pos.branch.stock')}}",
            type:'get',
            success:function(data){
                $('#stock_modal_body').html(data);
                $('#stock_preloader').hide();
            }
        });
    }
</script>
