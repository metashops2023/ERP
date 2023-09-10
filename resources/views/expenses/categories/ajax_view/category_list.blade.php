<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">@lang('Serial')</th>
            <th class="text-start">@lang('Name')</th>
            <th class="text-start">@lang('Code')</th>
            <th class="text-start">@lang('Action')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $category)
            <tr data-info="{{ $category }}">
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->code}}</td>

                <td>
                    <div class="dropdown table-dropdown">
                        <a href="javascript:;" class="action-btn c-edit" id="edit" title="@lang('Edit')"><span class="fas fa-edit"></span></a>
                        <a href="{{ route('expenses.categories.delete', $category->id) }}" class="action-btn c-delete" id="delete" title="@lang('Delete')" ><span class="fas fa-trash "></span></a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable({
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
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]]});
</script>
