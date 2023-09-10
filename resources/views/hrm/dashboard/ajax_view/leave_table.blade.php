@if (count($leaves) > 0)
    @foreach ($leaves as $leave)
    <tr>
        <td>
            <a href="#">
                {{ $leave->prefix.' '.$leave->name.' '.$leave->last_name.' '.$leave->start_date.' - '.$leave->end_date }}
            </a>
        </td>

        <td>
            @if ($leave->status == 0)
                <span class="badge bg-warning">@lang('Pending')</span>
            @elseif($leave->status == 1)
                <span class="badge bg-success">@lang('Approved')</span>
            @else 
                <span class="badge bg-danger">@lang('Rejected')</span>
            @endif
        </td>
    </tr>
    @endforeach
@else
    <tr>
        <td colspan="2" class="text-center">@lang('No Data Found').</td>
    </tr>
@endif

