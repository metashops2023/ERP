<table id="kt_datatable" class="display data_tbl data__table">
    <thead>
        <tr>
            <th>@lang('Serial')</th>
            <th>@lang('Name')</th>
            <th>@lang('Duration')</th>
            <th>@lang('Type')</th>
            <th>@lang('Description')</th>
            <th>@lang('Action')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($warranties as $warranty)
            <tr data-info="{{ $warranty }}">
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $warranty->name }}</td>
                <td>{{ $warranty->duration.' '.$warranty->duration_type }}</td>
                <td>{{ $warranty->type == 1 ? 'Warranty' : 'Guaranty' }}</td>
                <td>{{ $warranty->description }}</td>
                <td>
                    <div class="dropdown table-dropdown">
                        <a href="javascript:;" class="action-btn c-edit" id="edit" title="@lang('Edit')">
                         <i class="fa fa-edit"></i>
                        </a>
                        <a href="{{ route('product.warranties.delete', $warranty->id) }}" class="action-btn c-delete" id="delete" title="@lang('Delete')">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            //{extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
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
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
    });
</script>
