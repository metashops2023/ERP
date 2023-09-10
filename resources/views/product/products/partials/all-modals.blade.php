   <!-- Select modal  -->
   <div class="modal fade" id="VairantChildModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog select_variant_modal_dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">@lang('Select variant Child')</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="modal_variant_list_area">
                            <ul class="modal_variant_child">
                                <li class="modal_variant_child_list">
                                    <a class="select_variant_product" data-child="" href="#">X</a>
                                </li>

                                <li class="modal_variant_child_list">
                                    <a class="select_variant_product" data-child="" href="#">X</a>
                                </li>

                                <li class="modal_variant_child_list">
                                    <a class="select_variant_product" data-child="" href="#">X</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Select variant modal -->

<!-- Add Unit Modal -->
<div class="modal fade" id="addUnitModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
    aria-hidden="true">
    <div class="modal-dialog double-col-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">@lang('Add Unit')</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>

            <div class="modal-body">
                <!--begin::Form-->
                <form id="add_unit_form" action="{{ route('products.add.unit') }}">
                    <div class="form-group">
                        <label><b>@lang('Name') :</b></label> <span class="text-danger">*</span>
                        <input type="text" name="name" class="form-control add_unit_input"
                            data-name="Unit name" id="add_unit_name" placeholder="@lang('Unit name')" />
                        <span class="error error_add_unit_name"></span>
                    </div>

                    <div class="form-group mt-1">
                       <label><b>@lang('Unit Code') :</b></label>  <span class="text-danger">*</span>
                        <input type="text" name="code" class="form-control add_unit_input"
                            data-name="Unit code" id="add_unit_code" placeholder="@lang('Unit code')" />
                        <span class="error error_add_unit_code"></span>
                    </div>

                    <div class="form-group row mt-2">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i
                                    class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                            <button type="submit" class="c-btn button-success me-0 float-end submit_button">@lang('Save')</button>
                            <button type="reset" data-bs-dismiss="modal"
                                class="c-btn btn_orange float-end">@lang('Close')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Unit Modal End -->

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
    aria-hidden="true">
    <div class="modal-dialog double-col-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">@lang('Add Category')</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <!--begin::Form-->
                <form id="add_category_form" action="{{ route('products.add.category') }}">
                    <div class="form-group">
                        <b>@lang('Name') :</b> <span class="text-danger">*</span>
                        <input type="text" name="name" class="form-control add_cate_input"
                            data-name="Category name" id="add_cate_name" placeholder="@lang('Category name')" />
                        <span class="error error_add_cate_name"></span>
                    </div>

                    <div class="form-group row mt-2">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i
                                    class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                            <button type="submit" class="c-btn button-success me-0 float-end submit_button">@lang('Save')</button>
                            <button type="reset" data-bs-dismiss="modal"
                                class="c-btn btn_orange float-end">@lang('Close')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Category Modal End -->


<div class="modal fade" id="addSubCategoryModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
    aria-hidden="true">
    <div class="modal-dialog double-col-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">@lang('Add Sub-Category')</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <!--begin::Form-->
                <form id="add_sub_category_form" action="{{ route('product.subcategories.store') }}">
                    <div class="form-group">
                        <b>@lang('Name') :</b> <span class="text-danger">*</span>
                        <input type="text" name="name" class="form-control add_sub_input"
                            data-name="Category name" id="add_sub_input" placeholder="@lang('Sub-Category name')" />
                        <span class="error error_add_sub_input"></span>
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
                    </div>

                    <div class="form-group row mt-2">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i
                                    class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                            <button type="submit" class="c-btn button-success me-0 float-end submit_button">@lang('Save')</button>
                            <button type="reset" data-bs-dismiss="modal"
                                class="c-btn btn_orange float-end">@lang('Close')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


 <!-- Add Brand Modal -->
<div class="modal fade" id="addBrandModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
 aria-hidden="true">
    <div class="modal-dialog double-col-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">@lang('Add Brand')</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <!--begin::Form-->
                <form id="add_brand_form" action="{{ route('products.add.brand') }}">
                    <div class="form-group">
                        <b>@lang('Name') :</b> <span class="text-danger">*</span>
                        <input type="text" name="name" class="form-control add_brand_input"
                            data-name="Brand name" id="add_brand_name" placeholder="@lang('Brand name')" />
                        <span class="error error_add_brand_name"></span>
                    </div>

                    <div class="form-group row mt-2">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i
                                    class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                            <button type="submit" class="c-btn button-success me-0 float-end submit_button">@lang('Save')</button>
                            <button type="reset" data-bs-dismiss="modal"
                                class="c-btn btn_orange float-end">@lang('Close')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Brand Modal End -->

<!-- Add Warranty Modal -->
<div class="modal fade" id="addWarrantyModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
aria-hidden="true">
    <div class="modal-dialog double-col-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">@lang('Add Warranty')</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>

            <div class="modal-body">
                <!--begin::Form-->
                <form id="add_warranty_form" action="{{ route('products.add.warranty') }}">
                    <div class="form-group">
                        <label><b>@lang('Name') :</b> </label> <span class="text-danger">*</span>
                        <input type="text" name="name" class="form-control add_warranty_input" id="add_warranty_name" data-name="Warranty name" placeholder="@lang('Warranty name')"/>
                        <span class="error error_add_warranty_name"></span>
                    </div>

                    <div class="row mt-1">
                        <div class="col-lg-4">
                            <label><b>@lang('Type') : </b> </label> <span class="text-danger">*</span>
                            <select name="type" class="form-control" id="type">
                                <option value="1">@lang('Warranty')</option>
                                <option value="2">@lang('Guaranty')</option>
                            </select>
                        </div>

                        <div class="col-lg-8">
                            <label><b>@lang('Duration') :</b> </label> <span class="text-danger">*</span>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <input type="number" step="any" name="duration" class="form-control w-50 add_warranty_input" data-name="Warranty duration" id="add_warranty_duration" placeholder="@lang('Warranty duration')">
                                        <select name="duration_type" class="form-control w-50" id="duration_type">
                                            <option value="Months">@lang('Months')</option>
                                            <option value="Days">@lang('Days')</option>
                                            <option value="Years">@lang('Years')</option>
                                        </select>
                                    </div>
                                    <span class="error error_add_warranty_duration"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-1">
                        <label><b>@lang('Description') :</b></label>
                        <textarea name="description" id="description" class="form-control" cols="10" rows="3" placeholder="@lang('Warranty description')"></textarea>
                    </div>

                    <div class="form-group row mt-2">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i
                                    class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                            <button type="submit" class="c-btn button-success me-0 float-end submit_button">@lang('Save')</button>
                            <button type="reset" data-bs-dismiss="modal"
                                class="c-btn btn_orange float-end">@lang('Close')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Warranty Modal End -->
