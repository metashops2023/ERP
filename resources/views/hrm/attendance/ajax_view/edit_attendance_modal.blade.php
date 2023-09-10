<form id="edit_attendance_form" action="{{ route('hrm.attendance.update') }}" method="POST"> 
    @csrf
    <input type="hidden" name="id" value="{{ $attendance->id }}">
    <label class="text-navy-blue"><b>@lang('Employee') :</b> {{ $attendance->prefix . ' ' . $attendance->name . ' ' . $attendance->last_name }} </label><br>
    <label class="text-navy-blue"><b>@lang('Start Date') :</b> {{ $attendance->at_date }}  </label>
    <div class="form-group row">
        <div class="col-md-6">
            <label><b>@lang('Clock In') :</b></label>
            <input required type="time" name="clock_in" class="form-control" value="{{ $attendance->clock_in }}">
        </div>

        <div class="col-md-6">
            <label><b>@lang('Clock Out') :</b></label>
            <input type="time" name="clock_out" class="form-control" value="{{ $attendance->clock_out }}">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
            <label><b>@lang('Clock In Note') :</b></label>
            <textarea name="clock_in_note" cols="10" rows="3" class="form-control" placeholder="@lang('Clock in note')">{{ $attendance->clock_in_note }}</textarea>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
            <label><b>@lang('Clock Out Note') :</b></label>
            <textarea name="clock_out_note" cols="10" rows="3" class="form-control" placeholder="@lang('Clock out note')">{{ $attendance->clock_out_note }}</textarea>
        </div>
    </div>
    
    <div class="form-group row mt-3">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i
                    class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn me-0 button-success float-end">@lang('Save')</button>
            <button type="reset" data-bs-dismiss="modal"
                class="c-btn btn_orange float-end">@lang('Close')</button>
        </div>
    </div>
</form>