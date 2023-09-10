<table class="display data_tbl data__table">
    <thead>
        <tr class="text-start">
            <th class="text-start">@lang('S/L')</th>
            <th class="text-start">@lang('Shift Name')</th>
            <th class="text-start">@lang('Start Time')</th>
            <th class="text-start">@lang('End Time')</th>
            <th class="text-start">@lang('Actions')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($shift as $row)
            <tr data-info="{{ $row }}" class="text-center">
                <td class="text-start">{{ $loop->index + 1 }}</td>
                <td class="text-start">{{ $row->shift_name }}</td>
                <td class="text-start">{{ $row->start_time }}</td>
                <td class="text-start">{{ $row->endtime }}</td>
                <td class="text-start">
                    <div class="dropdown table-dropdown">
                        <a href="javascript:;" id="edit" title="@lang('Edit')" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span></a>
                        <a href=""" class="action-btn c-delete" id="delete" title="@lang('Delete')"><span class="fas fa-trash "></span></a>
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
            aaSorting: [[0, 'desc']],
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
        }
    );
</script>
