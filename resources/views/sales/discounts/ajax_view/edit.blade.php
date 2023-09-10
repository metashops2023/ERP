<form id="edit_discount_form" action="{{ route('sales.discounts.update', $discount->id) }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-12">
            <label><strong>@lang('Name') :</strong> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control add_input" data-name="Offer name" id="name"
                placeholder="@lang('Discount name')" value="{{ $discount->name }}" autocomplete="off"/>
            <span class="error error_name"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><strong>@lang('Priority') <i data-bs-toggle="tooltip" data-bs-placement="right"
                        title="Leave empty to auto generate." class="fas fa-info-circle tp"></i> :</strong> <span class="text-danger">*</span> </label>
            <input type="text" name="priority" class="form-control add_input" data-name="Priority" id="priority" placeholder="@lang('Priority')" value="{{ $discount->priority }}" autocomplete="off"/>
            <span class="error error_priority"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><strong>@lang('Start At') :</strong> </label>
            <input type="text" name="start_at" id="e_start_at" class="form-control add_input" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($discount->start_at)) }}">
            <span class="error error_start_at"></span>
        </div>

        <div class="col-md-6">
            <label><strong>@lang('End At') :</strong></label>
            <input type="text" name="end_at" id="e_end_at" class="form-control add_input" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($discount->end_at)) }}">
            <span class="error error_end_at"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><strong>@lang('Products') :</strong> </label>
            <select name="product_ids[]" class="form-control select2" multiple="multiple" id="e_product_ids">
                @foreach ($products as $product)
                    <option 
                        @foreach ($discountProducts as $discountProduct)
                            {{ $product->id == $discountProduct->product_id ? 'SELECTED' : '' }}
                        @endforeach
                    value="{{ $product->id }}">{{ $product->name . ' (' . $product->product_code . ')' }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row mt-1 e_brand_category_area {{ count($discountProducts) > 0 ? 'd-none' : '' }}">
        <div class="col-md-6">
            <label><strong>@lang('Brand') :</strong> </label>
            <select name="brand_id" id="brand_id" class="form-control add_input">
                <option value="">@lang('Please select') </option>
                @foreach ($brands as $brand)
                    <option {{ $discount->brand_id == $brand->id ? 'SELECTED' : '' }} value="{{ $brand->id }}">
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label><strong>@lang('Category') :</strong></label>
            <select name="category_id" id="category_id" class="form-control add_input">
                <option value="">@lang('Please select') </option>
                @foreach ($categories as $category)
                    <option {{ $discount->category_id == $category->id ? 'SELECTED' : '' }} value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><strong>@lang('Discount Type') :</strong> </label>
            <select name="discount_type" id="discount_type" class="form-control add_input">
                <option {{ $discount->discount_type == 1 ? 'SELECTED' : '' }} value="1">@lang('Fixed')(0.00)</option>
                <option {{ $discount->discount_type == 2 ? 'SELECTED' : '' }} value="1">@lang('Percentage')(%)</option>
            </select>
            <span class="error error_discount_type"></span>
        </div>

        <div class="col-md-6">
            <label><strong>@lang('Discount Amount') :</strong></label>
            <input type="number" name="discount_amount" id="discount_amount" class="form-control add_input" value="{{ $discount->discount_amount }}" autocomplete="off">
            <span class="error error_discount_amount"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><strong>@lang('Selling Price Group') :</strong> </label>
            <select name="price_group_id" id="price_group_id" class="form-control">
                <option value="">@lang('Default Price')</option>
                @foreach ($price_groups as $price_group)
                    <option {{ $discount->price_group_id == $price_group->id ? 'SELECTED' : '' }} value="{{ $price_group->id }}">
                        {{ $price_group->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mt-1">
        <div class="col-md-6">
            <div class="input-group mt-1">
                <div class="col-12">
                    <div class="row">
                        <p class="checkbox_input_wrap">
                            <input {{ $discount->apply_in_customer_group == 1 ? 'CHECKED' : '' }} type="checkbox" name="apply_in_customer_group" id="apply_in_customer_group"> &nbsp;
                            Apply in customer Group
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="input-group mt-1">
                <div class="col-12">
                    <div class="row">
                        <p class="checkbox_input_wrap">
                            <input {{ $discount->is_active == 1 ? 'CHECKED' : '' }} type="checkbox" name="is_active" id="is_active"> &nbsp; Is Active
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b>@lang('Loading')...</b></button>
            <button type="submit" class="c-btn button-success me-0 float-end submit_button">@lang('Save Changes')</button>
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
        </div>
    </div>
</form>

<script>
    $('.select2').select2({
        placeholder: "Select a products",
        allowClear: true
    });

    // Add discount by ajax
    $('#edit_discount_form').on('submit', function(e) {
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

                toastr.success(data);
                table.ajax.reload();
                $('.loading_button').hide();
                $('#editModal').modal('hide');
                $('.submit_button').prop('type', 'submit');
            },error: function(err) {
            
                $('.submit_button').prop('type', 'sumbit');
                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.'); 
                    return;
                }else if (err.status == 500){

                    toastr.error('Server error please contact to the support.');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_e_' + key + '').html(error[0]);
                });
            }
        });
    });

    $('#e_product_ids').on('change', function () {

        $('.e_brand_category_area').removeClass('d-none');
        if ($(this).val().length > 0) {

            $('.e_brand_category_area').hide();
        }else{

            $('.e_brand_category_area').show();
        }
    });

    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');

    new Litepicker({
        singleMode: true,
        element: document.getElementById('e_start_at'),
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

    new Litepicker({
        singleMode: true,
        element: document.getElementById('e_end_at'),
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
