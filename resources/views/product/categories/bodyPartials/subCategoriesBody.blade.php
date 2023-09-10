<div class="row mt-1 sub-categories tab_contant">
    <div class="col-md-4">
        <div class="card" id="add_sub_cate_form">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>@lang('Add SubCategory') </h6>
                </div>
            </div>
            <div class="form-area px-3 pb-2">
                <form id="add_sub_category_form" action="{{ route('product.subcategories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mt-1">
                        <label><b>@lang('Name') :</b> <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control " id="name"
                            placeholder="@lang('Sub category name')" />
                        <span class="error error_sub_name"></span>
                    </div>

                    <div class="form-group">
                        <label><b>@lang('Parent category') : <span class="text-danger">*</span></b></label>
                        <select name="parent_category_id" class="form-control " id="parent_category"
                            required>
                            <option selected="" disabled="">@lang('Select Parent Category')</option>
                            @foreach ($categories as $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                        <span class="error error_sub_parent_category_id"></span>
                    </div>

                    <div class="form-group mt-1">
                        <label><b>@lang('Description') :</b> </label>
                        <textarea name="description" class="form-control" cols="30" rows="3" placeholder="@lang('Description')"></textarea>
                    </div>

                    <div class="form-group mt-2">
                        <label><b>@lang('Sub-Category photo') :</b></label>
                        <input type="file" name="photo" class="form-control " id="photo"
                            accept=".jpg, .jpeg, .png, .gif">
                        <span class="error error_sub_photo"></span>
                    </div>

                    <div class="form-group mt-2">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                            <button type="submit" class="c-btn button-success float-end me-0 submit_button">@lang('Save')</button>
                            <button type="reset" class="c-btn btn_orange float-end">@lang('Reset')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card d-none" id="edit_sub_cate_form">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>@lang('Edit SubCategory') </h6>
                </div>
            </div>
            <div class="form-area px-3 pb-2" id="edit_sub_cate_form_body">
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="section-header">
                <div class="col-md-6">
                    <h6>@lang('All SubCategory')</h6>
                </div>
            </div>

            <div class="widget_content">
                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6>
                </div>
                <div class="form-group" style="padding: 5px;">
                    <div>
                        <input class="custom-control-input" type="checkbox" id="chkActiveSub">
                        <label for="chkActiveSub"
                            class="custom-control-label">{{ __('Show Cancelled') }}</label>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="display data_tbl2 data__table w-100">
                        <thead>
                            <tr>
                                <th>@lang('Serial')</th>
                                <th>@lang('Photo')</th>
                                <th>@lang('SubCategory')</th>
                                <th>@lang('Parent Category')</th>
                                <th>@lang('Description')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
