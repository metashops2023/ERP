@extends('layout.master')
@push('stylesheets')
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-desktop"></span>
                                <h5>@lang('Expense Category')</h5>
                            </div>
                        </div>
                    </div>

                    <div class="row px-3 mt-1">
                        <div class="col-md-4">
                            <div class="card" id="add_form">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>@lang('Add Expense Category')</h6>
                                    </div>
                                </div>

                                <div class="form-area px-3 pb-2">
                                    <form id="add_category_form" action="{{ route('expenses.categories.store') }}">
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label><b>@lang('Name') :</b> <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control add_input" data-name="Category name" id="name" placeholder="@lang('Expense Category Name')"/>
                                                <span class="error error_name"></span>
                                            </div>
                                        </div>

                                        <div class="form-group row mt-1">
                                            <div class="col-md-12">
                                                <label><b>@lang('Code') :</b></label>
                                                <input type="text" name="code" class="form-control" data-name="Expense category Code" placeholder="@lang('Code')"/>
                                                <span class="error error_code"></span>
                                            </div>
                                        </div>

                                        <div class="form-group row mt-2">
                                            <div class="col-md-12">
                                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                                <button type="submit" class="c-btn button-success float-end me-0">@lang('Save')</button>
                                                <button type="reset" class="c-btn btn_orange float-end">@lang('Reset')</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card" style="display:none;" id="edit_form">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>@lang('Edit Expense Category')</h6>
                                    </div>
                                </div>

                                <div class="form-area px-3 pb-2">
                                    <form id="edit_category_form" action="{{ route('expenses.categories.update') }}">
                                        <input type="hidden" name="id" id="id">
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label><strong>@lang('Name') :</strong>  <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control edit_input" data-name="Category name" id="e_name" placeholder="@lang('Expense Category Name')"/>
                                                <span class="error error_e_name"></span>
                                            </div>
                                        </div>

                                        <div class="form-group row text-right mt-2">
                                            <div class="col-md-12">
                                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                                <button type="submit" class="c-btn button-success float-end me-0">@lang('Save Changes')</button>
                                                <button type="button" id="close_form" class="c-btn btn_orange float-end">@lang('Close')</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>@lang('All Expense Categories')</h6>
                                    </div>
                                </div>
                                <!--begin: Datatable-->
                                <div class="widget_content">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6>
                                    </div>
                                    <div class="widget_content">
                                        <div class="table-responsive" id="data-list">
                                            <table class="display data_tbl data__table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-start">@lang('Serial')</th>
                                                        <th class="text-start">@lang('Name')</th>
                                                        <th class="text-start">@lang('Code')</th>
                                                        <th class="text-start">@lang('Actions')</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <form id="deleted_form" action="" method="post">
                                    @method('DELETE')
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    // Get all category by ajax
    function getAllCateogry(){
        $('.data_preloader').show();
        $.ajax({
            url:"{{ route('expenses.categories.all.category') }}",
            type:'get',
            success:function(data){
                $('#data-list').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getAllCateogry();

    // Setup ajax for csrf token.
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    // call jquery method
    $(document).ready(function(){
        // Add category by ajax
        $('#add_category_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.add_input');
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

            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    toastr.success(data);
                    $('#add_category_form')[0].reset();
                    $('.loading_button').hide();
                    getAllCateogry();
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e){
            e.preventDefault();
            $('.error').html('');
            var categoryInfo = $(this).closest('tr').data('info');
            $('#id').val(categoryInfo.id);
            $('#e_name').val(categoryInfo.name);
            $('#add_form').hide();
            $('#edit_form').show();
            document.getElementById('e_name').focus();
        });

        // edit category by ajax
        $('#edit_category_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.edit_input');
                inputs.removeClass('is-invalid');
                $('.error').html('');
                var countErrorField = 0;
            $.each(inputs, function(key, val){
                var inputId = $(val).attr('id');
                var idValue = $('#'+inputId).val();
                if(idValue == ''){
                    countErrorField += 1;
                    $('#'+inputId).addClass('is-invalid');
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
                data: request,
                success:function(data){
                    console.log(data);
                    toastr.success(data);
                    $('.loading_button').hide();
                    getAllCateogry();
                    $('#add_form').show();
                    $('#edit_form').hide();
                }
            });
        });

        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
            'title': "@lang('Delete Confirmation')",
            'content': "@lang('Are you sure, you want to delete?')",
            'buttons': {
                @lang("YES"): {'class': 'yes btn-modal-primary','action': function() {$('#deleted_form').submit();}},
                @lang("NO"): {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
            }
        });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                async:false,
                data:request,
                success:function(data){
                    getAllCateogry();
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                }
            });
        });

        $(document).on('click', '#close_form', function() {
            $('#add_form').show();
            $('#edit_form').hide();
            $('.error').html('');
        });
    });
</script>
@endpush
