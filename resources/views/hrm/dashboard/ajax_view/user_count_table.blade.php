<div class="section-header d-flex justify-content-between align-items-center px-3">
    <h6>
        <span class="fas fa-users"></span>
        @lang('Users')
    </h6>
    <span class="badge bg-secondary text-white">
        <div id="small-badge">
            Total: {{ $userCount }}
        </div>
    </span>
</div>
<div class="widget_content">
    <div class="mtr-table">
        <div class="table-responsive">
            <table id="user_table" class="display data__table data_tble stock_table compact" width="100%">
                <thead>
                    <tr>
                        <th>@lang('Department')</th>
                        <th>@lang('Total')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->department_name }}</td>
                            <td>{{ $user->total_users }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $('#user_table').DataTable({
        dom: "Bfrtip",
        buttons: [
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: '<i class="fas fa-print"></i> @lang("Print")',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        pageLength: 10,
    });
</script>