{{-- @php
   use Illuminate\Support\Str; 
@endphp
    @foreach ($products as $product)
        @if ($product->variant_id)
            @php
                $tax_percent = $product->tax_percent ? $product->tax_percent : 0;
                $tax_amount = $product->variant_price / 100 * $tax_percent;
            @endphp
            <div class="col-4 p-1">
                <a href="#" title="{{ $product->name.' - '.$product->variant_name }}" href="#" class="select_variant_product" data-p_id="{{ $product->id }}" data-v_id="{{ $product->variant_id }}" data-p_name="{{ $product->name }}" data-p_tax_id="{{ $product->tax_id }}" data-unit="{{ $product->unit_name }}" data-tax_percent="{{ $tax_percent }}" data-tax_type="{{ $product->tax_type }}" data-tax_amount="{{ bcadd($tax_amount, 0, 2) }}" data-v_code="{{ $product->variant_code }}" data-description="{{ $product->is_show_emi_on_pos }}" data-v_price="{{ $product->variant_price }}" data-v_name="{{ $product->variant_name }}" data-v_cost_inc_tax="{{ $product->variant_cost_with_tax }}" onclick="salectVariant(this); return false;">
                    <div class="product">
                        <div class="product-img">
                            <img loading="lazy" src="{{ asset('uploads/product/thumbnail/'.$product->thumbnail_photo) }}">
                        </div>
                        <div class="product-name" id="{{ $product->id.$product->variant_id }}">
                            <a href="#">
                                {{ Str::limit($product->name.' - '.$product->variant_name, 12) }}
                            </a>
                        </div>
                    </div>
                </a>
            </div>
        @else 
            @php
                $tax_percent = $product->tax_percent ? $product->tax_percent : 0.00;
                $tax_amount = $product->product_price / 100 * $tax_percent;
            @endphp
            <div class="col-4 p-1">
                <a href="#" title="{{ $product->name }}" href="#" class="select_single_product" data-p_id="{{ $product->id }}" data-p_name="{{ $product->name }}" data-p_tax_id="{{ $product->tax_id }}" data-p_code="{{ $product->product_code }}" data-unit="{{ $product->unit_name }}" data-p_tax_percent="{{ $tax_percent }}" data-tax_type="{{ $product->tax_type }}" data-p_tax_amount="{{ bcadd((float) $tax_amount, 0, 2) }}" data-description="{{ $product->is_show_emi_on_pos }}" data-p_price_exc_tax="{{ $product->product_price }}" data-p_cost_inc_tax="{{ $product->product_cost_with_tax }}" onclick="singleProduct(this); return false;">
                    <div class="product">
                        <div class="product-img">
                            <img loading="lazy" src="{{ asset('uploads/product/thumbnail/'.$product->thumbnail_photo) }}">
                        </div>
                        <div class="product-name" id="{{ $product->id.'noid' }}">
                            <a href="#">
                                {{ Str::limit($product->name, 12) }}
                            </a>
                        </div>
                    </div>
                </a>
            </div>
        @endif
    @endforeach --}}

{{-- separate--}}

{{-- @php
   use Illuminate\Support\Str; 
@endphp
    @foreach ($products as $product)
        @if (count($product->product_variants) > 0)
            @foreach ($product->product_variants as $variant)
                @php
                    $tax_percent = $product->tax ? $product->tax->tax_percent: 0;
                    $tax_amount = $variant->variant_price / 100 * $tax_percent;
                @endphp
                <div class="col-4 p-1">
                    <a href="#" title="{{ $product->name.' - '.$variant->variant_name }}" href="#" class="select_variant_product" data-p_id="{{ $product->id }}" data-v_id="{{ $variant->id }}" data-p_name="{{ $product->name }}" data-p_tax_id="{{ $product->tax_id }}" data-unit="{{ $product->unit->name }}" data-tax_percent="{{ $tax_percent }}" data-tax_type="{{ $product->tax_type }}" data-tax_amount="{{ bcadd($tax_amount, 0, 2) }}" data-v_code="{{ $variant->variant_code }}" data-description="{{ $product->is_show_emi_on_pos }}" data-v_price="{{ $variant->variant_price }}" data-v_name="{{ $variant->variant_name }}" data-v_cost_inc_tax="{{ $variant->updateVariantCost ? $variant->updateVariantCost->net_unit_cost : $variant->variant_cost_with_tax }}" onclick="salectVariant(this); return false;">
                        <div class="product">
                            <div class="product-img">
                                <img loading="lazy" src="{{ asset('uploads/product/thumbnail/'.$product->thumbnail_photo) }}">
                            </div>
                            <div class="product-name" id="{{ $product->id.$variant->id }}">
                                <a href="#">
                                    {{ Str::limit($product->name.' - '.$variant->variant_name, 15, '') }}
                                </a>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @else 
            @php
                $tax_percent = $product->tax ? $product->tax->tax_percent : 0.00;
                $tax_amount = $product->product_price / 100 * $tax_percent;
            @endphp
            <div class="col-4 p-1">
                <a href="#" title="{{ $product->name }}" href="#" class="select_single_product" data-p_id="{{ $product->id }}" data-p_name="{{ $product->name }}" data-p_tax_id="{{ $product->tax_id }}" data-p_code="{{ $product->product_code }}" data-unit="{{ $product->unit->name }}" data-p_tax_percent="{{ $tax_percent }}" data-tax_type="{{ $product->tax_type }}" data-p_tax_amount="{{ bcadd((float) $tax_amount, 0, 2) }}" data-description="{{ $product->is_show_emi_on_pos }}" data-p_price_exc_tax="{{ $product->product_price }}" data-p_cost_inc_tax="{{ $product->updateProductCost ? $product->updateProductCost->net_unit_cost : $product->product_cost_with_tax }}" onclick="singleProduct(this); return false;">
                    <div class="product">
                        <div class="product-img">
                            <img loading="lazy" src="{{ asset('uploads/product/thumbnail/'.$product->thumbnail_photo) }}">
                        </div>
                        <div class="product-name" id="{{ $product->id.'noid' }}">
                            <a href="#">
                                {{ Str::limit($product->name, 15, '') }}
                            </a>
                        </div>
                    </div>
                </a>
            </div>
        @endif
    @endforeach --}}

