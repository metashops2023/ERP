<table id="kt_datatable" class="table table-bordered table-striped">
    <thead>
        <tr class="text-left bg-navey-blue">
            @if (auth()->user()->permission->product['product_delete']  == '1')
                <th data-bSortable="false">
                    <input class="all" type="checkbox" name="all_checked"/>
                </th>
            @endif
            <th class="text-white">@lang('Image')</th>
            <th class="text-white">@lang('Actions')</th>
            <th class="text-white">@lang('Product')</th>
            <th class="text-white">@lang('Purchase Cost')</th>
            <th class="text-white">@lang('Salling Price')</th>
            <th class="text-white">@lang('Current Stock')</th>
            <th class="text-white">@lang('Product Type')</th>
            <th class="text-white">@lang('Category')</th>
            <th class="text-white">@lang('Brand')</th>
            <th class="text-white">@lang('Tax')</th>
            <th class="text-white">@lang('Expire Date')</th>
            <th class="text-white">@lang('Status')</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr data-info="{{ $product }}" class="clickable_row text-left">
                @if (auth()->user()->permission->product['product_delete']  == '1')
                    <td>
                        <input id="{{ $loop->index }}" class="data_id" type="checkbox" name="data_ids[]" value="{{ $product->id }}"/>
                    </td>
                @endif

                <td><img loading="lazy" class="rounded" width="50" height="50" src="{{ asset('uploads/product/thumbnail/'.$product->thumbnail_photo) }}" alt=""></td>

                <td>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @lang('Action')
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" id="check_pur_and_gan_bar_button" href="{{ route('products.check.purchase.and.generate.barcode', $product->id) }}"><i class="fas fa-barcode mr-1 text-primary"></i>@lang('Barcode')</a>
                            <a class="dropdown-item details_button" href="#"><i class="far fa-eye mr-1 text-primary"></i>@lang('View')</a>
                            @if (auth()->user()->permission->product['product_edit']  == '1')
                                <a class="dropdown-item" href="{{ route('products.edit', $product->id) }}"><i class="far fa-edit mr-1 text-primary"></i>@lang('Edit')</a>
                            @endif

                            @if (auth()->user()->permission->product['product_delete']  == '1')
                                <a class="dropdown-item" id="delete" href="{{ route('products.delete', $product->id) }}"><i class="far fa-trash-alt mr-1 text-primary"></i>@lang('Delete')</a>
                            @endif

                            @if ($product->status == 1)
                                <a class="dropdown-item" id="change_status" href="{{ route('products.change.status', $product->id) }}"><i class="far fa-thumbs-up mr-1 text-success"></i>@lang('Change Status')</a>
                            @else
                                <a class="dropdown-item" id="change_status" href="{{ route('products.change.status', $product->id) }}"><i class="far fa-thumbs-down mr-1 text-danger"></i>@lang('Change Status')</a>
                            @endif

                            @if (auth()->user()->permission->product['openingStock_add']  == '1')
                                <a class="dropdown-item" id="opening_stock" href="{{ route('products.opening.stock', $product->id) }}"><i class="fas fa-database mr-1 text-primary"></i>@lang('Add or edit opening stock')</a>
                            @endif
                        </div>
                    </div>
                </td>

                <td>{{ $product->name }}</td>

                <td>
                   <b>{{ json_decode($generalSettings->business, true)['currency'] .' '. $product->product_cost_with_tax }}</b>
                </td>

                <td>
                    <b>{{ json_decode($generalSettings->business, true)['currency'] .' '. $product->product_price}} </b>
                </td>

                <td>
                    <b> {!! $product->quantity <= $product->alert_quantity ? '<span class="text-danger">'. $product->quantity .'</span>' : '<span class="text-success">'. $product->quantity .'</span>' !!} </b>
                </td>

                <td>
                    @if ($product->type == 1 && $product->is_variant == 1)
                        <span class="text-primary">@lang('Variant')</span>
                    @elseif($product->type == 1 && $product->is_variant == 0)
                        <span class="text-success">@lang('Single')</span>
                    @elseif($product->type == 2)
                        <span class="text-info">@lang('Combo')</span>
                    @elseif($product->type == 3)
                        <span class="text-info">@lang('Digital')</span>
                    @endif
                </td>

                <td>
                    {{ $product->category ? $product->category->name : 'N/A' }} {!! $product->child_category ? '<br>--'.$product->child_category->name : '' !!}
                </td>

                <td>
                    {{ $product->brand ? $product->brand->name : 'N/A' }}
                </td>

                <td>
                    {{ $product->tax ? $product->tax->tax_name : 'NoTax' }}
                </td>
                <td>{{ $product->expire_date ? date('d/m/Y', strtotime($product->expire_date)) : 'N/A' }}</td>
                <td>
                    @if ($product->status == 1)
                        <i class="far fa-thumbs-up mr-1 text-success"></i>
                    @else
                        <i class="far fa-thumbs-down mr-1 text-danger"></i>
                    @endif
                </td>

            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="12">
                @if (auth()->user()->permission->product['product_delete'])
                    <a href="" class="btn btn-sm btn-danger multipla_delete_btn">@lang('Delete Selected')</a>
                @endif
                <a href="" class="btn btn-sm btn-primary">@lang('Remove Form Branch')</a>
                <a href="" class="btn btn-sm btn-warning multipla_deactive_btn">@lang('Deactivate Selected')</a>
            </th>
        </tr>
    </tfoot>
</table>

<!--Data table js active link-->
<script src="/assets/plugins/custom/data-table/datatable.active.js"></script>
<!--Data table js active link end-->
