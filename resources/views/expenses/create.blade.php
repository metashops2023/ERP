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
            <form id="add_expense_form" action="{{ route('expenses.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form_element">
                                    <div class="py-2 px-2 form-header">
                                        <div class="row">
                                            <div class="col-8">
                                                <h6>@lang('Add Expense')</h6>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>@lang('Voucher No'):</b> <i data-bs-toggle="tooltip" data-bs-placement="right" title="If you keep this field empty, The Voucher will be generated automatically." class="fas fa-info-circle tp"></i></label>
                                                    <div class="col-8">
                                                        <input type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="@lang('Ex Reference No')" autofocus>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class=" col-4"><b>@lang('Expense A/C') :</b> <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <select required name="ex_account_id" class="form-control" id="ex_account_id">
                                                            @foreach ($expenseAccounts as $exAc)
                                                                <option value="{{ $exAc->id }}">
                                                                    {{ $exAc->name.' ('.App\Utils\Util::accountType($exAc->account_type).')' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>@lang('Expense Date') :</b> <span
                                                        class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <input required type="text" name="date" class="form-control changeable"
                                                            value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" id="datepicker">
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class=" col-4"><b>@lang('Expense For') :</b></label>
                                                    <div class="col-8">
                                                        <select name="admin_id" class="form-control" id="admin_id">
                                                            <option value="">@lang('None')</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{$user->id}}">{{ $user->prefix.' '.$user->name.' '.$user->last_name }}</option>
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
                                                                <tr>
                                                                    <td id="index">
                                                                        <b><span class="serial">1</span></b>
                                                                        <input class="index-1" type="hidden" id="index">
                                                                    </td>

                                                                    <td>
                                                                        <select required name="category_ids[]" class="form-control category_id" id="category_id">
                                                                            <option value="">@lang('Select Expense Category')</option>
                                                                        </select>
                                                                    </td>

                                                                    <td>
                                                                        <input required type="number" name="amounts[]" step="any" class="form-control" id="amount" value="" placeholder="@lang('Amount')">
                                                                    </td>

                                                                    <td>
                                                                        <div class="btn_30_blue" >
                                                                            <a id="addMore" href=""><i class="fas fa-plus-square"></i></a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
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
                                                    <label class=" col-4"><b>@lang('Total') : </b> </label>
                                                    <div class="col-8">
                                                        <input readonly class="form-control add_input" name="total_amount" type="number" data-name="Total amount" id="total_amount" value="0.00" step="any" placeholder="@lang('Total amount')">
                                                        <span class="error error_total_amount"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('Tax') :</b> </label>
                                                    <div class="col-8">
                                                        <select name="tax" class="form-control" id="tax">
                                                            <option value="0.00">@lang('NoTax')</option>
                                                            @foreach ($taxes as $tax)
                                                                <option value="{{ $tax->tax_percent }}">{{ $tax->tax_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>@lang('Net Total') : </b>  </label>
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
                @include('expenses.partials.expensePaymentSection')
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

                    $.each(categories, function (key, category) {

                        $('#category_id').append('<option value="'+category.id+'">'+ category.name +' ('+category.code+')'+'</option>');
                    });
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
           // $('#paying_amount').val(parseFloat(netTotalAmount).toFixed(2));
            var payingAmount = $('#paying_amount').val() ? $('#paying_amount').val() : 0;
            var totalDue = parseFloat(netTotalAmount) - parseFloat(payingAmount);
            $('#total_due').val(parseFloat(totalDue).toFixed(2));
        }

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

        var action = '';
        //Add purchase request by ajax
        $('#add_expense_form').on('submit', function(e){
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');
            var inputs = $('.add_input');
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

            $('.submit_button').prop('type', 'button');
            $.ajax({
                url:url,
                type:'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){

                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');
                    if(!$.isEmptyObject(data)){

                        toastr.success('Expense created successfully.');
                        $('.loan_amount_field').hide();
                        $('.extra_category').remove();
                        $('#add_expense_form')[0].reset();
                        calculateAmount();

                        if (action == 'sale_and_print') {

                            $(data).printThis({
                                debug: false,
                                importCSS: true,
                                importStyle: true,
                                loadCSS: "{{asset('assets/css/print/purchase.print.css')}}",
                                removeInline: false,
                                printDelay: 500,
                                header: null,
                                footer: null,
                            });
                        }
                    }
                },error: function(err) {

                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');

                    toastr.error("@lang('Please check again all form fields.')",
                    "@lang('Something went wrong.')");

                    $('.error').html('');

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
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
           html += '<tr class="extra_category">';
            html += '<td>';
            html += '<b><span class="serial">'+(index + 1)+'</span></b>';
            html += '<input class="index-'+(index + 1)+'" type="hidden" id="index">';
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

        $(document).on('click', '.submit_button',function () {

            action = $(this).data('action');
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

        document.onkeyup = function () {

            var e = e || window.event; // for IE to cover IEs window event-object

            if(e.ctrlKey && e.which == 13) {

                $('#save_and_print').click();
                return false;
            }else if (e.shiftKey && e.which == 13) {

                $('#save').click();
                return false;
            }
        }

        $('#payment_method_id').on('change', function () {

            var account_id = $(this).find('option:selected').data('account_id');
            setMethodAccount(account_id);
        });

        function setMethodAccount(account_id) {

            if (account_id) {

                $('#account_id').val(account_id);
            }else if(account_id === ''){

                $('#account_id option:first-child').prop("selected", true);
            }
        }

        setMethodAccount($('#payment_method_id').find('option:selected').data('account_id'));
    </script>
@endpush
