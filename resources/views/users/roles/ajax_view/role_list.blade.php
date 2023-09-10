<table class="display data_tbl data__table table-striped">
    <thead>
        <tr>
            <th class="text-start">@lang('Serial')</th>
            <th class="text-start">@lang('Name')</th>
            <th class="text-start">@lang('Action')</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-start">#</td>
            <td class="text-start">@lang('Admin')</td>
            <td></td>
        </tr>
        @foreach ($roles as $role)
        <tr>
            <td class="text-start">{{ $loop->index + 1 }}</td>
            <td class="text-start">{{ $role->name }}</td>
            <td class="text-start">
                <div class="dropdown table-dropdown">
                    @if (auth()->user()->permission->user['role_edit'] == '1')
                    <a href="{{ route('users.role.edit', $role->id) }}" id="edit" title="@lang('Edit')" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span> </a>
                    @endif

                    @if (auth()->user()->permission->user['role_delete'] == '1' && ( $role->name != 'Vendor' )  )
                    <a href="{{ route('users.role.delete', $role->id) }}" class="action-btn c-delete" id="delete" title="@lang('Delete')"><span class="fas fa-trash "></span>
                    </a>
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
