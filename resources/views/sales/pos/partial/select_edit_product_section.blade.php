<div class="col-lg-5">
    <div class="row">
        <div class="category-sec col-lg-4 col-4">
            <div class="left-cat-pos">
                <div class="all-cat">
                    <a href="#" data-id="" class="cat-button" tabindex="-1">All</a>
                    @foreach ($categories as $cate)
                        <a href="#" data-id="{{ $cate->id }}" class="cat-button" tabindex="-1">{{ $cate->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-8 p-1">
            <div class="show-product">
                <div class="product-inner">
                    <div class="category-head">
                        <div class="cat-ban-sec">
                            <div class="row">
                                <div class="col-6">
                                    <select name="category_id" id="category_id" class="form-select form-control cat-bg-1 common_submitable" tabindex="-1">
                                        <option value="">All Categories</option>
                                        @foreach ($categories as $cate)
                                            <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6">
                                    <select id="brand_id" id="brand_id" class="form-select form-control cat-bg-2 bg common_submitable" tabindex="-1">
                                        <option value="">All Brands</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-area">
                        <div class="data_preloader select_product_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                        </div>
                        <div class="product-ctn">
                            <div class="row" id="select_product_list">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function selectProductList() {
        $('.select_product_preloader').show();
        var category_id = $('#category_id').val();
        var brand_id = $('#brand_id').val();
        $.ajax({
            url: "{{ route('sales.pos.product.list') }}",
            type: 'get',
            data: {category_id,brand_id,},
            success: function(data) {
                //console.log(data);
                $('#select_product_list').html(data);
                $('.select_product_preloader').hide();
            }
        });
    }
    selectProductList();

    //Submit filter form by select input changing
    $(document).on('change', '.common_submitable', function() {
        selectProductList();
    });

    $(document).on('click', '.cat-button', function(e) {
        e.preventDefault();
        var cate_id = $(this).data('id');
        console.log();
        $('#category_id').val(cate_id);
        selectProductList();
    });
</script>
