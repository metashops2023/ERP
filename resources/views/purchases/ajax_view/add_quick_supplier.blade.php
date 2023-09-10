<form id="add_supplier_form" action="{{ route('contacts.supplier.store') }}">
    @csrf
    <div class="form-group row">
        <div class="col-md-3">
            <label><strong>@lang('Name') :</strong>  <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control s_add_input" data-name="Supplier name" id="name" placeholder="@lang('Supplier Name')"/>
            <span class="error error_name"></span>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Phone') :</strong>  <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control s_add_input" data-name="Phone number" id="phone" placeholder="@lang('Phone Number')"/>
            <span class="error error_phone"></span>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Supplier ID') :</strong></label>
            <input type="text" name="contact_id" class="form-control"  placeholder="@lang('Contact ID')"/>
        </div>

        {{-- <div class="col-md-3">
            <label><strong>@lang('Business Name') :</strong></label>
            <input type="text" name="business_name" class="form-control" placeholder="@lang('Business Name')"/>
        </div> --}}


        <div class="col-md-3">
            <label><strong>@lang('Business Name') :</strong></label>
            <select name="add_branch_id" id="add_branch_id" class="form-control">
                <option value="">@lang('Select Business Name')</option>
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
            <input type="text" name="alternative_phone" class="form-control" placeholder="@lang('Alternative Phone Number')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Landline') :</strong></label>
            <input type="text" name="landline" class="form-control" placeholder="@lang('landline Number')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Email') :</strong></label>
            <input type="text" name="email" class="form-control" placeholder="@lang('Email Address')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Date Of Birth') :</strong>  </label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week"></i></span>
                </div>
                <input type="text" name="date_of_birth" class="form-control date-picker" autocomplete="off">
            </div>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><strong>@lang('Tax Number') :</strong>  </label>
            <input type="text" name="tax_number" class="form-control" placeholder="@lang('Tax Bumber')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Opening Balance') :</strong>  </label>
            <input type="number" name="opening_balance" class="form-control" placeholder="@lang('Opening Balance')"/>
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
                            <option value="">@lang('Select term')</option>
                            <option value="1">@lang('Days') </option>
                            <option value="2">@lang('Months')</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><strong>@lang('Address') :</strong></label>
            <input type="text" name="address" class="form-control"  placeholder="@lang('Address')">
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><strong>@lang('City') :</strong></label>
            <input type="text" name="city" class="form-control" placeholder="@lang('City')"/>
        </div>

        <div class="col-md-3">
            <label><b>@lang('State') :</b></label>
            <input type="text" name="state" class="form-control" placeholder="@lang('State')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Country') :</strong></label>
            <input type="text" name="country" class="form-control" placeholder="@lang('Country')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Zip-Code') :</strong></label>
            <input type="text" name="zip_code" class="form-control" placeholder="@lang('zip_code')"/>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-5">
            <label><strong>@lang('Shipping Address') :</strong></label>
            <input type="text" name="shipping_address" class="form-control" placeholder="@lang('Shipping Address')"/>
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
    // Add supplier by ajax
    $('#add_supplier_form').on('submit', function(e){
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        var inputs = $('.s_add_input');
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
            return;
        }

        $('.submit_button').prop('type', 'button');

        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){

                $('#addSupplierModal').modal('hide');
                $('.submit_button').prop('type', 'submit');
                toastr.success("@lang('Supplier added successfully.')");
                $('#add_supplier_form')[0].reset();
                $('.loading_button').hide();
                $('#supplier_id').append('<option value="'+data.id+'">'+ data.name +' ('+data.phone+')'+'</option>');
                $('#supplier_id').val(data.id);
                document.getElementById('search_product').focus();
            },error: function(err) {

                $('.submit_button').prop('type', 'sumbit');
                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                }else if (err.status == 500) {

                    toastr.error('Server error please contact to the support.');
                }
            }
        });
    });

</script>
