<style>
    .search_item_area{position: relative;}
    .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute;width: 100%;z-index: 9999999;padding: 0;left: 0%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
    .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
    .select_area ul li a {color:#464343;text-decoration: none;font-size: 12px;padding: 2px 3px;display: block;line-height: 15px;border: 1px solid #968e92;font-weight: 500;}
    .select_area ul li a:hover {background-color: #ab1c59;color: #fff;}
    .selectProduct {background-color: #ab1c59;color: #fff !important;}
    .text-info {color: #0795a5!important;}
</style>

<div class="head-pos">
    <input type="hidden" name="sale_id" value="{{ $sale->id }}">
    <input type="hidden" name="sale_account_id" value="{{ $sale->sale_account_id }}">
    <input type="hidden" name="action" id="action" value="">
    <nav class="pos-navigation">
        <div class="col-lg-4 col-sm-12 col-12 nav-left-sec">
            <div class="col-lg-4 col-sm-12 col-12 logo-sec">
                <div class="pos-logo">
                    @if (auth()->user()->branch)
                        @if (auth()->user()->branch->logo != 'default.png')
                            <img style="height: 40px; width:100px;" src="{{ asset('public/uploads/branch_logo/' . auth()->user()->branch->logo) }}">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:white;">{{ auth()->user()->branch->name }}</span>
                        @endif
                    @else
                        @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                            <img style="height: 40px; width:100px;" src="{{ asset('public/uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:white;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                        @endif
                    @endif
                </div>
            </div>

            <div class="col-lg-8 col-sm-12 col-12 address">
                <p class="store-name">
                    {{ json_decode($generalSettings->business, true)['shop_name'] }} (HEAD OFFICE)
                </p>
                <p class="address-name">
                    @if ($sale->branch)
                        {{ $sale->branch->name.'-'.$sale->branch->branch_code }}
                        {{ $sale->branch->city ? ','.$sale->branch->city : ''}}
                        {{ $sale->branch->state ? ','.$sale->branch->state : ''}}
                        {{ $sale->branch->country ? ','.$sale->branch->country : ''}}
                    @else
                        {{ json_decode($generalSettings->business, true)['address'] }}
                    @endif
                </p>
                <small class="login-user-name">
                    <span class="text-highlight">@lang('Loggedin') :</span> {{ $sale->admin ? $sale->admin->prefix.' '.$sale->admin->name.' '.$sale->admin->last_name : 'N/A' }}
                    <span>
                        <span class="text-highlight">C.Register :</span>
                        @if ($sale->admin)
                            @if ($sale->admin->role_type == 1)
                                Super-Admin
                            @elseif($sale->admin->role_type == 2)
                                Admin
                            @else
                                {{ $sale->admin->role->name }}
                            @endif
                        @endif
                    </span>
                </small>
            </div>
        </div>
        <div class="col-lg-8 col-sm-12 col-12 input-buttob-sec">
            <div class="input-section">
                <div class="row">
                    <div class="input-sec col-sm-8 col-12">
                        <div class="row">
                            <div class="col-lg-7 col-12 sm-input-sec-w">
                                <div class="input-group mb-1">
                                    <input readonly type="text" class="form-control form-select" value="{{ $sale->customer ? $sale->customer->name.' ('.$sale->customer->phone.')' : 'Walk-In-Customer' }}">
                                </div>

                                <div class="search_item_area">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                        </div>
                                        <input type="text" name="search_product" class="form-control" id="search_product" placeholder="@lang('Scan/Search Items by SKU/Barcode')" autofocus>
                                        @if (auth()->user()->permission->product['product_add'] == '1')
                                            <div class="input-group-append add_button" id="add_product">
                                                <span class="input-group-text"><i class="fas fa-plus"></i></span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="select_area">
                                        <ul id="list" class="variant_list_area"></ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-5 input-value-sec">
                                @if (json_decode($generalSettings->reward_poing_settings, true)['enable_cus_point'] == '1')
                                    <div class="input-group mb-1">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text valus">@lang('Point')</span>
                                        </div>
                                        <input readonly type="number" step="any" class="form-control" name="earned_point" id="earned_point">
                                        <div class="input-group-prepend ms-1">
                                            <span class="input-group-text valus"> = {{ json_decode($generalSettings->business, true)['currency'] }}</span>
                                        </div>
                                        <input readonly type="text" class="form-control" id="trial_point_amount">
                                    </div>
                                @endif
                                <div class="input-group col-6">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text valus">SQ</span>
                                    </div>
                                    <input type="text" class="form-control" id="stock_quantity">

                                    <div class="input-group-prepend ms-1">
                                        <select name="price_group_id" class="form-control" id="price_group_id">
                                            <option value="">@lang('Default Selling Price')</option>
                                            @foreach ($price_groups as $pg)
                                                <option value="{{ $pg->id }}">{{ $pg->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 btn-section">
                        <div class="date">
                            <p>{{ date('d-m-Y') }} <span id="time">6:58 AM</span></p>
                        </div>

                        <div class="btn-sec">
                            <a href="{{ route('sales.pos.suspended.list') }}" class="pos-btn status" id="suspends" tabindex="-1"><i class="fas text-warning fa-pause"></i></a>
                            <a href="" class="pos-btn mr-1" data-bs-toggle="modal" data-bs-target="#calculatorModal" tabindex="-1">
                                <span class="fas fa-calculator"></span>
                            </a>
                            {{-- <a href="" class="pos-btn"><span class="fas fa-briefcase"></span></a>
                            <a href="" class="pos-btn text-danger"><span class="fas fa-times"></span></a> --}}
                            <a href="" class="pos-btn" tabindex="-1"><span class="fas fa-bell"></span></a>
                            <a href="" class="pos-btn" id="pos_exit_button" tabindex="-1"><span class="fas fa-backward"></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>
<script>
    // Get all price group
    var price_groups = '';
    function getPriceGroupProducts(){
        $.ajax({
            url:"{{ route('sales.product.price.groups') }}",
            success:function(data){
                price_groups = data;
            }
        });
    }
    getPriceGroupProducts();

    $('#add_product').on('click', function () {
        $.ajax({
            url:"{{route('sales.add.product.modal.view')}}",
            type:'get',
            success:function(data){
                $('#add_product_body').html(data);
                $('#addProductModal').modal('show');
            }
        });
    });

    // Add product by ajax
    $(document).on('submit', '#add_product_form', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                toastr.success('Successfully product is added.');
                $.ajax({
                    url:"{{url('sales/pos/get/recent/product')}}"+"/"+data.id,
                    type:'get',
                    success:function(data){

                        $('.loading_button').hide();
                        $('#addProductModal').modal('hide');

                        if (!$.isEmptyObject(data.errorMsg)) {

                            toastr.error(data.errorMsg);
                        }else{

                            $('#product_list').prepend(data);
                            calculateTotalAmount();
                        }
                    }
                });
            },
            error: function(err) {

                $('.loading_button').hide();
                toastr.error('Please check again all form fields.', 'Some thing went wrong.');
                $('.error').html('');
                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_sale_' + key + '').html(error[0]);
                });
            }
        });
    });

    $(document).on('click', '#suspends',function (e) {
        e.preventDefault();
        allSuspends();
    });

    function allSuspends() {

        $('#suspendedSalesModal').modal('show');
        $('#suspend_preloader').show();
        $.ajax({
            url:"{{ route('sales.pos.suspended.list') }}",
            async:true,
            success:function(data){

                $('#suspended_sale_list').html(data);
                $('#suspend_preloader').hide();
            }
        });
    }
</script>
<!-- Pos Header End-->
