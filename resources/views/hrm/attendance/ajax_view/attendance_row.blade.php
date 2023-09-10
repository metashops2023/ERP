@php use Carbon\Carbon;  @endphp
@if ($attendance)
    <tr data-user_id="{{ $attendance->user_id }}">
        <td>
            <p class="m-0 mt-2">{{ $attendance->prefix . ' ' . $attendance->name . ' ' . $attendance->last_name.' (ID:'.($employee->emp_id ? $employee->emp_id : 'N/A' ).')' }}</p>
            <input type="hidden" name="user_ids[{{ $attendance->user_id }}]" value="{{ $attendance->user_id }}" />
        </td>

        <td>
            <p class="m-0">{{ $attendance->clock_in_ts }}</p>
            @php
                $startTime = Carbon::parse($attendance->clock_in_ts);
                $totalDuration = $startTime->diffForHumans();
            @endphp
            <small class="m-0 text-muted">(Clock In - {{ $totalDuration }})</small>
            <input type="hidden" name="clock_ins[{{ $attendance->user_id }}]" value="{{ $attendance->clock_in }}" />
        </td>

        <td>
            <input type="time" name="clock_outs[{{ $attendance->user_id }}]" placeholder="Clock Out"
                class="form-control"/>
        </td>

        <td>
            <input type="text" name="clock_in_notes[{{ $attendance->user_id }}]" class="form-control" value="{{ $attendance->clock_in_note }}" placeholder="@lang('Clock in note')" />
        </td>

        <td>
            <input type="text" name="clock_out_notes[{{ $attendance->user_id }}]" class="form-control" placeholder="@lang('clock out note')" value="{{ $attendance->clock_out_note }}" />
        </td>

        <td>
            <a href="" class="btn_remove"><i class="fas fa-trash-alt text-danger mt-2"></i></a>
        </td>
    </tr>
@else
<tr data-user_id="{{ $employee->id }}">
    <td>
        <p class="m-0 mt-2">{{ $employee->prefix . ' ' . $employee->name . ' ' . $employee->last_name.' (ID:'.($employee->emp_id ? $employee->emp_id : 'N/A' ).')' }}</p>
        <input type="hidden" name="user_ids[{{ $employee->id }}]" value="{{ $employee->id }}" />
    </td>

    <td>
        <input required type="time" name="clock_ins[{{ $employee->id }}]"
        class="form-control"/>
    </td>

    <td>
        <input type="time" name="clock_outs[{{ $employee->id }}]" placeholder="Clock Out"
            class="form-control"/>
    </td>

    <td>
        <input type="text" name="clock_in_notes[{{ $employee->id }}]" class="form-control" placeholder="@lang('Clock in note')" />
    </td>

    <td>
        <input type="text" name="clock_out_notes[{{ $employee->id }}]" class="form-control" placeholder="@lang('clock out note')"/>
    </td>

    <td>
        <a href="" class="btn_remove"><i class="fas fa-trash-alt text-danger mt-2"></i></a>
    </td>
</tr>
@endif
