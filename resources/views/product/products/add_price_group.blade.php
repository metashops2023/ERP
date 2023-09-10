@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="add_product_price_group_form" action="{{ route('products.save.price.groups') }}" method="POST">
                @csrf
                <input type="hidden" name="action_type" id="action_type" value="">
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form_element m-0 mt-4">
                                    <div class="py-2 px-2 form-header">
                                        <div class="row">
                                            <div class="col-6"><h5>@lang('Add or edit Price Group') </h5></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form_element m-0 mt-2">
                                    <div class="element-body">
                                        <div class="form_part">
                                            <div class="row mt-2">
                                                <div class="col-md-12">
                                                    <div class="heading_area">
                                                        <p><strong>@lang('Product') : {{ $product_name->name.' ('.$product_name->product_code.')' }}</strong> </p>
                                                        <small class="text-danger">@lang('Tax (If Exists) will be added to all price group').</small>
                                                    </div>
                                                    <div class="table-responsive mt-1">
                                                        <table class="table modal-table table-sm">
                                                            <thead>
                                                                <tr class="bg-primary">
                                                                    @if ($type == 1)
                                                                        <th class="text-white text-start" scope="col">@lang('Variant')</th>
                                                                    @endif
                                                                    <th class="text-white text-center" scope="col">
                                                                        Default Selling Price Exc.Tax
                                                                    </th>
                                                                    @foreach ($priceGroups as $pg)
                                                                        <th class="text-white text-start" scope="col">
                                                                            {{ $pg->name }}
                                                                        </th>
                                                                    @endforeach
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($products as $item)
                                                                    @if ($item->is_variant == 1)
                                                                        <tr>
                                                                            <td class="text-start">
                                                                                <input type="hidden" name="product_ids[]" value="{{ $item->p_id }}">
                                                                                <input type="hidden" name="variant_ids[]" value="{{ $item->v_id }}">
                                                                                {{ $item->variant_name }}
                                                                            </td>
                                                                            <td class="text-center">

                                                                                <b>{{ json_decode($generalSettings->business, true)['currency'] }} {{ $item->variant_price}}</b>
                                                                            </td>
                                                                            @foreach ($priceGroups as $pg)
                                                                                <td class="text-start">
                                                                                    @php
                                                                                        $existsPrice = DB::table('price_group_products')
                                                                                        ->where('price_group_id', $pg->id)
                                                                                        ->where('product_id', $item->p_id)
                                                                                        ->where('variant_id', $item->v_id)->first(['price']);
                                                                                    @endphp
                                                                                    @if ($existsPrice)
                                                                                        <input name="group_prices[{{ $pg->id }}][{{ $item->p_id }}][{{ $item->v_id }}]" type="number" step="any" class="form-control" value="{{ ($existsPrice->price) }}">
                                                                                    @else
                                                                                        <input name="group_prices[{{ $pg->id }}][{{ $item->p_id }}][{{ $item->v_id }}]" type="number" step="any" class="form-control" value="0.00">
                                                                                    @endif
                                                                                </td>
                                                                            @endforeach
                                                                        </tr>
                                                                    @else
                                                                        <tr>
                                                                            <td class="text-center">
                                                                                <input type="hidden" name="product_ids[]" value="{{ $item->p_id }}">
                                                                                <input type="hidden" name="variant_ids[]" value="noid">
                                                                                <b>{{ json_decode($generalSettings->business, true)['currency'] }} {{ $item->product_price }}</b>
                                                                            </td>
                                                                            @foreach ($priceGroups as $pg)
                                                                                <td>
                                                                                    @php
                                                                                        $existsPrice = DB::table('price_group_products')
                                                                                        ->where('price_group_id', $pg->id)
                                                                                        ->where('product_id', $item->p_id)->first(['price']);
                                                                                    @endphp
                                                                                    @if ($existsPrice)
                                                                                        <input name="group_prices[{{ $pg->id }}][{{ $item->p_id }}][noid]" type="number" step="any" class="form-control" value="{{ $existsPrice->price }}">
                                                                                    @else
                                                                                        <input name="group_prices[{{ $pg->id }}][{{ $item->p_id }}][noid]" type="number" step="any" class="form-control" value="0.00">
                                                                                    @endif
                                                                                </td>
                                                                            @endforeach
                                                                        </tr>
                                                                    @endif
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 text-end">
                                <button type="button" class="btn loading_button btn-sm d-none"><i class="fas fa-spinner"></i><strong>@lang('Loading')</strong> </button>
                                <button type="submit" name="action" value="save_and_new" class="btn btn-primary submit_button btn-sm">@lang('Save And Add Another')</button>
                                <button type="submit" name="action" value="save" class="btn btn-primary submit_button btn-sm">@lang('Save')</button>
                            </div>
                        </div>
                </section>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Setup ajax for csrf token.
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
         // Add or edit product price group by ajax
        $('#add_product_price_group_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    $('.loading_button').hide();
                    if(!$.isEmptyObject(data.saveMessage)){
                        toastr.success(data.saveMessage);
                        window.location = "{{ route('products.all.product') }}";
                    }else if(!$.isEmptyObject(data.saveAndAnotherMsg)){
                        toastr.success(data.saveAndAnotherMsg);
                        window.location = "{{ route('products.add.view') }}";
                    }
                }
            });
        });

        $(document).on('click', '.submit_button',function (e) {
            var value = $(this).val();
            $('#action_type').val(value);
        });
    </script>
@endpush
