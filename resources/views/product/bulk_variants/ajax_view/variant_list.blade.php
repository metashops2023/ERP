<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">SL</th>
            <th class="text-start">@lang('Variant')</th>
            <th class="text-start">@lang('Childs')</th>
            <th class="text-start">@lang('Action')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($variants as $variant)
            <tr data-info="{{ $variant }}">
                <td class="text-start">{{ $loop->index + 1 }}</td>
                <td class="text-start">{{ $variant->bulk_variant_name }}</td>
                <td class="text-start">
                    @foreach ($variant->bulk_variant_child as $variant_child)
                        {{ $variant_child->child_name.' ,' }}
                    @endforeach
                </td>
                <td class="text-start">
                    <div class="dropdown table-dropdown">
                        <a href="javascript:;" class="action-btn c-edit" id="edit" title="@lang('Edit')" data-bs-toggle="modal" data-bs-target="#editModal">
                            <span class="fas fa-edit"></span>
                        </a>
                        <a href="{{ route('product.variants.delete', $variant->id) }}" class="action-btn c-delete" id="delete" title="@lang('Delete')">
                            <span class="fas fa-trash "></span>
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
           // {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
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
