@extends('layout.master')
@push('stylesheets')
    <link href="/assets/css/tab.min.css" rel="stylesheet" type="text/css"/>
@endpush
{{-- @section('title', 'All Categories/SubCategories - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-cubes"></span>
                                <h5>@lang('Categories / SubCategories')</h5>
                            </div>
                        </div>
                    </div>

                    <div class="sec-name">
                        <div class="name-head">
                            <div class="tab_list_area">
                                <ul class="list-unstyled">
                                    <li>
                                        <a id="tab_btn" data-show="categories" class="tab_btn tab_active" href="#">
                                            <i class="fas fa-th-large"></i> @lang('Categories')</a>
                                    </li>

                                    <li>
                                        <a id="tab_btn" data-show="sub-categories" class="tab_btn" href="#">
                                            <i class="fas fa-code-branch"></i> @lang('SubCategories')</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @include('product.categories.bodyPartials.categoriesBody')
                    @include('product.categories.bodyPartials.subCategoriesBody')
                </div>
            </div>
        </div>
    </div>

    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

    <form id="deleted_sub_cate_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection
@push('scripts')
    <script>$('.sub-categories').hide();</script>
    @include('product.categories.jsPartials.categoriesBodyJs')
    @include('product.categories.jsPartials.subCategoriesBodyJs')
@endpush