@php
   use Illuminate\Support\Str; 
@endphp
    @foreach ($products as $product)
        @if (count($product->product_branch_variants) > 0)
            @foreach ($product->product_branch_variants as $variant)
                @php
                    $tax_percent = $product->product->tax ? $product->product->tax->tax_percent: 0;
                    $tax_amount = $variant->product_variant->variant_price / 100 * $tax_percent;
                @endphp
                <div class="col-4 p-1">
                    <a href="#" title="{{ $product->product->name.' - '.$variant->product_variant->variant_name }}" href="#" class="select_variant_product" data-p_id="{{ $product->product->id }}" data-v_id="{{ $variant->product_variant->id }}" data-p_name="{{ $product->product->name }}" data-p_tax_id="{{ $product->product->tax_id }}" data-unit="{{ $product->product->unit->name }}" data-tax_percent="{{ $tax_percent }}" data-tax_type="{{ $product->product->tax_type }}" data-tax_amount="{{ bcadd($tax_amount, 0, 2) }}" data-v_code="{{ $variant->product_variant->variant_code }}" data-description="{{ $product->product->is_show_emi_on_pos }}" data-v_price="{{ $variant->product_variant->variant_price }}" data-v_name="{{ $variant->product_variant->variant_name }}" data-v_cost_inc_tax="{{ $variant->product_variant->updateVariantCost ? $variant->product_variant->updateVariantCost->net_unit_cost : $variant->product_variant->variant_cost_with_tax }}" onclick="salectVariant(this); return false;">
                        <div class="product">
                            <div class="product-img">
                                <img loading="lazy" src="{{ asset('uploads/product/thumbnail/'.$product->product->thumbnail_photo) }}">
                            </div>
                            <div class="product-name" id="{{ $product->product->id.$variant->product_variant->id }}">
                                <a href="#">
                                    {{ Str::limit($product->product->name.' - '.$variant->product_variant->variant_name, 15, '') }}
                                </a>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @else 
            @php
                $tax_percent = $product->tax ? $product->tax->tax_percent : 0.00;
                $tax_amount = $product->product_price / 100 * $tax_percent;
            @endphp
            <div class="col-4 p-1">
                <a href="#" title="{{ $product->product->name }}" href="#" class="select_single_product" data-p_id="{{ $product->id }}" data-p_name="{{ $product->product->name }}" data-p_tax_id="{{ $product->product->tax_id }}" data-p_code="{{ $product->product->product_code }}" data-unit="{{ $product->product->unit->name }}" data-p_tax_percent="{{ $tax_percent }}" data-tax_type="{{ $product->product->tax_type }}" data-p_tax_amount="{{ bcadd((float) $tax_amount, 0, 2) }}" data-description="{{ $product->product->is_show_emi_on_pos }}" data-p_price_exc_tax="{{ $product->product->product_price }}" data-p_cost_inc_tax="{{ $product->product->updateProductCost ? $product->product->updateProductCost->net_unit_cost : $product->product->product_cost_with_tax }}" onclick="singleProduct(this); return false;">
                    <div class="product">
                        <div class="product-img">
                            <img loading="lazy" src="{{ asset('uploads/product/thumbnail/'.$product->product->thumbnail_photo) }}">
                        </div>
                        <div class="product-name" id="{{ $product->product->id.'noid' }}">
                            <a href="#">
                                {{ Str::limit($product->product->name, 15, '') }}
                            </a>
                        </div>
                    </div>
                </a>
            </div>
        @endif
    @endforeach



