  <!--begin::Form-->
  <form id="edit_branch_form" action="{{ route('settings.branches.update', $branch->id) }}">
    @csrf
    <div class="form-group row">
        <div class="col-md-3">
            <label><strong>@lang('Name') :</strong>  <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control edit_input" data-name="Name" id="e_name" placeholder="@lang('Business Location Name')" value="{{ $branch->name }}"/>
            <span class="error error_e_name"></span>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Location Code') :</strong>  <span class="text-danger">*</span> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Branch code must be unique." class="fas fa-info-circle tp"></i></label>
            <input type="text" name="code" class="form-control edit_input" data-name="Branch code" id="e_code" placeholder="@lang('Business Location code')" value="{{ $branch->branch_code }}"/>
            <span class="error error_e_code"></span>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Phone') :</strong>  <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control  edit_input" data-name="Phone number" id="e_phone" placeholder="@lang('Phone number')" value="{{ $branch->phone }}"/>
            <span class="error error_e_phone"></span>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Alternate Phone Number') :</strong> </label>
            <input type="text" name="alternate_phone_number" class="form-control" id="e_alternate_phone_number" placeholder="@lang('Alternate phone number')" value="{{ $branch->alternate_phone_number }}"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label> <strong>@lang('City') :</strong> <span class="text-danger">*</span></label>
            <input type="text" name="city" class="form-control edit_input" data-name="City" id="e_city" placeholder="@lang('City')" value="{{ $branch->city }}"/>
            <span class="error error_e_city"></span>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('State') :</strong>  <span class="text-danger">*</span></label>
            <input type="text" name="state" class="form-control edit_input" data-name="State" id="e_state" placeholder="@lang('State')" value="{{ $branch->state }}"/>
            <span class="error error_e_state"></span>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Country') :</strong>  <span class="text-danger">*</span></label>
            <input type="text" name="country" class="form-control  edit_input" data-name="country" id="e_country" placeholder="@lang('Country')" value="{{ $branch->country }}"/>
            <span class="error error_e_country"></span>
        </div>

        <div class="col-md-3">
            <label> <strong>@lang('Zip-code') :</strong> <span class="text-danger">*</span></label>
            <input type="text" name="zip_code" class="form-control edit_input" data-name="Zip code" id="e_zip_code" placeholder="@lang('Zip code')" value="{{ $branch->zip_code }}"/>
            <span class="error error_e_zip_code"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label> <strong>@lang('Email') :</strong> </label>
            <input type="text" name="email" class="form-control" id="e_email" placeholder="@lang('Email address')" value="{{ $branch->email }}"/>
        </div>

        <div class="col-md-3">
            <label> <strong>@lang('Website') :</strong> </label>
            <input type="text" name="website" class="form-control" id="e_website" placeholder="@lang('Website URL')" value="{{ $branch->website }}"/>
        </div>

        <div class="col-md-3">
            <label> <strong>@lang('Location Logo') :</strong> <small class="text-danger">@lang('Logo size 200px * 70px')</small> </label>
            <input type="file" name="logo" class="form-control" id="logo"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><strong>@lang('Invoice Schema') :</strong>  <span class="text-danger">*</span></label>
            <select name="invoice_schema_id" id="e_invoice_schema_id" data-name="Add sale pos invoice schema" class="form-control  edit_input">
                <option value="">@lang('Select Please')</option>
                @foreach ($invSchemas as $schema)
                    <option {{ $schema->id == $branch->invoice_schema_id ? 'SELECTED' : '' }} value="{{ $schema->id }}">{{ $schema->name }}</option>
                @endforeach
            </select>
            <span class="error error_e_invoice_schema_id"></span>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('Add Sale Invoice Layout') :</strong>  <span class="text-danger">*</span></label>
            <select name="add_sale_invoice_layout_id" id="e_add_sale_invoice_layout_id" data-name="Add sale invoice layout" class="form-control  edit_input">
                <option value="">@lang('Select Please')</option>
                @foreach ($invLayouts as $layout)
                    <option {{ $layout->id == $branch->add_sale_invoice_layout_id ? 'SELECTED' : '' }} value="{{ $layout->id }}">{{ $layout->name }}</option>
                @endforeach
            </select>
            <span class="error error_e_add_sale_invoice_layout_id"></span>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('POS Sale Invoice Layout') :</strong>  <span class="text-danger">*</span></label>
            <select name="pos_sale_invoice_layout_id" id="e_pos_sale_invoice_layout_id" data-name="POS sale invoice layout" class="form-control  edit_input">
                <option value="">@lang('Select Please')</option>
                @foreach ($invLayouts as $layout)
                    <option {{ $layout->id == $branch->pos_sale_invoice_layout_id ? 'SELECTED' : '' }} value="{{ $layout->id }}">{{ $layout->name }}</option>
                @endforeach
            </select>
            <span class="error error_e_pos_sale_invoice_layout_id"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <div class="row">
                <p class="checkbox_input_wrap mt-2">
            <input type="checkbox" {{ $branch->purchase_permission == 1 ? 'CHECKED' : '' }} name="purchase_permission" id="e_purchase_permission" value="1"> &nbsp; <b>@lang('Enable purchase permission')</b>  </p>
            </div>
        </div>
    </div>

    <div class="form-group text-end">
        <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
        <button type="submit" class="me-0 c-btn button-success float-end">@lang('Save')</button>
        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
    </div>
</form>

<script>
    // edit branch by ajax
    $('#edit_branch_form').on('submit', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        var inputs = $('.edit_input');
            $('.error').html('');
            var countErrorField = 0;
        $.each(inputs, function(key, val){
            var inputId = $(val).attr('id');
            var idValue = $('#'+inputId).val()
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

        $.ajax({
            url:url,
            type:'post',
            data:new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success:function(data){
                $('#editModal').modal('hide');
                toastr.success(data);
                $('.loading_button').hide();
                // getAllBranch();
                table.ajax.reload();
            }
        });
    });
</script>
