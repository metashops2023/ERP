<form id="add_schema_form" action="{{ route('settings.branches.quick.invoice.schema.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label><b>@lang('Preview') : <span id="q_schema_preview"></span></label>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><b>@lang('Name') :</b> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" id="q_name" placeholder="@lang('Schema name')"/>
            <span class="error error_q_name"></span>
        </div>

        <div class="col-md-6">
            <label><b>@lang('Format') :</b> <span class="text-danger">*</span></label>
            <select name="format" class="form-control" id="q_format">
                <option value="1">@lang('FORMAT-XXXX')</option>
                <option value="2">@lang('FORMAT')-{{ date('Y') }}<XXXX</option>
            </select>
            <span class="error error_q_format"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><b>@lang('Prefix') :</b> <span class="text-danger">*</span></label>
            <input type="text" name="prefix" class="form-control" id="q_prefix" placeholder="@lang('Prefix')"/>
            <span class="error error_q_prefix"></span>
        </div>

        <div class="col-md-6">
            <label><b>@lang('Start From') :</b></label>
            <input type="number" name="start_from" class="form-control" id="q_start_from" placeholder="@lang('Start From')" value="0"/>
        </div>
    </div>

    <div class="form-group text-end mt-3">
        <button type="button" class="btn loading_button d-none q_ld_btn"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
        <button type="submit" class="c-btn button-success me-0 float-end">@lang('Save')</button>
        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
    </div>
</form>

<script>
    $(document).on('change', '#q_format', function () {
        var val = $(this).val();
        if (val == 2) {
            $('#q_prefix').val("{{ date('Y') }}"+'/');
            $('#q_prefix').prop('readonly', true);
        }else{
            $('#q_prefix').val("");
            $('#q_prefix').prop('readonly', false);
        }
        previewInvoieId();
    });

    $(document).on('input', '#q_prefix', function () {previewInvoieId();});
    $(document).on('input', '#q_start_from', function () {previewInvoieId();});

    function previewInvoieId() {
        var prefix = $('#q_prefix').val();
        var start_from = $('#q_start_from').val();
        $('#q_schema_preview').html('#'+prefix+start_from);
    }

    // Add category by ajax
    $(document).on('submit', '#add_schema_form',function(e){
        e.preventDefault();
        $('.q_ld_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){
                toastr.success(data);
                $('#add_schema_form')[0].reset();
                $('.q_ld_btn').hide();
                $('#quickInvSchemaModal').modal('hide');
                $('#q_schema_preview').html('');
                $('#q_prefix').prop('readonly', false);
                $('#invoice_schema_id').append('<option SELECTED value="'+data.id+'">'+data.name+'</option>');
            },
            error: function(err) {
                $('.q_ld_btn').hide();
                $('.error').html('');
                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_q_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
