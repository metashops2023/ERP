<div class="col-lg-3 p-1">
    <div class="pos-right-inner">
        <div class="check-out-woaper">
            <div class="function-sec">
                <div class="row">
                    <div class="col-4 px-2 py-1">
                        <div class="btn-bg">
                            <a href="#"
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
                                class="bg-orange function-card" tabindex="-1">Draft<p>F2</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4 px-2 py-1">
                        <div class="btn-bg">
                            <a href="#"
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
                                class="bg-orange function-card" tabindex="-1">Quotation<p>F4</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4 px-2 py-1">
                        <div class="btn-bg">
                            <a href="#"
                            onclick="
                                event.preventDefault();
                                toastr.error('Exchange System is not available in pos sale edit section.');
                            "
                            class="bg-orange function-card" tabindex="-1">
                                Exchange<p>F6</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4 px-2 py-1">
                        <div class="btn-bg">
                            <a href="" class="bg-gren function-card" id="show_stock" tabindex="-1">
                                Stock<p>Alt+C</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4 px-2 py-1">
                        <div class="btn-bg">
                            <a href="#"
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
                                class="bg-gren function-card" tabindex="-1">Hold Invoice<p>F8</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4 px-2 py-1">
                        <div class="btn-bg">
                            <a href="#"
                                @if (json_decode($generalSettings->pos, true)['is_enabled_hold_invoice'] == '1')
                                    id="pick_hold_btn"
                                @else
                                    onclick="
                                        event.preventDefault();
                                        toastr.error('Hold invoice is disabled in POS.');
                                    "
                                @endif
                                class="bg-gren function-card" tabindex="-1">Pick Hold <p>F9</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4 px-2 py-1">
                        <div class="btn-bg">
                            <a href="{{ route('settings.general.index') }}" class="bg-swit function-card" tabindex="-1">
                                Setup <p>Ctrl+Q</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4 px-2 py-1">
                        <div class="btn-bg">
                            <a href="#"
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
                                class="bg-swit function-card" tabindex="-1">Suspend<p>Alt+A</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4 px-2 py-1">
                        <div class="btn-bg">
                            <a href="#" class="bg-swit function-card" onclick="cancel(); return false;" tabindex="-1">
                                Cancel
                                <p>Ctrl+M</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wrapper_input_btn">
                <div class="checkout-input-sec">
                    <div class="row">
                        <label for="inputEmail3" class="col-sm-3 col-form-label text-white"><b>Total:</b></label>
                        <div class="col-sm-9">
                            <input readonly type="number" class="form-control sp-input" name="net_total_amount" id="net_total_amount" value="0.00">
                        </div>
                    </div>

                    @if (json_decode($generalSettings->pos, true)['is_enabled_order_tax'] == '1')
                        <div class="row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label text-white">Vat/Tax:</label>
                            <div class="col-sm-9 ">
                                <select name="order_tax" class="form-control" id="order_tax">

                                </select>
                                <input type="number" class="d-none" name="order_tax_amount" id="order_tax_amount"
                                    value="0.00">
                            </div>
                        </div>
                    @else
                        <input name="order_tax" type="hidden" id="order_tax" value="0.00">
                        <input type="hidden" name="order_tax_amount" id="order_tax_amount" value="0.00">
                    @endif

                    @if (json_decode($generalSettings->pos, true)['is_enabled_discount'] == '1')
                        <div class="row">
                            <label for="inputEmail3" class="col-sm-3 col-form-label text-white">Discount:</label>
                            <div class="col-sm-9 ">
                                <input name="order_discount" type="number" step="any" class="form-control"
                                    id="order_discount" value="0.00">
                                <input name="order_discount_amount" type="number" class="d-none" id="order_discount_amount"
                                    value="0.00">
                                <input name="order_discount_type" class="d-none" id="order_discount_type" value="1">
                            </div>
                        </div>
                    @else
                        <input name="order_discount" type="hidden" id="order_discount" value="0.00">
                        <input name="order_discount_amount" type="number" class="d-none" id="order_discount_amount"
                            value="0.00">
                        <input name="order_discount_type" class="d-none" id="order_discount_type" value="1">
                    @endif

                    <div class="row">
                        <label for="inputEmail3" class="col-sm-3 col-form-label text-white">Pre. Due:</label>
                        <div class="col-sm-9 ">
                            <input readonly class="form-control" type="number" step="any" name="previous_due"
                                id="previous_due" value="0.00" autocomplete="off">
                        </div>
                        <label for="inputEmail3" class="col-sm-3 col-form-label text-white">Payable:</label>
                        <div class="col-sm-9 ">
                            <input readonly class="form-control sp-input" type="number" step="any"
                                name="total_payable_amount" id="total_payable_amount" value="0.00">
                            <input class="d-none" type="number" step="any" name="total_invoice_payable"
                                id="total_invoice_payable" value="0.00">
                        </div>
                    </div>

                    <div class="row">
                        <label for="inputEmail3" class="col-sm-6 col-form-label text-white">Cash Receive:</label>
                        <div class="col-sm-6 ">
                            <input type="number" step="any" name="paying_amount" id="paying_amount" value="0"
                                class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <div class="row">
                        <label for="inputEmail3" class="col-sm-6 col-form-label text-white">Change Amount:</label>
                        <div class="col-sm-6 ">
                            <input readonly type="text" name="change_amount" id="change_amount" value="0.00"
                                class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <label for="inputEmail3" class="col-sm-6 col-form-label text-danger"><b>Due :</b></label>
                        <div class="col-sm-6 ">
                            <input type="text" readonly name="total_due" id="total_due" value="0.00"
                                class="form-control sp-input text-danger">
                        </div>
                    </div>
                </div>

                <div class="sub-btn-sec">
                    <div class="row">
                        <div class="col-lg-4 col-12 p-1 pb-1">
                            <div class="btn-bg mb-1">
                                <a href="#" class="bg-orange btn-pos"
                                    @if (json_decode($generalSettings->pos, true)['is_enabled_credit_full_sale'] == '1')
                                        data-button_type="0"
                                        id="full_due_button"
                                    @else
                                        onclick="
                                            event.preventDefault();
                                            toastr.error('Full credit sale is disabled.');
                                        "
                                    @endif
                                tabindex="-1"><i class="fas fa-check"></i> Credit Sale</a>
                            </div>

                            <div class="btn-bg">
                                <a href="#" class="bg-parpal btn-pos" id="reedem_point_button" tabindex="-1">Reedem Point</a>
                            </div>
                        </div>

                        <div class="col-lg-4 col-6 p-1 pb-0 btn-bottom">
                            <div class="btn-bg">
                                <a href="#" class="bg-parpal function-card other_payment_method" tabindex="-1">
                                    <small><i class="fas fa-credit-card"></i> Other Method</small>
                                    <p>Ctrl+B</p>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-4 col-6 p-1 pb-0 btn-bottom">
                            <div class="btn-bg">
                                <a href="#" class="bg-parpal function-card cash-btn" id="submit_btn" data-button_type="1"
                                    data-action_id="1" tabindex="-1">
                                        <small><i class="far fa-money-bill-alt"></i> Cash </small>
                                    <p>F10</p>
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
    $(document).on('click', '#submit_btn', function (e) {
        e.preventDefault();
        var action = $(this).data('action_id');
        if (action == 1) {
            actionMessage = 'Successfully sale is created.';
        }else if (action == 2) {
            actionMessage = 'Successfully draft is created.';
        }else if (action == 3) {
            actionMessage = 'Successfully challan is created.';
        }else if (action == 4) {
            actionMessage = 'Successfully quotation is created.';
        }
        $('#action').val(action);
        $('#pos_submit_form').submit();
    });

    $(document).on('click', '#full_due_button', function (e) {
        e.preventDefault();
        fullDue();
    });

    function fullDue() {
        var total_payable_amount = $('#total_payable_amount').val();
        $('#paying_amount').val(parseFloat(0).toFixed(2));
        $('#change_amount').val(- parseFloat(total_payable_amount).toFixed(2));
        $('#total_due').val(parseFloat(total_payable_amount).toFixed(2));
        $('#action').val(1);
        $('#pos_submit_form').submit();
    }

    var actionMessage = 'Successfull data is inserted.';
    $('#pos_submit_form').on('submit', function(e){
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
                console.log(data);
                if(!$.isEmptyObject(data.errorMsg)){
                    toastr.error(data.errorMsg,'ERROR');
                    $('.submit_preloader').hide();
                    return;
                }else if(data.suspendMsg){
                    toastr.success(data.suspendMsg);
                    window.location = "{{route('sales.pos.create')}}";
                }else if(data.holdInvoiceMsg){
                    toastr.success(data.holdInvoiceMsg);
                    window.location = "{{route('sales.pos.create')}}";
                }else {
                    $('.modal').modal('hide');
                    toastr.success(actionMessage);
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
                        removeInline: false,
                        printDelay: 1000,
                        header: null,
                    });
                    setTimeout(function () {
                        window.location = "{{ url()->previous(); }}";
                    }, 2000);
                }
            }
        });
    });

    function cancel() {
        toastr.error('Sale is cancelled.');
        window.location = "{{route('sales.pos.create')}}";
    }

    //Key shorcut for cancel
    shortcuts.add('ctrl+m',function() {
        cancel();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('f2',function() {
        $('#action').val(2);
        $('#pos_submit_form').submit();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('f4',function() {
        $('#action').val(4);
        $('#pos_submit_form').submit();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('f8',function() {
        $('#action').val(5);
        $('#pos_submit_form').submit();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('f10',function() {
        $('#action').val(1);
        $('#pos_submit_form').submit();
    });

    $('.other_payment_method').on('click', function (e) {
       e.preventDefault();
        $('#otherPaymentMethod').modal('show');
    });

    $(document).on('click', '#cancel_pay_mathod', function (e) {
        e.preventDefault();
        $('#payment_method').val('Cash');
        $('.payment_method').hide();
        $('#otherPaymentMethod').modal('hide');
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('ctrl+b',function() {
        $('#otherPaymentMethod').modal('show');
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('alt+g',function() {
        fullDue();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('alt+s',function() {
        $('#paying_amount').select();
        $('#paying_amount').focus();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('alt+a',function() {
        $('#action').val(6);
        $('#pos_submit_form').submit();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('ctrl+q',function() {
        window.location = "{{ route('settings.general.index') }}";
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('alt+z',function() {
        allSuspends();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('alt+x',function() {
        showRecentTransectionModal();
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

    $(document).on('click', '#show_stock',function (e) {
       e.preventDefault();
       showStock();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('alt+c',function() {
        showStock();
    });

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

    function getEditSaleProducts() {
        $.ajax({
            url:"{{route('sales.pos.invoice.products', $sale->id)}}",
            success:function(invoiceProducts){
                $('#product_list').append(invoiceProducts);
                calculateTotalAmount();
            }
        });
    }
    getEditSaleProducts();
</script>
