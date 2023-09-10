<form id="edit_holiday_form" action="{{ route('hrm.holidays.update') }}">
    <input type="hidden" name="id" id="id" value="{{ $holiday->id }}">
    <div class="form-group ">
        <label><b>@lang('Holiday Name') :</b> <span class="text-danger">*</span></label>
        <input type="text" name="holiday_name" required class="form-control" placeholder="@lang('Holiday Name')" value="{{ $holiday->holiday_name }}">
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><b>@lang('Start Date') :</b> <span class="text-danger">*</span></label>
            <input type="date" name="start_date" required class="form-control" value="{{ $holiday->start_date }}">
        </div>

        <div class="col-md-6">
            <label><b>@lang('End Date') :</b> <span class="text-danger">*</span></label>
            <input type="date" name="end_date" required class="form-control" value="{{ $holiday->end_date }}">
        </div>
    </div>

    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
        <div class="form-group mt-1">
            <label><b>@lang('Allowed Branch')</b> <span class="text-danger">*</span></label>
            <select class="form-control" name="branch_id">
                <option {{ $holiday->is_all == 1 ? 'SELECTED' : '' }} value="All"> @lang('All') </option>
                <option {{ !$holiday->branch_id ? 'SELECTED' : '' }} value=""> {{json_decode($generalSettings->business, true)['shop_name']}}  (<b>@lang('Head Office')</b>) </option>
                @foreach($branches as $row)
                    <option {{ $row->id == $holiday->branch_id ? 'SELECTED' : '' }} value="{{ $row->id }}"> {{ $row->name.'/'.$row->branch_code }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <div class="form-group mt-1">
        <label><b>@lang('Note') :</b> </label>
        <textarea name="notes" class="form-control" cols="10" rows="3" placeholder="@lang('Note')">{{ $holiday->notes }}</textarea>
    </div>

    <div class="form-group mt-3">
        <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
        <button type="submit" class="c-btn me-0 button-success float-end">@lang('Save Change')</button>
        <button type="reset" data-bs-dismiss="modal"
            class="c-btn btn_orange float-end">@lang('Close')</button>
    </div>
</form>
