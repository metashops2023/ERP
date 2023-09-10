<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th>@lang('Title')</th>
            <th>@lang('Type')</th>
            <th>@lang('Amount')</th>
            <th>@lang('Actions')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($allowance as $row)
            <tr data-info="{{ $row }}">
                <td>{{ $row->description }}</td>
                <td>
                	@if($row->type=="Allowance")
                	    <span class="badge bg-success"> {{ $row->type }} </span>
                	@else
                	    <span class="badge bg-danger"> {{ $row->type }} </span>
                	@endif
                </td>
                <td>{{ $row->amount }} {{ $row->amount_type == 1 ? json_decode($generalSettings->business, true)['currency'] : '%' }}</td>

                <td>
                    <div class="dropdown table-dropdown">
                        <a href="{{ route('hrm.allowance.edit', $row->id) }}" id="edit" title="@lang('Edit')" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span></a>
                        <a href="{{ route('hrm.allowance.delete', $row->id) }}" class="action-btn c-delete" id="delete" title="@lang('Delete')"><span class="fas fa-trash "></span></a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable({"lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]]});
</script>
