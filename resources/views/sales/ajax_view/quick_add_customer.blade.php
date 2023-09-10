<form id="add_customer_form" action="{{ route('contacts.customer.store') }}">
    @csrf
    <div class="form-group row">
        <div class="col-md-3">
            <label><strong>@lang('Name') :</strong>  <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control c_add_input" data-name="Customer name" id="name" placeholder="@lang('Customer name')"/>
            <span class="error error_name"></span>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Phone') :</strong> <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control c_add_input" data-name="Phone number" id="phone" placeholder="@lang('Phone number')"/>
            <span class="error error_phone"></span>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Contact ID') :</strong></label>
            <input type="text" name="contact_id" class="form-control"  placeholder="@lang('Contact ID')"/>
        </div>

        {{-- <div class="col-md-3">
            <label><strong>@lang('Business Name') :</strong></label>
            <input type="text" name="business_name" class="form-control" placeholder="@lang('Business name')"/>
        </div> --}}
        <div class="col-md-3">
            <label><strong>@lang('Business Name') :</strong></label>
            <select name="add_branch_id" id="add_branch_id">
                @foreach ($branches as $branch)
                <option value="{{ $branch->id }}">
                    {{ $branch->name . '/' . $branch->branch_code }}
                </option>
                @endforeach
            </select>
            <!-- <input type="text" name="business_name" class="form-control" placeholder="@lang('Business name')" /> -->
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><strong>@lang('Alternative Number') :</strong>  </label>
            <input type="text" name="alternative_phone" class="form-control" placeholder="@lang('Alternative phone number')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Landline') :</strong></label>
            <input type="text" name="landline" class="form-control" placeholder="@lang('landline number')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Email') :</strong></label>
            <input type="text" name="email" class="form-control" placeholder="@lang('Email address')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Date Of Birth') :</strong></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week"></i></span>
                </div>
                <input type="text" name="date_of_birth" id="date_of_birth" class="form-control" autocomplete="off">
            </div>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><strong>@lang('Tax Number') :</strong></label>
            <input type="text" name="tax_number" class="form-control" placeholder="@lang('Tax number')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Opening Balance') :</strong>  </label>
            <input type="number" name="opening_balance" class="form-control" placeholder="@lang('Opening balance')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Credit Limit') :</strong> <i data-bs-toggle="tooltip" data-bs-placement="right" title="If there is no credit limit of this customer, so leave this field empty." class="fas fa-info-circle tp"></i></label>
            <input type="number" step="any" name="credit_limit" class="form-control"
                placeholder="@lang('Credit Limit')" value=""/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Pay Term') :</strong>  </label>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-5">
                        <input type="text" name="pay_term_number" class="form-control" placeholder="@lang('Number')"/>
                    </div>

                    <div class="col-md-7">
                        <select name="pay_term" class="form-control">
                            <option value="1">@lang('Select term')</option>
                            <option value="2">@lang('Days') </option>
                            <option value="3">@lang('Months')</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-3">
            <label><strong>@lang('Customer Group') :</strong>  </label>
            <select name="customer_group_id" class="form-control" id="customer_group_id">
                <option value="">@lang('None')</option>
                @foreach ($customerGroups as $customerGroup)
                    <option value="{{ $customerGroup->id }}">{{ $customerGroup->group_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-9">
            <label><strong>@lang('Address') :</strong>  </label>
            <input type="text" name="address" class="form-control"  placeholder="@lang('Address')">
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-3">
            <label><strong>@lang('City') :</strong>  </label>
            <input type="text" name="city" class="form-control" placeholder="@lang('City')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('State') :</strong>  </label>
            <input type="text" name="state" class="form-control" placeholder="@lang('State')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Country') :</strong>  </label>
            <input type="text" name="country" class="form-control" placeholder="@lang('Country')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Zip-Code') :</strong>  </label>
            <input type="text" name="zip_code" class="form-control" placeholder="@lang('zip_code')"/>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-5">
            <label><strong>@lang('Shipping Address') :</strong>  </label>
            <input type="text" name="shipping_address" class="form-control" placeholder="@lang('Shipping address')"/>
        </div>
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn button-success me-0 float-end submit_button">@lang('Save')</button>
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
        </div>
    </div>
</form>

<script>
    // Add customer by ajax
    $('#add_customer_form').on('submit', function(e){
        e.preventDefault();

        $('.loading_button').show();
        $('.submit_button').prop('button');
        var url = $(this).attr('action');
        var request = $(this).serialize();
        var inputs = $('.c_add_input');
            $('.error').html('');
            var countErrorField = 0;

        $.each(inputs, function(key, val){

            var inputId = $(val).attr('id');
            var idValue = $('#'+inputId).val();

            if(idValue == ''){

                countErrorField += 1;
                var fieldName = $('#'+inputId).data('name');
                $('.error_'+inputId).html(fieldName+' is required.');
            }
        });

        if(countErrorField > 0){

            $('.loading_button').hide();
            $('.submit_button').prop('submit');
            return;
        }

        $('.submit_button').prop('type', 'button');
        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){

                $('#addCustomerModal').modal('hide');
                $('.submit_button').prop('type', 'submit');
                toastr.success('Customer added successfully.');
                $('.loading_button').hide();
                $('#customer_id').append('<option value="'+data.id+'">'+ data.name +' ('+data.phone+')'+'</option>');
                $('#customer_id').val(data.id);
                $('#display_pre_due').val(parseFloat(data.total_sale_due).toFixed(2));
                $('#previous_due').val(parseFloat(data.total_sale_due).toFixed(2));
                calculateTotalAmount();
            }
        });

    });
    new Litepicker({
        singleMode: true,
        element: document.getElementById('date_of_birth'),
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
        format: 'YYYY-MM-DD',
    });
</script>

