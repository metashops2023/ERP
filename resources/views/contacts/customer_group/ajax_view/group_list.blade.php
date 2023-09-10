<table class="display data_tbl data__table table-striped">
    <thead>
        <tr>
            <th>@lang('Serial')</th>
            <th>@lang('Name')</th>
            <th>@lang('Calculation Percent')</th>
            <th>@lang('Status')</th>
            <th>@lang('Action')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($groups as $group)
            <tr data-info="{{ $group }}">
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $group->group_name }}</td>
                <td>{{ $group->calc_percentage }}%</td>
                <td>{{ $group->stauts }}</td>
                <td>
                    <div class="dropdown table-dropdown">
                        <a href="javascript:;" class="action-btn c-edit" id="edit" data-bs-toggle="modal" data-bs-target="#editModal"><span class="fas fa-edit"></span></a>
                        <a href="{{ route('customers.groups.delete', $group->id) }}" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable({"lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]]});
</script>
