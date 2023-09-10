<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title product_name" id="exampleModalLabel">
                {{ $product->name . ' - ' . $product->product_code }}
            </h5>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="carousel-item active product_image">
                        <img  class="rounded" style="height:170px;width:190px;"
                            src="{{ asset('uploads/product/thumbnail/' . $product->thumbnail_photo) }}" class="d-block w-100">
                    </div>
                </div>

                <div class="col-md-3">
                    <ul class="list-unstyled">
                        <li><strong>@lang('Code(SKU)') : </strong> {{ $product->product_code }}</li>
                        <li><strong>@lang('Brand') : </strong> {{ $product->brand ? $product->brand->name : 'N/A' }}</li>
                        <li><strong>@lang('Unit') : </strong> {{ $product->unit->name }}</li>
                        <li><strong>@lang('Barcode Type') : </strong> {{ $product->barcode_type }}</li>
                        <li><strong>@lang('Manage Stock') : </strong> {!! $product->is_manage_stock == 1 ? "<span class='text-success'>".__('YES')."</span>" : "<span class='text-danger'>".__('NO')."</span>" !!}</li>
                        {{-- <li><strong>@lang('Available Branch'): </strong>
                            @if (count($product->product_branches))
                                @foreach ($product->product_branches as $product_branch)
                                     {{ $product_branch->branch->name . '/' . $product_branch->branch->branch_code }},
                                @endforeach
                            @else
                                Yet-to-be-available-in-any-Branch.
                            @endif
                        </li> --}}
                    </ul>
                </div>

                <div class="col-md-3">
                    <ul class="list-unstyled">
                        <li><strong>@lang('Category') : </strong> {{$product->category ? $product->category->name : 'N/A' }}</li>
                        <li><strong>@lang('Sub-Category') : </strong> {{ $product->subCategory ? $product->subCategory->name : 'N/A' }}</li>
                        <li><strong>@lang('Is For Sale') : </strong>{{ $product->is_for_sale == 1 ? 'Yes' : 'No' }}</li>
                        <li><strong>@lang('Alert Quantity') : </strong>{{ $product->alert_quantity }}</li>
                        <li><strong>@lang('Warranty') : </strong>
                            {{ $product->warranty ? $product->warranty->name : 'N/A' }}
                        </li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <ul class="list-unstyled">
                        <li><strong>@lang('Expire Date') : </strong> {{$product->expire_date ? date(json_decode($generalSettings->business, true)['date_format'], strtotime($product->expire_date)) : 'N/A' }}
                        </li>
                        <li><strong>@lang('Tax') : </strong>{{ $product->tax ? $product->tax->tax_name : 'N/A' }}</li>
                        @if ($product->tax)
                            <li><strong>@lang('Tax Type'): </strong>{{ $product->tax_type == 1 ? 'Exclusive' : 'Inclusive' }}</li>
                        @endif
                        <li><strong>@lang('Product Condition') : </strong> {{ $product->product_condition }}</li>
                        <li>
                            <strong>@lang('Product Type') : </strong>
                            @php
                                $product_type = '';
                            @endphp
                            @if ($product->type == 1 && $product->is_variant == 1)
                                @php $product_type = 'Variant'; @endphp
                            @elseif ($product->type == 1 && $product->is_variant == 0)
                                @php $product_type = 'Single'; @endphp
                            @elseif ($product->type == 2) {
                                @php  $product_type = 'Combo'; @endphp
                            @elseif ($product->type == 3) {
                                @php  $product_type = 'Digital'; @endphp
                            @endif
                            {{ $product_type }}
                        </li>
                        <li>
                            <strong class="text-primary">{{ $product->is_manage_stock == 0 ? '(Service related/Digital Item)' : '' }}</strong>
                        </li>
                    </ul>
                </div>
            </div><br>
            @php $tax = $product->tax ? $product->tax->tax_percent : 0  @endphp
            @if ($product->is_combo == 1)
                <div class="row">
                    <div class="heading">
                        <label class="p-0 m-0"><strong>@lang('COMBO') :</strong></label>
                    </div>
                    <div class="table-responsive" id="combo_product_details">
                        <!--Warehouse Stock Details-->
                        @include('product.products.ajax_view.partials.combo_product_list')
                        <!--Warehouse Stock Details End-->
                    </div>
                </div>
            @else
                @if ($product->is_variant == 0)
                    <div class="row">
                        <div class="heading">
                            <label class="p-0 m-0"><strong>@lang('PURCHASE AND SELLING PRICE DETAILS') :</strong></label>
                        </div>
                        <div class="table-responsive">
                            <!--single_product_pricing_table-->
                            @include('product.products.ajax_view.partials.single_product_pricing_table')
                            <!--single_product_pricing_table End-->
                        </div>
                    </div>
                @elseif($product->is_variant == 1)
                    <div class="row">
                        <div class="heading">
                            <label class="p-0 m-0"><strong>@lang('PURCHASE AND SELLING PRICING DETAILS') :</strong></label>
                        </div>

                        <div class="table-responsive">
                            <!--variant_product_pricing_table-->
                            @include('product.products.ajax_view.partials.variant_product_pricing_table')
                            <!--variant_product_pricing_table End-->
                        </div>
                    </div>
                @endif
            @endif

            @if ($product->is_manage_stock == 1)
                @if (count($own_warehouse_stocks) > 0)
                    <hr class="m-0">

                    <div class="row">
                        <div class="heading">
                            <label class="p-0 m-0">@lang('OWN') <strong>@lang('WAREHOUSE')</strong> @lang('STOCK DETAILS') :</label>
                        </div>
                        <div class="table-responsive" id="warehouse_stock_details">
                            <!--Warehouse Stock Details-->
                            @include('product.products.ajax_view.partials.own_warehouse_stock_details')
                            <!--Warehouse Stock Details End-->
                        </div>
                    </div>
                @endif

                @if (count($global_warehouse_stocks) > 0)
                    <hr class="m-0">

                    <div class="row">
                        <div class="heading">
                            <label class="p-0 m-0">@lang('GLOBAL') <strong>@lang('WAREHOUSE')</strong> @lang('STOCK DETAILS') : </label>
                        </div>
                        <div class="table-responsive" id="warehouse_stock_details">
                            <!--Warehouse Stock Details-->
                            @include('product.products.ajax_view.partials.global_warehouse_stock_details')
                            <!--Warehouse Stock Details End-->
                        </div>
                    </div>
                @endif
                <hr class="m-0">

                <div class="row">
                    <div class="heading">
                        <label class="p-0 m-0">@lang('WON') <strong>@lang('BUSINESS LOCATION')</strong> @lang('STOCK DETAILS') :</label>
                    </div>
                    <div class="table-responsive" id="branch_stock_details">
                        @include('product.products.ajax_view.partials.branch_stock_details')
                    </div>
                </div>

                <hr class="m-0">

                @if ($addons->branches == 1)
                    <div class="row">
                        <div class="heading">
                            <label class="p-0 m-0">@lang('ANOTHER') <strong>@lang('BUSINESS LOCATION')</strong> @lang('STOCK DETAILS') :</label>
                        </div>
                        <div class="table-responsive" id="branch_stock_details">
                            @include('product.products.ajax_view.partials.another_branch_details')
                        </div>
                    </div>
                @endif

            @endif

        <div class="modal-footer text-end">
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="c-btn button-success print_btn">@lang('Print')</button>
                    <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
</div>
