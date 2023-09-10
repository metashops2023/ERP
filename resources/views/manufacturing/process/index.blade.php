@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" type="text/css" href="/backend/asset/css/select2.min.css"/>
@endpush
{{-- @section('title', 'All Process - ') --}}
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
                                                <a href="{{ route('manufacturing.process.index') }}" class="text-white"><i class="fas fa-dumpster-fire text-primary"></i> <b>@lang('menu.process')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->manufacturing['production_view'] == '1')
                                            <li>
                                                <a href="{{ route('manufacturing.productions.index') }}" class="text-white"><i class="fas fa-shapes"></i> <b>@lang('menu.productions')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->manufacturing['manuf_settings'] == '1')
                                            <li>
                                                <a href="{{ route('manufacturing.settings.index') }}" class="text-white"><i class="fas fa-sliders-h"></i> <b>@lang('menu.manufacturing_setting')</b></a>
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

                    <div class="row mt-1">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>@lang('Process')</h6>
                                    </div>

                                    @if (auth()->user()->permission->manufacturing['process_add'] == '1')
                                        <div class="col-md-6">
                                            <div class="btn_30_blue float-end">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i
                                                        class="fas fa-plus-square"></i> @lang('Add')</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="widget_content">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner text-primary"></i> @lang('Processing')...</h6>
                                    </div>
                                    <div class="table-responsive" id="data-list">
                                        <form id="update_product_cost_form" action="">
                                            <table class="display data_tbl data__table">
                                                <thead>
                                                    <tr class="bg-navey-blue">
                                                        <th data-bSortable="false">
                                                            <input class="all" type="checkbox" name="all_checked"/>
                                                        </th>
                                                        <th class="text-black">@lang('Actions')</th>
                                                        <th class="text-black">@lang('Product Name')</th>
                                                        <th class="text-black">@lang('Category')</th>
                                                        <th class="text-black">@lang('SubCategory')</th>
                                                        <th class="text-black">@lang('Wastage')</th>
                                                        <th class="text-black">@lang('Output Quantity')</th>
                                                        <th class="text-black">@lang('Total Ingrediant Cost')</th>
                                                        <th class="text-black">@lang('Production Cost')</th>
                                                        <th class="text-black">@lang('Total Cost')</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>

                                @if (auth()->user()->permission->manufacturing['process_delete'] == '1')
                                    <form id="deleted_form" action="" method="post">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (auth()->user()->permission->manufacturing['process_add'] == '1')
        <div class="modal fade" id="addModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog double-col-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">@lang('Choose Product')</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                                class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body">
                        <!--begin::Form-->
                        <form action="{{ route('manufacturing.process.create') }}" method="GET">
                            <div class="form-group">
                                <label><b>@lang('Select Product')</b> : <span class="text-danger">*</span></label>
                                <select required name="product_id" class="form-control select2">
                                    @foreach ($products as $product)
                                        @php
                                            $variant_name = $product->variant_name ? $product->variant_name : '';
                                            $product_code = $product->variant_code ? $product->variant_code : $product->product_code;
                                        @endphp
                                        <option value="{{ $product->id.'-'.($product->v_id ? $product->v_id : 'NULL') }}">{{ $product->name.' '.$variant_name.' ('.$product_code.')' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <button type="button" class="btn loading_button d-none">
                                        <i class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b>
                                    </button>
                                    <button type="submit" class="c-btn me-0 button-success float-end submit_button">@lang('Save')</button>
                                    <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('Close')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-50-modal" role="document">
            <div class="modal-content" id="view-modal-content">

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="/backend/asset/js/select2.min.js"></script>
    <script>
        $('.select2').select2();

        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: [3,4,5,6,7,8,9,10,11,12]}},
                {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: [3,4,5,6,7,8,9,10,11,12]}},
                {extend: 'print',text: '<i class="fas fa-print"></i> @lang("Print")',className: 'btn btn-primary',exportOptions: {columns: [3,4,5,6,7,8,9,10,11,12]}},
            ],
            "processing": true,
            "serverSide": true,
            language: {
                search: "@lang('Search')",
                emptyTable: "@lang('EmptyTable')",
                infoEmpty: "@lang('EmptyTable')",
                sInfo: "@lang('Showing _START_ to _END_ of _TOTAL_ entries')",
                sInfoEmpty: "@lang('Showing 0 to 0 of 0 entries')",
                sLengthMenu: "@lang('Show _MENU_ entries')",
                paginate: {
                    next: "@lang('Next')",
                    previous: "@lang('Previous')"

                },
            },
            aaSorting: [[0, 'asc']],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            ajax: "{{ route('manufacturing.process.index') }}",
            columnDefs: [{"targets": [0],"orderable": false,"searchable": false}],
            columns: [
                {data: 'multiple_update',name: 'multiple_update'},
                {data: 'action',name: 'action'},
                {data: 'product',name: 'product'},
                {data: 'cate_name',name: 'cate_name'},
                {data: 'sub_cate_name',name: 'sub_cate_name'},
                {data: 'wastage_percent',name: 'wastage_percent'},
                {data: 'total_output_qty',name: 'total_output_qty'},
                {data: 'total_ingredient_cost',name: 'total_ingredient_cost'},
                {data: 'production_cost',name: 'production_cost'},
                {data: 'total_cost',name: 'total_cost'},
            ],
        });

        //Show process view modal with data
        $(document).on('click', '#view', function (e) {
           e.preventDefault();

           var url = $(this).attr('href');

            $.get(url, function(data) {

                $('#view-modal-content').html(data);
                $('#viewModal').modal('show');
            });
        });

        $(document).on('click', '#delete',function(e){
            e.preventDefault();

            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);

            $.confirm({
            'title': "@lang('Delete Confirmation')",
            'content': "@lang('Are you sure, you want to delete?')",
            'buttons': {
                @lang("YES"): {'class': 'yes btn-modal-primary','action': function() {$('#deleted_form').submit();}},
                @lang("NO"): {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
            }
        });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_form',function(e){
            e.preventDefault();

            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){

                    table.ajax.reload();
                    toastr.error(data);
                }
            });
        });

        $(document).on('change', '.all', function() {

            if ($(this).is(':CHECKED', true)) {

                $('.data_id').prop('checked', true);
            } else {

                $('.data_id').prop('checked', false);
            }
        });
    </script>
@endpush
