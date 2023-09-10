@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="edit_expense_form" action="{{ route('expenses.update', $expense->id) }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form_element">
                                    <div class="section-header">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5>@lang('Edit Expense')</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>@lang('Voucher') :</b> </label>
                                                    <div class="col-8">
                                                        <input readonly type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="@lang('Ex Reference No')" value="{{ $expense->invoice_id }}" autofocus>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class=" col-4"><b>@lang('Expense A/C') :</b> <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <select required name="ex_account_id" class="form-control" id="ex_account_id">
                                                            @foreach ($expenseAccounts as $exAc)
                                                                <option {{ $exAc->id == $expense->expense_account_id ? 'SELECTED' : '' }} value="{{ $exAc->id }}">
                                                                    {{ $exAc->name.' ('.App\Utils\Util::accountType($exAc->account_type).')' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('Expense Date') :</b> </label>
                                                    <div class="col-8">
                                                        <input required type="text" name="date" class="form-control datepicker changeable"
                                                            value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime( $expense->date)) }}" id="datepicker">
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class=" col-4"><b>@lang('Expense For') :</b></label>
                                                    <div class="col-8">
                                                        <select name="admin_id" class="form-control" id="admin_id">
                                                            <option value="">@lang('None')</option>
                                                            @foreach ($users as $user)
                                                                <option {{ $user->id == $expense->admin_id ? 'SELECTED' : '' }} value="{{ $user->id }}">{{ $user->prefix.' '.$user->name.' '.$user->last_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-3">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form_element m-0">
                                    <div class="heading_area">
                                        <div class="row">

                                            <div class="col-md-6">
                                                <p class="text-muted m-0 p-0 ps-1 float-start mt-1"><b>@lang('Descriptions')</b></p>
                                            </div>

                                            <div class="col-md-6">
                                                <a href="#" class="text-primary m-0 p-0 ps-1 float-end me-1" data-bs-toggle="modal" data-bs-target="#addModal"><b>@lang('Add New Category')</b></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="expense_description_table">
                                                    <div class="table-responsive">
                                                        <table class="table modal-table table-sm">
                                                            <tbody id="description_body">
                                                                @foreach ($expense->expense_descriptions as $description)
                                                                    <tr>
                                                                        <td id="index">
                                                                            <b><span class="serial">{{ $loop->index + 1 }}</span></b>
                                                                            <input class="index-{{ $loop->index + 1 }}" type="hidden" id="index">
                                                                            <input type="hidden" name="description_ids[]" id="description_id" value="{{ $description->id }}">
                                                                        </td>
                                                                        <td>
                                                                            <select required name="category_ids[]" class="form-control category_id" id="category_id">
                                                                                <option value="">@lang('Select Expense Category')</option>
                                                                                @foreach ($categories as $category)
                                                                                    <option {{ $category->id == $description->expense_category_id ? 'SELECTED' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>

                                                                        <td>
                                                                            <input required type="number" name="amounts[]" step="any" class="form-control" id="amount" placeholder="@lang('Amount')" value="{{ $description->amount }}">
                                                                        </td>

                                                                        <td>
                                                                            @if ($loop->index == 0)
                                                                                <div class="btn_30_blue" >
                                                                                    <a id="addMore" href=""><i class="fas fa-plus-square"></i></a>
                                                                                </div>
                                                                            @else
                                                                                <a href="#" class="action-btn c-delete" id="remove_btn"><span class="fas fa-trash "></span></a>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
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
                        </div>
                    </div>
                </section>

                <section class="">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form_element m-0">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>@lang('Total') : ({{ json_decode($generalSettings->business, true)['currency'] }})</b> </label>
                                                    <div class="col-8">
                                                        <input readonly class="form-control add_input" name="total_amount" type="number" data-name="Total amount" id="total_amount" value="0.00" step="any" placeholder="@lang('Total amount')">
                                                        <span class="error error_total_amount"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Tax') :</b> </label>
                                                    <div class="col-8">
                                                        <select name="tax" class="form-control" id="tax">
                                                            <option value="0.00">@lang('NoTax')</option>
                                                            @foreach ($taxes as $tax)
                                                                <option {{ $tax->tax_percent == $expense->tax_percent ? 'SELECTED' : '' }} value="{{ $tax->tax_percent }}">{{ $tax->tax_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>@lang('Net Total') : </b>  </label>
                                                    <div class="col-8">
                                                        <input readonly name="net_total_amount" type="number" step="any" id="net_total_amount" class="form-control" value="0.00">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="submit-area py-3 mb-4">
                                <button type="button" class="btn loading_button d-none"><i
                                    class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                <button class="btn btn-sm btn-success submit_button float-end">@lang('Save Changes')</button>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('Add Expense Category')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_quick_expense_category_form" action="{{ route('expenses.add.quick.expense.category') }}">
                        @csrf
                        <div class="form-group">
                            <label><b>@lang('Name')</b> : <span class="text-danger">*</span></label>
                            <input required type="text" name="name" class="form-control" data-name="Name" id="name" placeholder="@lang('Expense Category Name')"/>
                            <span class="error error_ex_name"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><b>@lang('Category ID')</b> : </label>
                            <input type="text" name="code" class="form-control" data-name="Expense category ID" placeholder="@lang('Expense category ID')"/>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                <button type="submit" class="c-btn me-0 button-success float-end submit_button">@lang('Save')</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="c-btn btn_orange float-end">@lang('Close')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Set accounts in payment and payment edit form
        var ex_categories = [];
        function setExpenseCategory(){
            $.ajax({
                url:"{{route('expenses.all.categories')}}",
                async:true,
                type:'get',
                dataType: 'json',
                success:function(categories){
                    ex_categories = categories;
                }
            });
        }
        setExpenseCategory();

         // Calculate amount
         function calculateAmount() {
            var indexs = document.querySelectorAll('#index');

            indexs.forEach(function(index) {

                var className = index.getAttribute("class");
                var rowIndex = $('.' + className).closest('tr').index();
                $('.' + className).closest('tr').find('.serial').html(rowIndex + 1);
            });

            var amounts = document.querySelectorAll('#amount');
            totalAmount = 0;
            amounts.forEach(function(amount){

                totalAmount += parseFloat(amount.value ? amount.value : 0);
            });

            $('#total_amount').val(parseFloat(totalAmount).toFixed(2));
            var tax_percent = $('#tax').val() ? $('#tax').val() : 0;
            var tax_amount = parseFloat(totalAmount) / 100 * parseFloat(tax_percent);
            var netTotalAmount = parseFloat(totalAmount) + parseFloat(tax_amount);
            $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));
            var payingAmount = $('#paying_amount').val() ? $('#paying_amount').val() : 0;
            var totalDue = parseFloat(netTotalAmount) - parseFloat(payingAmount);
            $('#total_due').val(parseFloat(totalDue).toFixed(2));
        }
        calculateAmount();

        $(document).on('input', '#amount',function () {

            calculateAmount();
        });

        $('#tax').on('change', function () {

            calculateAmount();
        });

        $('#paying_amount').on('input', function () {

            calculateAmount();
        });

        $(document).on('click', '#remove_btn', function (e) {
            e.preventDefault();

            $(this).closest('tr').remove();
            calculateAmount();
        });

        //Add purchase request by ajax
        $('#edit_expense_form').on('submit', function(e){
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');
            var inputs = $('.add_input');
                inputs.removeClass('is-invalid');
                $('.error').html('');
                var countErrorField = 0;

            $.each(inputs, function(key, val){

                var inputId = $(val).attr('id');
                var idValue = $('#'+inputId).val();

                if(idValue == ''){

                    countErrorField += 1;
                    var fieldName = $('#'+inputId).data('name');
                    $('.error_'+inputId).html(fieldName+' is required.');
                }
            });

            if(countErrorField > 0){

                $('.loading_button').hide();
                toastr.error("@lang('Please check again all form fields.')");
                return;
            }

            $.ajax({
                url:url,
                type:'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){

                    if(!$.isEmptyObject(data.errorMsg)){

                        toastr.error(data.errorMsg,'ERROR');
                        $('.loading_button').hide();
                    }

                    if(!$.isEmptyObject(data.successMsg)){

                        $('.loading_button').hide();
                        toastr.success(data.successMsg);
                        window.location = "{{route('expenses.index')}}";
                    }
                }
            });
        });

        $('#add_quick_expense_category_form').on('submit', function(e){
            e.preventDefault();

            $('.loading_button').show();
            $('.submit_button').prop('type', 'button');
            var request = $(this).serialize();
            var url = $(this).attr('action');

            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){

                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');

                    if(!$.isEmptyObject(data)){

                        $('.error_ex_').html('');
                        ex_categories.push(data)
                        $('.category_id').each(function() {

                            $(this).append('<option value="'+data.id+'">'+data.name+' ('+data.code+')'+'</option>');
                        });

                        $('#addModal').modal('hide');
                        $('#add_quick_expense_category_form')[0].reset();
                        toastr.success('Expense Category created successfully.');
                    }
                },error: function(err) {

                    $('.loading_button').hide();
                    $('.error_ex_').html('');
                    $('.submit_button').prop('type', 'submit');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Please check the connection..');
                        return;
                    }else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support.');
                    }

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_ex_' + key + '').html(error[0]);
                    });
                }
            });
        });

        var index = 1;
        $(document).on('click', '#addMore', function (e) {
            e.preventDefault();
            var html = '';
            html += '<tr>';
            html += '<td>';
            html += '<b><span class="serial">'+(index + 1)+'</span></b>';
            html += '<input class="index-'+(index + 1)+'" type="hidden" id="index">';
            html += '<input type="hidden" name="description_ids[]" id="description_id" value="">';
            html += '</td>';
            html += '<td>';
            html += '<select required name="category_ids[]" class="form-control category_id">';
            html += '<option value="">@lang('Select Expense Category')</option>';
                $.each(ex_categories, function (key, val) {

                    html += '<option value="'+val.id+'">'+val.name+' ('+val.code+')'+'</option>';
                });
            html += '</select>';
            html += '</td>';

            html += '<td>';
            html += '<input required type="number" name="amounts[]" step="any" class="form-control" id="amount" value="" placeholder="@lang('Amount')">';
            html += '</td>';

            html += '<td>';
            html += '<a href="#" class="action-btn c-delete" id="remove_btn"><span class="fas fa-trash "></span></a>';
            html += '</td>';
            html += '</tr>';
            $('#description_body').append(html);
            calculateAmount();
            index++;
        });

        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
        var _expectedDateFormat = '';
        _expectedDateFormat = dateFormat.replace('d', 'DD');
        _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
        _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
        new Litepicker({
            singleMode: true,
            element: document.getElementById('datepicker'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: _expectedDateFormat,
        });
    </script>
@endpush
