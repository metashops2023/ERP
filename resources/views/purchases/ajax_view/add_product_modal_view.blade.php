<style>
    h6.checkbox_input_wrap {font-size: 13px;}
</style>
<form id="add_product_form" action="{{ route('purchases.add.product') }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-3">
            <label><b>@lang('Product Name') :</b> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" id="add_name" placeholder="@lang('Product Name')"/>
            <span class="error error_add_name"></span>
        </div>

        <div class="col-md-3">
            <label><b>@lang('Product Code (SKU)') :</b> <span class="text-danger">*</span></label>
            <input type="text" name="product_code" class="form-control" placeholder="@lang('Product code')"/>
            <span class="error error_add_product_code"></span>
        </div>

        <div class="col-md-3">
            <label><b>@lang('Barcode Type') :</b></label>
            <select class="form-control" name="barcode_type" id="barcode_type">
                <option value="CODE128">@lang('Code 128 (C128)')</option>
                <option value="CODE39">@lang('Code 39 (C39)')</option>
                <option value="EAN13">@lang('EAN')-13</option>
                <option value="UPC">@lang('UPC')</option>
            </select>
        </div>

        <div class="col-md-3 ">
            <label><b> @lang('Unit') :</b> <span class="text-danger">*</span></label>
            <select class="form-control product_unit" name="unit_id" id="add_unit_id">
                <option value="">@lang('Select Unit')</option>
                @foreach ($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->name }}({{ $unit->code_name }})</option>
                @endforeach
            </select>
            <span class="error error_add_unit_id"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        @if (json_decode($generalSettings->product, true)['is_enable_categories'] == '1')
            <div class="col-md-3">
                <label><b>@lang('Category') :</b> </label>
                <select class="form-control category" name="category_id" id="add_category_id">
                    <option value="">@lang('Select Category')</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <span class="error error_add_category_id"></span>
            </div>
        @endif

        @if (json_decode($generalSettings->product, true)['is_enable_categories'] == '1' && json_decode($generalSettings->product, true)['is_enable_sub_categories'] == '1')
            <div class="col-md-3 parent_category">
                <label><b>@lang('Child category') :</b></label>
                <select class="form-control" name="child_category_id"
                    id="add_child_category_id">
                    <option value="">@lang('Select child category first')</option>
                </select>
            </div>
        @endif

        @if (json_decode($generalSettings->product, true)['is_enable_brands'] == '1')
            <div class="col-md-3">
                <label><b>@lang('Brand') :</b></label>
                <select class="form-control" data-live-search="true" name="brand_id"
                    id="add_brand_id">
                    <option value="">@lang('Select Brand')</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        @if (json_decode($generalSettings->product, true)['is_enable_warranty'] == '1')
            <div class="col-md-3">
                <label><b>@lang('Warranty') :</b></label>
                <select class="form-control" name="warranty_id" id="add_warranty_id">
                    <option value="">@lang('Select Warranty')</option>
                    @foreach ($warranties as $warranty)
                        <option value="{{ $warranty->id }}">{{ $warranty->name }} ({{$warranty->type == 1 ? 'Warranty' : 'Guaranty'}})</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-8">
            <label><b>@lang('Description') :</b> </label>
            <textarea  name="product_details" class="form-control" cols="10" rows="3">
            </textarea>
        </div>

        <div class="col-md-4">
            <div class="row mt-5">
                <p class="checkbox_input_wrap p-0 m-0"> <input type="checkbox" name="is_show_in_ecom" id="is_show_in_ecom" value="1"> &nbsp; Product wil be displayed in E-Commerce. &nbsp; </p>
                <p class="checkbox_input_wrap p-0 m-0"> <input type="checkbox" name="is_show_emi_on_pos" id="is_show_emi_on_pos" value="1"> &nbsp; Enable IMEI or SL NO &nbsp;</p>
            </div>
        </div>
    </div>

    <div class="form-group row mt-1">
        @if (json_decode($generalSettings->product, true)['is_enable_price_tax'] == '1')
            <div class="col-md-3 ">
                <label><b>@lang('Tax') :</b> </label>
                <select class="form-control" name="tax_id" id="add_tax_id">
                    <option value="">@lang('NoTax')</option>
                    @foreach ($taxes as $tax)
                        <option value="{{ $tax->id.'-'.$tax->tax_percent }}">{{ $tax->tax_name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="col-md-3">
            <label><b>@lang('Alert quentity') :</b></label>
            <input type="number" name="alert_quantity" class="form-control"
                autocomplete="off" id="add_alert_quantity" value="0">
        </div>
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-12">
            <table class="table modal-table table-sm custom-table">
                <thead>
                    <tr class="bg-primary">
                        <th class="text-white">@lang('Default Purchase Price')</th>
                        <th class="text-white">@lang('x Margin')(%)</th>
                        <th class="text-white">@lang('Selling Price')</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="row">
                                <div class="col-md-6 text-start">
                                    <label><b>@lang('Unit Cost Exc').Tax :</b> <span class="text-danger">*</span></label>
                                    <input type="number" step="any" name="product_cost" id="add_product_cost" class="form-control" autocomplete="off" placeholder="@lang('Unit Cost Exc.Tax')">
                                    <span class="error error_add_product_cost"></span>
                                </div>
                                <div class="col-md-6 text-start">
                                    <label><b>@lang('Unit Cost Inc').Tax :</b> <span class="text-danger">*</span></label>
                                    <input type="number" step="any" name="product_cost_with_tax"
                                    class="form-control" autocomplete="off"
                                    id="add_product_cost_with_tax" placeholder="@lang('Unit Cost Inc.Tax')">
                                    <span class="error error_add_product_cost_with_tax"></span>
                                </div>
                            </div>
                        </td>

                        <td>
                            <label></label>
                            <input type="number" step="any" name="profit" class="form-control" autocomplete="off" id="add_profit" value="{{ json_decode($generalSettings->business, true)['default_profit'] }}"
                            placeholder="@lang('Profix Margin')">
                        </td>

                        <td class="text-start">
                            <label><b>@lang('Price Exc').Tax :</b> <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="product_price" class="form-control"
                                    autocomplete="off" id="add_product_price" placeholder="@lang('Price Exc.Tax')">
                            <span class="error error_add_product_price"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i
                    class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn button-success me-0 float-end submit_button">@lang('Save')</button>
            <button type="reset" data-bs-dismiss="modal"
                class="c-btn btn_orange float-end">@lang('Close')</button>
        </div>
    </div>
</form>

<script>
    var tax_percent = 0;
    $(document).on('change', '#add_tax_id',function() {
        var tax = $(this).val();
        if (tax) {
            var split = tax.split('-');
            tax_percent = split[1];
        } else {
            tax_percent = 0;
        }
    });

    function costCalculate() {
        var product_cost = $('#add_product_cost').val() ? $('#add_product_cost').val() : 0;
        var calc_product_cost_tax = parseFloat(product_cost) / 100 * parseFloat(tax_percent ? tax_percent : 0);
        var product_cost_with_tax = parseFloat(product_cost) + calc_product_cost_tax;
        $('#add_product_cost_with_tax').val(parseFloat(product_cost_with_tax).toFixed(2));
        var profit = $('#add_profit').val() ? $('#add_profit').val() : 0;
        var calculate_profit = parseFloat(product_cost) / 100 * parseFloat(profit);
        var product_price = parseFloat(product_cost) + parseFloat(calculate_profit);
        $('#add_product_price').val(parseFloat(product_price).toFixed(2));
    }

    $(document).on('input', '#add_product_cost',function() {
        console.log($(this).val());
        costCalculate();
    });

    $(document).on('change', '#add_tax_id', function() {
        costCalculate();
    });

    $(document).on('input', '#add_profit',function() {
        costCalculate();
    });

    // Add product by ajax
    $('#add_product_form').on('submit', function(e) {
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $('.submit_button').prop('type', 'button');
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.error').html('');
                toastr.success('Successfully product is added.');
                $.ajax({
                    url:"{{url('purchases/recent/product')}}"+"/"+data.id,
                    type:'get',
                    success:function(data){

                        $('#addProductModal').modal('hide');
                        $('.loading_button').hide();
                        $('.submit_button').prop('type', 'submit');
                        $('#purchase_list').prepend(data);
                        calculateTotalAmount();
                        document.getElementById('search_product').focus();
                    }
                });
            },
            error : function(err) {

                $('.error').html('');
                $('.loading_button').hide();
                $('.submit_button').prop('type', 'submit');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                }else if (err.status == 500) {

                    toastr.error('Server error please contact to the support.');
                }

                toastr.error("@lang('Please check again all form fields.')",
                    "@lang('Something went wrong.')");

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_add_' + key + '').html(error[0]);
                });
            }
        });
    });

    $('#add_category_id').on('change', function () {
        var category_id = $(this).val();
        $.ajax({
            url:"{{ url('common/ajax/call/category/subcategories/') }}"+"/"+category_id,
            async:true,
            type:'get',
            dataType: 'json',
            success:function(subcate){

                $('#add_child_category_id').empty();
                $('#add_child_category_id').append('<option value="">@lang('Select Sub-Category')</option>');

                $.each(subcate, function(key, val){

                    $('#add_child_category_id').append('<option value="'+val.id+'">'+val.name+'</option>');
                });
            }
        });
    });
</script>
