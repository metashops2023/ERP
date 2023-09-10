<script>
    var loans_table = $('.data_tbl2').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
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
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
        processing: true,
        serverSide: true,
        searchable: true,
        // aaSorting: [[1, 'desc']],
        "ajax": {
            "url": "{{ route('accounting.loan.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.company_id = $('#f_company_id').val();
                d.from_date = $('.from_date').val();
                d.to_date = $('.to_date').val();
            }
        },
        columns: [
            {data: 'action', name: 'action'},
            {data: 'report_date',name: 'report_date'},
            {data: 'branch',name: 'branches.name'},
            {data: 'reference_no', name: 'reference_no'},
            {data: 'c_name', name: 'loan_companies.name'},
            {data: 'type', name: 'type'},
            {data: 'loan_by', name: 'loan_by'},
            {data: 'loan_amount', name: 'loan_amount'},
            {data: 'due', name: 'due'},
            {data: 'total_paid', name: 'total_paid'},
        ],
    });

    // Add loan by ajax
    $(document).on('submit', '#adding_loan_form', function(e) {
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
                $('#adding_loan_form')[0].reset();
                $('.loading_button').hide();
                $('.submit_button').prop('type', 'submit');
                loans_table.ajax.reload();
                companies_table.ajax.reload();
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
    $(document).on('click', '#edit_loan', function(e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.get(url, function (data) {
            if (!$.isEmptyObject(data.errorMsg)) {
                toastr.error(data.errorMsg);
            }else{
                $('#edit_loan_form_body').html(data);
                $('#add_loan_form').hide();
                $('#edit_loan_form').show();
                document.getElementById('e_company_id').focus();
            }
            $('.data_preloader').hide();
        });
    });

     // Edit company by ajax
    $(document).on('submit', '#editting_loan_form', function(e) {
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
                loans_table.ajax.reload();
                $('.loading_button').hide();
                $('#add_loan_form').show();
                $('#edit_loan_form').hide();
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

     // Show details modal with data
     $(document).on('click', '#view', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.get(url, function(data) {
            $('#loan_details').html(data);
            $('.data_preloader').hide();
            $('#detailsModal').modal('show');
        });
    });

    $(document).on('click', '#delete_loan',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        $('#delete_loan_form').attr('action', url);
        $.confirm({
            'title': "@lang('Delete Confirmation')",
            'content': "@lang('Are you sure, you want to delete?')",
            'buttons': {
                @lang("YES"): {'class': 'yes btn-modal-primary','action': function() {$('#delete_loan_form').submit();}},
                @lang("NO"): {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
            }
        });
    });

    //data delete by ajax
    $(document).on('submit', '#delete_loan_form', function(e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                if (!$.isEmptyObject(data.errorMsg)) {
                    toastr.error(data.errorMsg);
                }else{
                    toastr.error(data);
                    companies_table.ajax.reload();
                    loans_table.ajax.reload();
                    $('#delete_loan_form')[0].reset();
                }
            }
        });
    });

    function allCompanies() {
        var url = "{{ route('accounting.loan.all.companies.for.form') }}";
        $.ajax({
            url: url,
            type: 'get',
            success: function(companies) {
                $('#company_id').empty();
                $('#f_company_id').empty();
                $('#company_id').append('<option value="">@lang('Select Company')</option>');
                $('#f_company_id').append('<option value="">@lang('All')</option>');
                $.each(companies, function (key, com) {
                    $('#company_id').append('<option value="'+com.id +'">'+com.name+'</option>');
                    $('#f_company_id').append('<option value="'+com.id +'">'+com.name+'</option>');
                })
            }
        });
    }
    allCompanies();

    $(document).on('click', '#close_loan_edit_form', function() {
        $('#add_loan_form').show();
        $('#edit_loan_form').hide();
    });

    //Submit filter form by select input changing
    $(document).on('submit', '#filter_form', function (e) {
        e.preventDefault();
        loans_table.ajax.reload();
    });

    //Print Profit/Loss
    $(document).on('click', '#print_report', function (e) {
        e.preventDefault();
        var url = "{{ route('accounting.loan.print') }}";
        var branch_id = $('#branch_id').val();
        var company_id = $('#f_company_id').val();
        var date_range = $('#date_range').val();
        $.ajax({
            url:url,
            type:'get',
            data: { branch_id, company_id, date_range },
            success:function(data){
                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
                    removeInline: false,
                    printDelay: 700,
                    header: null,
                    footer: null,
                });
            }
        });
    });

    // Print single payment details
    $('#print_loan_details').on('click', function (e) {
        e.preventDefault();
        var body = $('.loan_details_print_area').html();
        var footer = $('.signature_area').html();
        $(body).printThis({
            debug: false,
            importCSS: true,
            importStyle: true,
            loadCSS: "{{asset('assets/css/print/purchase.print.css')}}",
            removeInline: true,
            printDelay: 500,
            header: '',
            footer: footer
        });
    });
</script>

<script type="text/javascript">
     var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');

    new Litepicker({
        singleMode: true,
        element: document.getElementById('date'),
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
        format: _expectedDateFormat
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('datepicker'),
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
        format: 'DD-MM-YYYY'
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('datepicker2'),
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
        format: 'DD-MM-YYYY',
    });

</script>
