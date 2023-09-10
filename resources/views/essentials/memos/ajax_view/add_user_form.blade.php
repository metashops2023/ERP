<form id="add_user_form" action="{{ route('memos.add.users', $memo) }}" method="post">
    @csrf
    <div class="form-group row">
        <div class="col-md-12">
            <label><b>@lang('Users') :</b></label>
            <select required name="user_ids[]" class="form-control select2" id="user_ids" multiple="multiple">
                <option disabled value=""> @lang('Select Please') </option>
                @foreach ($users as $user)
                    @if ($user->id != auth()->user()->id)
                        <option @foreach ($memo->memo_users as $mamo_user)
                            {{ $user->id == $mamo_user->user_id ? "SELECTED" : '' }}
                        @endforeach
                        value="{{ $user->id }}">{{ $user->prefix.' '.$user->name.' '.$user->last_name }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn me-0 button-success float-end">@lang('Update')</button>
            <button type="reset" data-bs-dismiss="modal"
                class="c-btn btn_orange float-end">@lang('Close')</button>
        </div>
    </div>
</form>

<script>
    $('.select2').select2();
</script>