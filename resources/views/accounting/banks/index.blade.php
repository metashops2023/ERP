@extends('layout.master')
@push('stylesheets')
@endpush
{{-- @section('title', 'Bank List - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">

                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-university"></span>
                                <h5>@lang('Banks')</h5>
                            </div>
                        </div>
                    </div>

                    <div class="row margin_row mt-1">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>@lang('All Banks')</h6>
                                </div>

                                <div class="col-md-6">
                                    <div class="btn_30_blue float-end">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i
                                                class="fas fa-plus-square"></i> @lang('Add')</a>
                                    </div>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th class="text-start">SL</th>
                                                <th class="text-start">@lang('Bank Name')</th>
                                                <th class="text-start">@lang('Branch Name')</th>
                                                <th class="text-start">@lang('Address')</th>
                                                <th class="text-start">@lang('Action')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Add Bank')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_bank_form" action="{{ route('accounting.banks.store') }}">
                        <div class="form-group">
                            <label><b>@lang('Bank Name')</b> : <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm add_input" data-name="Bank name" id="name" placeholder="@lang('Bank name')"/>
                            <span class="error error_name"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><b>@lang('Branch Name')</b> : <span class="text-danger">*</span></label>
                            <input type="text" name="branch_name" class="form-control form-control-sm add_input" data-name="Branch name" id="branch_name" placeholder="@lang('Branch name')"/>
                            <span class="error error_branch_name"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><b>@lang('Bank Address')</b> :</label>
                            <textarea name="address" class="form-control form-control-sm"  id="address" cols="10" rows="3" placeholder="@lang('Bank address')"></textarea>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                <button type="submit" class="c-btn me-0 button-success float-end submit_button">@lang('Save')</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="c-btn btn_orange float-end">@lang('Close')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Edit Bank')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="edit_bank_form" action="{{ route('accounting.banks.update') }}">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label><b>@lang('Bank Name')</b> : <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm edit_input" data-name="Bank name" id="e_name" placeholder="@lang('Bank name')"/>
                            <span class="error error_e_name"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><b>@lang('Branch Name')</b> : <span class="text-danger">*</span></label>
                            <input type="text" name="branch_name" class="form-control form-control-sm edit_input" data-name="Branch name" id="e_branch_name" placeholder="@lang('Branch name')"/>
                            <span class="error error_e_branch_name"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><b>@lang('Bank Address')</b> : </label>
                            <textarea name="address" class="form-control form-control-sm" id="e_address" cols="10" rows="3" placeholder="@lang('Bank address')"></textarea>
                        </div>

                        <div class="form-group text-right mt-3">
                            <button type="button" class="btn loading_button d-none"><i
                                    class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                            <button type="submit" class="me-0 c-btn button-success float-end">@lang('Save')</button>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Get all category by ajax
        function getAllBank() {
            $('.data_preloader').show();
            $.ajax({
                url: "{{ route('accounting.banks.all.bank') }}",
                type: 'get',
                success: function(data) {
                    $('.table-responsive').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        getAllBank();

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method
        $(document).ready(function() {
            // Add bank by ajax
            $('#add_bank_form').on('submit', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                var inputs = $('.add_input');
                $('.error').html('');
                var countErrorField = 0;
                $.each(inputs, function(key, val) {
                    var inputId = $(val).attr('id');
                    var idValue = $('#' + inputId).val();
                    if (inputId !== 'parent_category' && inputId !== 'photo') {
                        if (idValue == '') {
                            countErrorField += 1;
                            var fieldName = $('#' + inputId).data('name');
                            $('.error_' + inputId).html(fieldName + ' is required.');
                        }
                    }
                });

                if (countErrorField > 0) {
                    $('.loading_button').hide();
                    return;
                }

                $('.submit_button').prop('type', 'button');
                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        toastr.success(data);
                        $('#add_bank_form')[0].reset();
                        $('.loading_button').hide();
                        getAllBank();
                        $('#addModal').modal('hide');
                        $('.submit_button').prop('type', 'sumbit');
                    }
                });
            });

            // pass editable data to edit modal fields
            $(document).on('click', '#edit', function(e) {
                e.preventDefault();
                $('.form-control').removeClass('is-invalid');
                $('.error').html('');
                var bank = $(this).closest('tr').data('info');
                console.log(bank);
                $('#id').val(bank.id);
                $('#e_name').val(bank.name);
                $('#e_branch_name').val(bank.branch_name);
                $('#e_address').val(bank.address);
                $('#editModal').modal('show');
            });

            // edit bank by ajax
            $('#edit_bank_form').on('submit', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                var inputs = $('.edit_input');
                $('.error').html('');
                var countErrorField = 0;
                $.each(inputs, function(key, val) {
                    var inputId = $(val).attr('id');
                    var idValue = $('#' + inputId).val();
                    if (idValue == '') {
                        countErrorField += 1;
                        var fieldName = $('#' + inputId).data('name');
                        $('.error_' + inputId).html(fieldName + ' is required.');
                    }
                });

                if (countErrorField > 0) {
                    $('.loading_button').hide();
                    return;
                }

                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        console.log(data);
                        toastr.success(data);
                        $('.loading_button').hide();
                        getAllBank();
                        $('#editModal').modal('hide');
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
            $(document).on('submit', '#deleted_form', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'post',
                    async: false,
                    data: request,
                    success: function(data) {
                        getAllBank();
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
                    }
                });
            });
        });

    </script>
@endpush
