@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
    </style>
@endpush
{{-- @section('title', 'HRM Leaves - ') --}}
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="breadCrumbHolder module w-100">
                                <div id="breadCrumb3" class="breadCrumb module">
                                    <ul>
                                        @if (auth()->user()->permission->manufacturing['process_view'] == '1')
                                            <li>
                                                <a href="{{ route('manufacturing.process.index') }}" class="text-white"><i class="fas fa-dumpster-fire"></i> <b>@lang('menu.process')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->manufacturing['production_view'] == '1')
                                            <li>
                                                <a href="{{ route('manufacturing.productions.index') }}" class="text-white"><i class="fas fa-shapes"></i> <b>@lang('menu.productions')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->manufacturing['manuf_settings'] == '1')
                                            <li>
                                                <a href="{{ route('manufacturing.settings.index') }}" class="text-white"><i class="fas fa-sliders-h text-primary"></i> <b>@lang('menu.manufacturing_setting')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->manufacturing['manuf_report'] == '1')
                                            <li>
                                                <a href="{{ route('manufacturing.report.index') }}" class="text-white"><i class="fas fa-file-alt"></i> <b>@lang('menu.manufacturing_report')</b></a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>@lang('Settings')</h6>
                                </div>
                            </div>

                            <form id="update_settings_form" action="{{ route('manufacturing.settings.store') }}" method="post" class="p-3">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label><strong>@lang('Production Reference prefix') :</strong></label>
                                        @php
                                            $voucherPrefix = '';
                                            if(isset(json_decode($generalSettings->mf_settings, true)['production_ref_prefix'])){
                                                $voucherPrefix = json_decode($generalSettings->mf_settings, true)['production_ref_prefix'];
                                            }
                                        @endphp
                                        <input type="text" name="production_ref_prefix" class="form-control"
                                            autocomplete="off" placeholder="Production Reference prefix"
                                            value="{{ $voucherPrefix }}">
                                    </div>

                                    <div class="col-md-4">
                                        <div class="row mt-1">
                                            <p class="checkbox_input_wrap mt-4">
                                                <input type="checkbox"
                                                    @if(isset(json_decode($generalSettings->mf_settings, true)['enable_editing_ingredient_qty']))
                                                        {{ json_decode($generalSettings->mf_settings, true)['enable_editing_ingredient_qty'] == '1' ? 'CHECKED' : '' }}
                                                    @endif
                                                    name="enable_editing_ingredient_qty"> &nbsp; <b>@lang('Enable editing ingredients quantity in production')</b>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="row mt-1">
                                            <p class="checkbox_input_wrap mt-4">
                                                <input type="checkbox"
                                                    @if(isset(json_decode($generalSettings->mf_settings, true)['enable_updating_product_price']))
                                                        {{ json_decode($generalSettings->mf_settings, true)['enable_updating_product_price'] == '1' ? 'CHECKED' : '' }}
                                                    @endif
                                                    name="enable_updating_product_price"> &nbsp; <b>@lang('Update product cost and selling price based on total production cost, on finalizing production')</b>
                                            </p>
                                        </div>
                                    </div>
                                </div>




                                <div class="row mt-2">
                                    <div class="col-md-12 text-end">
                                        <button type="button" class="btn loading_button d-none"><i
                                            class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                        <button class="btn btn-sm btn-success submit_button float-end">@lang('Save Change')</button>
                                    </div>
                                    {{-- <div class="col-md-12 text-end">
                                        <button type="button" class="btn loading_button d-none"><i
                                            class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                        <button class="btn btn-sm btn-primary submit_button float-end">@lang('Save Change')</button>
                                    </div> --}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')

<script>
    // Setup ajax for csrf token.
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    // call jquery method
    $(document).ready(function(){
        // Update settings by ajax
        $('#update_settings_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });
    });
</script>
@endpush
