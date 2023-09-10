<form id="edit_category_form" action="{{ route('product.categories.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="id" value="{{ $category->id }}">
    <div class="form-group">
        <label><b>@lang('Name') :</b> <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control " id="e_name" placeholder="@lang('Category name')" value="{{ $category->name }}" />
        <span class="error error_e_name"></span>
    </div>

    <div class="form-group mt-1">
        <label><b>@lang('Description') :</b> </label>
        <textarea name="description" class="form-control" cols="30" rows="3" placeholder="@lang('Description')">{{ $category->description }}</textarea>
    </div>

    <div class="form-group mt-1">
        <b>@lang('Business Name') :</b> <span class="text-danger">*</span>
        <select name="add_branch_id" id="add_branch_id" class="form-control">
            @foreach ($branches as $branch)
            <option value="{{ $branch->id }}" @if(isset($category->branch_id))
                @if($category->branch_id == $branch->id)
                selected
                @endif
                @endif>
                {{ $branch->name . '/' . $branch->branch_code }}
            </option>
            @endforeach
        </select>
        <!-- <input type="text" name="business_name" class="form-control" placeholder="@lang('Business name')" /> -->
    </div>

    <div class="form-group mt-1">
        <label><b>@lang('Photo') :</b> <small class="text-danger"><b>@lang('Photo size 400px * 400px').</b> </small></label>
        <input type="file" name="photo" class="form-control " accept=".jpg, .jpeg, .png, .gif">
        <span class="error error_e_photo"></span>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn button-success me-0 float-end" id="update_btn">@lang('Save Changes')</button>
            <button type="button" class="c-btn btn_orange float-end" id="close_cate_form">@lang('Close')</button>
        </div>
    </div>
</form>
