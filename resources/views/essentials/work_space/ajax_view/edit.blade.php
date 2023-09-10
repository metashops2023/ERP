<form id="edit_work_space_form" action="{{ route('workspace.update', $ws->id) }}" method="post">
    @csrf
    <div class="form-group row">
        <div class="col-md-6">
            <label><b>@lang('Name') :</b></label>
            <input required type="text" name="name" class="form-control" placeholder="@lang('Workspace Name')" value="{{ $ws->name }}">
        </div>

        <div class="col-md-6">
            <label><b>@lang('Assigned To') :</b></label>
            <select required name="user_ids[]" class="form-control select2" id="user_ids" multiple="multiple">
                <option disabled value=""> @lang('Select Please') </option>
                @foreach ($users as $user)
                    <option
                        @foreach ($ws->ws_users as $ws_user)
                            {{ $ws_user->user_id == $user->id ? "SELECTED" : '' }}
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
            <select required name="priority" class="form-control" id="priority">
                <option value="">@lang('Select Priority')</option>
                <option {{ $ws->priority == 'Low' ? 'SELECTED' : '' }} value="Low">@lang('Low')</option>
                <option {{ $ws->priority == 'Medium' ? 'SELECTED' : '' }} value="Medium">@lang('Medium')</option>
                <option {{ $ws->priority == 'High' ? 'SELECTED' : '' }} value="High">@lang('High')</option>
                <option {{ $ws->priority == 'Urgent' ? 'SELECTED' : '' }} value="Urgent">@lang('Urgent')</option>
            </select>
        </div>

        <div class="col-md-6">
            <label><strong>@lang('Status') : </strong></label>
            <select required name="status" class="form-control" id="status">
                <option value="">@lang('Select Status')</option>
                <option {{ $ws->status == 'New' ? 'SELECTED' : '' }} value="New">@lang('New')</option>
                <option {{ $ws->status == 'In-Progress' ? 'SELECTED' : '' }} value="In-Progress">@lang('In-Progress')</option>
                <option {{ $ws->status == 'On-Hold' ? 'SELECTED' : '' }} value="On-Hold">@lang('On-Hold')</option>
                <option {{ $ws->status == 'Complated' ? 'SELECTED' : '' }} value="Complated">@lang('Completed')</option>
            </select>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><b>@lang('Start Date') : </b></label>
            <input required type="text" name="start_date" class="form-control datepicker" id="start_date" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ws->start_date)) }}">
        </div>

        <div class="col-md-6">
            <label><b>@lang('End Date') : </b></label>
            <input required type="text" name="end_date" class="form-control datepicker" id="end_date" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ws->end_date)) }}">
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><b>@lang('Description') : </b></label>
            <textarea name="description" class="form-control" id="description" cols="10" rows="3" placeholder="@lang('Workspace Description.')">{{ $ws->description }}</textarea>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><b>@lang('Documents') : </b></label>
            <input type="file" name="documents[]" class="form-control" multiple id="documents" placeholder="@lang('Workspace Description.')">
        </div>

        <div class="col-md-6">
            <label><b>@lang('Estimated Hours') : </b></label>
            <input type="text" name="estimated_hours" class="form-control" id="estimated_hours" placeholder="@lang('Estimated Hours')" value="{{ $ws->estimated_hours }}">
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn me-0 button-success float-end">@lang('Save Changes')</button>
            <button type="reset" data-bs-dismiss="modal"
                class="c-btn btn_orange float-end">@lang('Close')</button>
        </div>
    </div>
</form>
<script>
    $('.select2').select2();
    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'dd');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'mm');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'yyyy');
    $('.datepicker').datepicker({format: _expectedDateFormat});
</script>
