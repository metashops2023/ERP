<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">@lang('S/L')</th>
            <th class="text-start">@lang('Name')</th>
            <th class="text-start">@lang('Department ID')</th>
            <th class="text-start">@lang('description')</th>
            <th class="text-start">@lang('Action')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($department as $key => $row)
            <tr data-info="{{ $row }}" class="text-center">
                <td class="text-start">{{ $key+1 }}</td>
                <td class="text-start">{{ $row->department_name }}</td>
                <td class="text-start">{{ $row->department_id }}</td>
                <td class="text-start">{{ $row->description }}</td>
                <td class="text-start">
                    <div class="dropdown table-dropdown">
                        <a href="javascript:;" id="edit" title="@lang('Edit')" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span></a>
                        <a href="{{ route('hrm.department.delete', $row->id) }}" class="action-btn c-delete" id="delete" title="@lang('Delete')"><span class="fas fa-trash "></span></a>
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
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]]
        }
    );
</script>
