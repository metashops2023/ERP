<script>
    var subCatetable = $('.data_tbl2').DataTable({
       dom: "lBfrtip",
       buttons: [
           {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
           {extend: 'print',text: '<i class="fas fa-print"></i> @lang("Print")',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
       ],
       language: {
                search: "@lang('Search')",
                emptyTable: "@lang('EmptyTable')",
                infoEmpty: "@lang('EmptyTable')",
                sInfo : "@lang('Showing _START_ to _END_ of _TOTAL_ entries')",
                sInfoEmpty : "@lang('Showing 0 to 0 of 0 entries')",
                sLengthMenu : "@lang('Show _MENU_ entries')",
                paginate: {
                    next: "@lang('Next')",
                    previous: "@lang('Previous')"

                },
            },
            ajax: {
                url: "{{ route('product.subcategories.index') }}",
                data: function(d) {
                    d = Object.assign(d, {
                        active: $("#chkActiveSub").is(':checked'),
                    });
                },
            },
       processing: true,
       serverSide: true,
       searchable: true,
       "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
    //    ajax: "{{ route('product.subcategories.index') }}",
       columnDefs: [{"targets": [0, 1, 3, 4], "orderable": false, "searchable": false}],
       columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
           { data: 'photo',name: 'photo'},
           {data: 'name',name: 'name'},
           {data: 'parentname',name: 'parentname'},
           {data: 'description',name: 'description'},
           {data: 'status',name: 'status'},
           {data: 'action',name: 'action'},
       ]
   });

   $('#chkActiveSub').change(function() {
        subCatetable.draw();
        });

   $(document).ready(function () {
       // Add Subcategory by ajax
       $('#add_sub_category_form').on('submit', function(e) {
           e.preventDefault();

           $('.loading_button').show();
           var url = $(this).attr('action');
           $('.submit_button').prop('type', 'button');

           $.ajax({
               url: url,
               type: 'post',
               data: new FormData(this),
               contentType: false,
               cache: false,
               processData: false,
               success: function(data) {

                   toastr.success(data);
                   $('#add_sub_category_form')[0].reset();
                   $('.loading_button').hide();
                   subCatetable.ajax.reload();
                   $('.submit_button').prop('type', 'submit');
                   $('.error').html('');
               },error: function(err) {

                    $('.submit_button').prop('type', 'submit');
                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    }

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_sub_' + key + '').html(error[0]);
                    });
               }
           });
       });

       // pass editable data to edit modal fields
       $(document).on('click', '.edit_sub_cate', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            $('.data_preloader').show();
            $.get("sub-categories/edit/" + id, function(data) {
                $("#edit_sub_cate_form_body").html(data);
                $('#add_sub_cate_form').hide();
                $('#edit_sub_cate_form').show();
                $('.data_preloader').hide();
                document.getElementById('e_sub_name').focus();
            })
       });


       $(document).on('click', '#change_status_sub', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {

                        toastr.success(data);
                        subCatetable.ajax.reload();
                    }
                });
            });

        // edit category by ajax
        $(document).on('submit', '#edit_sub_category_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('.error').html('');
                    toastr.success(data);
                    $('.loading_button').hide();
                    $('#edit_sub_category_form')[0].reset();
                    subCatetable.ajax.reload();
                    $('#add_sub_cate_form').show();
                    $('#edit_sub_cate_form').hide();
                },error: function(err) {
                    $('.loading_button').hide();
                    $('.error').html('');
                    $.each(err.responseJSON.errors, function(key, error) {
                        $('.error_sub_e_' + key + '').html(error[0]);
                    });
                }
            });
        });

        $(document).on('click', '#delete_sub_cate',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_sub_cate_form').attr('action', url);
            $.confirm({
            'title': "@lang('Delete Confirmation')",
            'content': "@lang('Are you sure, you want to delete?')",
            'buttons': {
                @lang("YES"): {'class': 'yes btn-modal-primary','action': function() {$('#deleted_sub_cate_form').submit();}},
                @lang("NO"): {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
            }
        });
        });

       //data delete by ajax
       $(document).on('submit', '#deleted_sub_cate_form', function(e) {
           e.preventDefault();
           var url = $(this).attr('action');
           var request = $(this).serialize();
           $.ajax({
               url: url,
               type: 'post',
               async: false,
               data: request,
               success: function(data) {
                   subCatetable.ajax.reload();
                   toastr.error(data);
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

       $(document).on('click', '#close_sub_cate_form', function() {
           $('#add_sub_cate_form').show();
           $('#edit_sub_cate_form').hide();
       });
   });
</script>
