<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">@lang('Serial')</th>
            <th class="text-start">@lang('Tax Name')</th>
            <th class="text-start">@lang('Tax Percent')</th>
            <th class="text-start">@lang('Actions')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($taxes as $tax)
            <tr data-info="{{ $tax }}">
                <td class="text-start">{{ $loop->index + 1 }}</td>
                <td class="text-start">{{ $tax->tax_name }}</td>
                <td class="text-start">{{ $tax->tax_percent }}</td>

                <td nowrap="nowrap" class="text-start">
                    <a href="javascript:;" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span>
                    </a>
                    <a href="{{ route('settings.taxes.delete', $tax->id) }}" id="delete" class="action-btn c-delete"><span class="fas fa-trash "></span>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable({"lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]]});
</script>
