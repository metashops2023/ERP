<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">@lang('Reference No')</th>
            <th class="text-start">@lang('Type')</th>
            <th class="text-start">@lang('Employee')</th>
            <th class="text-start">@lang('Date')</th>
            <th class="text-start">@lang('Reason')</th>
            <th class="text-start">@lang('Status')</th>
            <th class="text-start">@lang('Actions')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($leave as $key => $row)
            <tr data-info="{{ $row }}">
                <td class="text-start">{{ $row->reference_number }}</td>
                <td class="text-start">{{ $row->leave_type->leave_type }}</td>
                <td class="text-start">{{ $row->admin_and_user->prefix.' '.$row->admin_and_user->name.' '.$row->admin_and_user->last_name }}</td>
                <td class="text-start">{{ $row->start_date }} to {{ $row->end_date }}</td>
                <td class="text-start">{{ $row->reason }}</td>
                <td class="text-start">
                	@if($row->status == 0)
                	   <span class="badge bg-warning">@lang('Pending')</span>
                	@else
                	  <span class="badge bg-success">@lang('Success')</span>
                	@endif
                </td>
                <td class="text-start">
                    <div class="dropdown table-dropdown">
                        <a href="javascript:;" id="edit" title="@lang('Edit')" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span></a>
                        <a href="{{ route('hrm.leave.delete', $row->id) }}" class="action-btn c-delete" id="delete" title="@lang('Delete')"><span class="fas fa-trash "></span></a>
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
