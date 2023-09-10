<table class="display data_tbl data__table">
    <thead>
        <tr class="text-center">
            <th class="text-start">@lang('S/L')</th>
            <th class="text-start">@lang('Name')</th>
            <th class="text-start">@lang('Description')</th>
            <th class="text-start">@lang('Action')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($designation as $key => $row)
            <tr data-info="{{ $row }}">
                <td class="text-start">{{ $key+1 }}</td>
                <td class="text-start">{{ $row->designation_name }}</td>
                <td class="text-start">{{ $row->description }}</td>
                <td class="text-start">
                    <div class="dropdown table-dropdown">
                        <a href="javascript:;" id="edit" title="@lang('Edit')" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span></a>
                        <a href="{{ route('hrm.designations.delete', $row->id) }}" class="action-btn c-delete" id="delete" title="@lang('Delete')"><span class="fas fa-trash "></span></a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable(
        {

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
                sInfo : "@lang('Showing _START_ to _END_ of _TOTAL_ entries')",
                sInfoEmpty : "@lang('Showing 0 to 0 of 0 entries')",
                sLengthMenu : "@lang('Show _MENU_ entries')",
                paginate: {
                    next: "@lang('Next')",
                    previous: "@lang('Previous')"

                },
            },
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
        }
    );
</script>
