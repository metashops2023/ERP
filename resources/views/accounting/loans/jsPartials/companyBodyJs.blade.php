<script>
    $('.loans').hide();
    var companies_table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [{
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> Pdf',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> @lang("Print")',
                className: 'btn btn-primary',
                autoPrint: true,
                exportOptions: {
                    columns: ':visible'
                }
            }
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
        "lengthMenu": [
            [50, 100, 500, 1000, -1],
            [50, 100, 500, 1000, "All"]
        ],
        processing: true,
        serverSide: true,
        searchable: true,
        ajax: "{{ route('accounting.loan.companies.index') }}",
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'pay_loan_amount',
                name: 'pay_loan_amount'
            },
            {
                data: 'total_receive',
                name: 'total_receive'
            },
            {
                data: 'get_loan_amount',
                name: 'get_loan_amount'
            },
            {
                data: 'total_pay',
                name: 'total_pay'
            },
            {
                data: 'action',
                name: 'action'
            },
        ],
    });

    // Add company by ajax
    $(document).on('submit', '#add_company_form', function(e) {
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
                toastr.success(data);
                $('#add_company_form')[0].reset();
                $('.loading_button').hide();
                $('.submit_button').prop('type', 'submit');
                companies_table.ajax.reload();
                allCompanies();
            },
            error: function(err) {
                $('.loading_button').hide();
                $('.error').html('');
                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_' + key + '').html(error[0]);
                });
                $('.submit_button').prop('type', 'submit');
            }
        });
    });

    // pass editable data to edit modal fields
    $(document).on('click', '#edit_company', function(e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.get(url, function(data) {
            $('#edit_com_form_body').html(data);
            $('#add_com_form').hide();
            $('#edit_com_form').show();
            $('.data_preloader').hide();
            document.getElementById('e_name').focus();
        });
    });

    // Edit company by ajax
    $(document).on('submit', '#edit_company_form', function(e) {
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
                companies_table.ajax.reload();
                allCompanies();
                $('.loading_button').hide();
                $('#add_com_form').show();
                $('#edit_com_form').hide();
                $('.error').html('');
            },
            error: function(err) {
                $('.loading_button').hide();
                $('.error').html('');
                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_e_' + key + '').html(error[0]);
                });
            }
        });
    });

    $(document).on('click', '#delete_company', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#delete_companies_form').attr('action', url);
        $.confirm({
            'title': "@lang('Delete Confirmation')",
            'content': "@lang('Are you sure, you want to delete?')",
            'buttons': {
                @lang("YES"): {'class': 'yes btn-modal-primary','action': function() {$('#delete_companies_form').submit();}},
                @lang("NO"): {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
            }
        });
    });

    //data delete by ajax
    $(document).on('submit', '#delete_companies_form', function(e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                toastr.error(data);
                companies_table.ajax.reload();
                loans_table.ajax.reload();
                allCompanies();
                $('#delete_companies_form')[0].reset();
            }
        });
    });

    $(document).on('click', '#close_com_edit_form', function() {
        $('#add_com_form').show();
        $('#edit_com_form').hide();
    });

    $(document).on('click', '#tab_btn', function(e) {
        e.preventDefault();
        $('.tab_btn').removeClass('tab_active');
        $('.tab_contant').hide();
        var show_content = $(this).data('show');
        $('.' + show_content).show();
        $(this).addClass('tab_active');
    });

    $(document).on('click', '#loan_payment', function(e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.get(url, function(data) {
            $('#loanPymentModal').html(data);
            $('#loanPymentModal').modal('show');
            $('.data_preloader').hide();
        });
    });

    $(document).on('click', '#view_payments', function(e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.get(url, function(data) {
            $('#payment_list').html(data);
            $('#viewPaymentModal').modal('show');
            $('.data_preloader').hide();
        });
    });

    $(document).on('click', '#delete_payment', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#deleted_payment_form').attr('action', url);
        $.confirm({
            'title': "@lang('Delete Confirmation')",
            'content': "@lang('Are you sure, you want to delete?')",
            'buttons': {
                @lang("YES"): {'class': 'yes btn-modal-primary','action': function() {$('#deleted_payment_form').submit();}},
                @lang("NO"): {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
            }
        });
    });

    //data delete by ajax
    $(document).on('submit', '#deleted_payment_form', function(e) {
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
                $('#deleted_payment_form')[0].reset();
                $('#viewPaymentModal').modal('hide');
                companies_table.ajax.reload();
                loans_table.ajax.reload();
            }
        });
    });
</script>
