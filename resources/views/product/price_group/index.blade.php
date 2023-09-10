@extends('layout.master')
@push('stylesheets')

@endpush
{{-- @section('title', 'Selling Price Groups - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-tags"></span>
                                <h5>@lang('Selling Price Group')</h5>
                            </div>
                        </div>

                        {{-- <div class="sec-name">
                            <div class="col-md-6 col-sm-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <p class="mb-3"><b>@lang('Import/Export Selling Price Group Prices')</b> </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-4"><b>@lang('Import File') :</b> </label>
                                            <div class="col-8">
                                                <input type="file" name="import_file" class="form-control">
                                                <span class="error" style="color: red;">
                                                    {{ $errors->first('import_file') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-4"></label>
                                            <div class="col-8">
                                                <button class="btn btn-sm btn-primary float-end mt-1">@lang('Upload Import File')</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <div class="col-12">
                                                <a href="{{ route('products.export.price.group.products') }}" class="btn btn-sm btn-success">@lang('Export Selling Price Group Prices')</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12 d-none d-md-block">
                                <div class="col-md-12">
                                    <div class="heading"><h4>@lang('Instructions')</h4></div>
                                    <div class="top_note">
                                        <p class="p-0 m-0">
                                            <b>â€¢</b> Export Selling price group prices.
                                        </p>
                                        <p class="p-0 m-0">
                                            <b>â€¢</b> Update the exported file and import the same file.
                                        </p>
                                        <p class="p-0 m-0">
                                            <b>â€¢</b> Only selling price group prices of the product will be updated. Any blank price will be skipped.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>

                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="section-header">
                                    <div class="col-md-10">
                                        <h6>@lang('All Selling Price Group')</h6>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="btn_30_blue float-end">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus-square"></i> @lang('Add')</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="widget_content">
                                    <div class="table-responsive" id="data-list">
                                        <table class="display data_tbl data__table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>@lang('S/L')</th>
                                                    <th>@lang('Name')</th>
                                                    <th>@lang('Description')</th>
                                                    <th>@lang('Status')</th>
                                                    <th>@lang('Actions')</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
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
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Add Price Group')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_price_group_form" action="{{ route('product.selling.price.groups.store') }}" method="POST">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label><b>@lang('Name') :</b> <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="name"
                                    placeholder="@lang('Name')" />
                                <span class="error error_name"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><b>@lang('Description') :</b></label>
                                <textarea name="description" class="form-control" cols="10" rows="3" placeholder="@lang('Price Group Description')"></textarea>
                                <span class="error error_photo"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                <button type="submit" class="c-btn button-success me-0 float-end submit_button">@lang('Save')</button>
                                <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
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
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Edit Category')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_modal_body">
                    <!--begin::Form-->

                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    var table = $('.data_tbl').DataTable({
      dom: "lBfrtip",
      buttons: [
          {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
          {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
          {extend: 'print',text: '<i class="fas fa-print"></i> @lang("Print")',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
      ],
      language: {
                search: "@lang('Search')",
                emptyTable: "@lang('EmptyTable')",
                infoEmpty: "@lang('EmptyTable')",
                sInfo: "@lang('Showing _START_ to _END_ of _TOTAL_ entries')",
                sInfoEmpty: "@lang('Showing 0 to 0 of 0 entries')",
                sLengthMenu: "@lang('Show _MENU_ entries')",
                paginate: {
                    next: "@lang('Next')",
                    previous: "@lang('Previous')"

                },
            },
      "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
      "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
      processing: true,
      serverSide: true,
      aaSorting: [[0, 'desc']],
      ajax: "{{ route('product.selling.price.groups.index') }}",
      columns: [
          {data: 'DT_RowIndex',name: 'DT_RowIndex'},
          {data: 'name', name: 'name'},
          {data: 'description', name: 'description'},
          {data: 'status', name: 'status'},
          {data: 'action', name: 'action'},
      ]
  });

  // Setup ajax for csrf token.
  $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
  // Add Price Group by ajax
  $(document).on('submit', '#add_price_group_form', function(e) {
      e.preventDefault();
      $('.loading_button').show();
      var url = $(this).attr('action');
      $('.submit_button').prop('type', 'button');
      var request = $(this).serialize();
      $.ajax({
          url: url,
          type: 'post',
          data: request,
          success: function(data) {
              toastr.success(data);
              $('#add_price_group_form')[0].reset();
              $('.loading_button').hide();
              table.ajax.reload();
              $('#addModal').modal('hide');
              $('.submit_button').prop('type', 'submit');
          },error: function(err) {
              $('.submit_button').prop('type', 'submit');
              $('.loading_button').hide();
              $('.error').html('');
              if (err.status == 0) {
                  toastr.error('Net Connetion Error. Reload This Page.');
                  return;
              }
              $.each(err.responseJSON.errors, function(key, error) {
                  $('.error_' + key + '').html(error[0]);
              });
          }
      });
  });

  // pass editable data to edit modal fields
  $(document).on('click', '#edit', function(e) {
      e.preventDefault();
      var url = $(this).attr('href');
      $.ajax({
          url: url,
          type: 'get',
          success: function(data) {
              $('#edit_modal_body').html(data);
              $('#editModal').modal('show');
          }
      });
  });

  // edit category by ajax
  $(document).on('submit', '#edit_price_group_form', function(e) {
      e.preventDefault();
      $('.loading_button').show();
      var url = $(this).attr('action');
      var request = $(this).serialize();
      $.ajax({
          url: url,
          type: 'post',
          data: request,
          success: function(data) {
              toastr.success(data);
              $('.loading_button').hide();
              table.ajax.reload();
              $('#editModal').modal('hide');
          },
          error: function(err) {
              $('.loading_button').hide();
              $('.error').html('');
              if (err.status == 0) {
                  toastr.error('Net Connetion Error. Reload This Page.');
                  return;
              }
              $.each(err.responseJSON.errors, function(key, error) {
                  $('.error_e_' + key + '').html(error[0]);
              });
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
              toastr.error(data);
              $('.data_tbl').DataTable().ajax.reload();
              $('#deleted_form')[0].reset();
          },error: function(err) {
              if (err.status == 0) {
                  toastr.error('Net Connetion Error. Reload This Page.');
              }else{
                  toastr.error('Server Error. Please contact to the support team.');
              }
          }
      });
  });


   $(document).on('click', '#change_status', function(e) {
      e.preventDefault();
      var url = $(this).attr('href');
      $.ajax({
          url: url,
          type: 'get',
          success: function(data) {
              toastr.success(data);
              table.ajax.reload();
          }
      });
  });
</script>


@endpush
