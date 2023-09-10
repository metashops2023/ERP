 <!--begin::Form-->
<form id="edit_sub_category_form" action="{{ route('product.subcategories.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="id" value="{{ $data->id }}">

    <div class="form-group">
        <label><b>@lang('Parent category') :</b> <span class="text-danger">*</span></label>
        <select name="parent_category_id" class="form-control" id="edit_parent_category">
        	@foreach($category as $row)
             <option value="{{ $row->id }}" @if($data->parent_category_id==$row->id) selected @endif>{{ $row->name }}</option>
            @endforeach
        </select>
        <span class="error error_sub_e_parent_category_id"></span>
    </div>

    <div class="form-group mt-1">
        <label><b>@lang('Name') :</b> <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control " value="{{ $data->name }}" id="e_sub_name" placeholder="@lang('Sub category name')"/>
        <span class="error error_sub_e_name"></span>
    </div>

    <div class="form-group mt-1">
        <label><b>@lang('Description') :</b> </label>
        <textarea name="description" class="form-control" cols="30" rows="3" placeholder="@lang('Description')">{{ $data->description }}</textarea>
    </div>

    <div class="form-group editable_cate_img_field mt-1">
        <label><b>@lang('Sub Category photo') :</b></label>
        <input type="file" name="photo" class="form-control" id="e_photo" accept=".jpg, .jpeg, .png, .gif">
        <span class="error error_sub_e_photo"></span>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
            <button type="submit" class="c-btn button-success float-end me-0">@lang('Save Changes')</button>
            <button type="button" class="c-btn btn_orange float-end" id="close_sub_cate_form">@lang('Close')</button>
        </div>
    </div>
</form>
