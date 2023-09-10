<form id="edit_todo_form" action="{{ route('todo.update', $todo->id) }}" method="POST">
    @csrf
    <div class="form-group">
        <div class="col-md-12">
            <label><b>@lang('Task') :</b></label>
            <input required type="text" name="task" class="form-control" placeholder="@lang('Task')" value="{{ $todo->task }}">
        </div>
    </div>

    <div class="form-group mt-1">
        <div class="col-md-12">
            <label><b>@lang('Assigned To') :</b></label>
            <select required name="user_ids[]" class="form-control select2" multiple="multiple">
                <option disabled value=""> @lang('Select Please') </option>
                @foreach ($users as $user)
                <option
                    @foreach ($todo->todo_users as $todo_user)
                        {{ $todo_user->user_id == $user->id ? "SELECTED" : '' }}
                    @endforeach
                 value="{{ $user->id }}">{{ $user->prefix.' '.$user->name.' '.$user->last_name }}
                </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><b>@lang('Priority') : </b></label>
            <select required name="priority" class="form-control">
                <option value="">@lang('Select Priority')</option>
                <option {{ $todo->priority == 'Low' ? 'SELECTED' : ''  }} value="Low">@lang('Low')</option>
                <option {{ $todo->priority == 'Medium' ? 'SELECTED' : ''  }} value="Medium">@lang('Medium')</option>
                <option {{ $todo->priority == 'High' ? 'SELECTED' : ''  }} value="High">@lang('High')</option>
                <option {{ $todo->priority == 'Urgent' ? 'SELECTED' : ''  }} value="Urgent">@lang('Urgent')</option>
            </select>
        </div>

        <div class="col-md-6">
            <label><strong>@lang('Status') : </strong></label>
            <select required name="status" class="form-control">
                <option value="">@lang('Select Status')</option>
                <option {{ $todo->status == 'New' ? 'SELECTED' : ''  }} value="New">@lang('New')</option>
                <option {{ $todo->status == 'In-Progress' ? 'SELECTED' : ''  }} value="In-Progress">@lang('In-Progress')</option>
                <option {{ $todo->status == 'On-Hold' ? 'SELECTED' : ''  }} value="On-Hold">@lang('On-Hold')</option>
                <option {{ $todo->status == 'Complated' ? 'SELECTED' : ''  }} value="Complated">@lang('Completed')</option>
            </select>
        </div>
    </div>


    <div class="form-group mt-1">
        <div class="col-md-12">
            <label><b>@lang('Due Date') : </b></label>
            <input required type="text" name="due_date" class="form-control datepicker" id="due_date" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($todo->due_date)) }}">
        </div>
    </div>

    <div class="form-group mt-1">
        <div class="col-md-12">
            <label><b>@lang('Description') : </b></label>
            <textarea name="description" class="form-control" id="description" cols="10" rows="3" placeholder="@lang('Workspace Description.')">{{ $todo->description }}</textarea>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn me-0 button-success float-end">@lang('Save Changes')</button>
            <button type="button" class="c-btn btn_orange float-end" id="close_form">@lang('Close')</button>
        </div>
    </div>
</form>
<script>
    $('.select2').select2();
    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
    new Litepicker({
        singleMode: true,
        element: document.getElementById('e_due_date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: _expectedDateFormat,
    });
</script>
