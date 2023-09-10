<table id="att_table" class="display data__table data_tble stock_table compact" width="100%">
    <thead>
        <tr>
            <th>@lang('Employee')</th>
            <th>@lang('Clock-in Time')</th>
            <th>@lang('Clock-out Time')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($todayAttendances as $att)
            <tr>
                <td>{{ $att->prefix.' '.$att->name.' '.$att->last_name }}</td>
                <td>{{ date('h:i a', strtotime($att->clock_in)) }}</td>
                <td>{{ $att->clock_out ? date('h:i a', strtotime($att->clock_out)) : ''}}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('#att_table').DataTable({
        dom: "Bfrtip",
        buttons: [
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: '<i class="fas fa-print"></i> @lang("Print")',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        pageLength: 10,
    });
</script>