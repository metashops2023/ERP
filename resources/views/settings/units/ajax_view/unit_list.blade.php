<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">@lang('Serial')</th>
            <th class="text-start">@lang('Unit Name')</th>
            <th class="text-start">@lang('Code Name')</th>
            <th class="text-start">@lang('Status')</th>
            <th class="text-start">@lang('Actions')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($units as $unit)
        <tr data-info="{{ $unit }}">
            <td class="text-start">{{ $loop->index + 1 }}</td>
            <td class="text-start">{{ $unit->name }}</td>
            <td class="text-start">{{ $unit->code_name }}</td>
            @if ($unit->status == 1)
                <td class="text-start"><span class="text-success">Active</span></td>
            @else
                <td class="text-start"><span class="text-danger">Inactive</span></td>
            @endif
            {{-- <td class="text-start">{{ $unit->status }}</td> --}}
            <!-- <input type="hidden" name="add_banch_id" id="add_branch_id" value="{{$unit->branch_id}}"> -->
            <td nowrap="nowrap" class="text-start">
                <a href="javascript:;" id="edit" title="@lang('Edit')" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span>
                </a>
                {{-- <a href="{{ route('settings.units.delete', $unit->id) }}" id="delete" class="action-btn c-delete" title="@lang('Delete')"><span class="fas fa-trash "></span>
                </a> --}}
                @if ($unit->status == 1)
                    <a href="{{ route('settings.units.change.status', $unit->id) }}" class="action-btn c-edit" title="@lang('Cancel')" id="change_status" ><i class="fas fa-window-close text-danger"></i></a>
                 @else
                    <a href="{{ route('settings.units.change.status', $unit->id) }}" class="action-btn c-edit" title="@lang('Undo')" id="change_status" ><i class="fas fa-undo text-success"></i></a>

                @endif

            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<script>
    var table =$('.data_tbl').DataTable({
        "lengthMenu": [
            [50, 100, 500, 1000, -1],
            [50, 100, 500, 1000, "All"]
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

    });

</script>


