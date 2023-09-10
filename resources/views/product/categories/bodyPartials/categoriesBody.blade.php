<div class="row px-2 mt-1 categories tab_contant">
    <div class="col-md-4">
        <div class="card" id="add_cate_form">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>@lang('Add Category') </h6>
                </div>
            </div>

            <div class="form-area px-3 pb-2">
                <form id="add_category_form" action="{{ route('product.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label><b>@lang('Name') :</b> <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="@lang('Category name')" />
                        <span class="error error_name"></span>
                    </div>

                    <div class="form-group mt-1">
                        <label><b>@lang('Description') :</b> </label>
                        <textarea name="description" class="form-control" cols="30" rows="3" placeholder="@lang('Description')"></textarea>
                    </div>

                    <div class="form-group mt-1">
                        <b>@lang('Business Name') :</b> <span class="text-danger">*</span>
                        <select name="add_branch_id" id="add_branch_id" class="form-control">
                            @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">
                                {{ $branch->name . '/' . $branch->branch_code }}
                            </option>
                            @endforeach
                        </select>
                        <!-- <input type="text" name="business_name" class="form-control" placeholder="@lang('Business name')" /> -->
                    </div>

                    <div class="form-group mt-1">
                        <label><b>@lang('Photo') :</b> <small class="text-danger"><b>@lang('Photo size 400px * 400px').</b></small></label>
                        <input type="file" name="photo" class="form-control" id="photo">
                        <span class="error error_photo"></span>
                    </div>

                    <div class="form-group row mt-2">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                            <button type="submit" class="c-btn button-success me-0 float-end submit_button">@lang('Save')</button>
                            <button type="reset" class="c-btn btn_orange float-end">@lang('Reset')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card" style="display:none;" id="edit_cate_form">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>@lang('Edit Category') </h6>
                </div>
            </div>

            <div class="form-area px-3 pb-2" id="edit_cate_form_body"></div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="section-header">
                <div class="col-md-6">
                    <h6>@lang('All Category')</h6>
                </div>
            </div>

            <div class="widget_content">
                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6>
                </div>
                <div class="form-group" style="padding: 5px;">
                    <div class="custom-control custom-switch pull-right">
                        <input class="custom-control-input" type="checkbox" id="chkActive">
                        <label for="chkActive"
                            class="custom-control-label">{{ __('Show Cancelled') }}</label>
                    </div>
                </div>
                <div class="table-responsive" id="data-list">
                    <table class="display data_tbl data__table">
                        <thead>
                            <tr class="bg-navey-blue">
                                <th class="text-black">@lang('Serial')</th>
                                <th class="text-black">@lang('Photo')</th>
                                <th class="text-black">@lang('Name')</th>
                                <th class="text-black">@lang('Description')</th>
                                <th class="text-black">@lang('Status')</th>
                                <th class="text-black">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
