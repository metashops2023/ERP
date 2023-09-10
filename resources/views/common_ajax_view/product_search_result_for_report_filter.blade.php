@foreach ($products as $product)
    <li>
        <a id="select_product" class="name" data-p_id="{{ $product->product_id }}" data-v_id="{{ $product->variant_id ? $product->variant_id : '' }}" href="#">
            {{$product->name}}{{ $product->variant_name ? ' - '.$product->variant_name : ''  }}{{ $product->variant_code ? ' - '.$product->variant_code : ' - '.$product->product_code }}
        </a>
    </li>
@endforeach
