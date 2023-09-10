@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {
            border: 1px solid #7e0d3d;
        }

        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }

    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="update_payroll_form" action="{{ route('hrm.payrolls.update', $payroll->id) }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ $payroll->employee->id }}">
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form_element m-0 mt-4">
                                    <div class="py-2 px-2 form-header">
                                        <div class="row">
                                            <div class="col-9"><h5>Edit Payroll Of
                                                <b>{{ $payroll->employee->name }}</b> @lang('for') <b>{{ $payroll->month. ' '.$payroll->year }}</b> (Reference No : {{$payroll->reference_no}})</h5></div>
                                        </div>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label><b>@lang('Total Work Duration') :</b> <span class="text-danger">*
                                                    </span> </label>
                                                <input type="number" step="any" name="duration_time" id="duration_time" class="form-control"
                                                    placeholder="@lang('Total Work Duration')" autofocus value="{{ $payroll->duration_time }}">
                                                <span class="error error_duration_time"></span>
                                            </div>

                                            <div class="col-md-3">
                                                <label><span class="text-danger">* </span><b>@lang('Unit (Pay Type)') :</b> </label>
                                                <select name="duration_unit" id="duration_unit" class="form-control">
                                                    <option {{ $payroll->duration_unit == 'Hourly' ? 'SELECTED' : '' }} value="Hourly">@lang('Hourly')</option>
                                                    <option {{ $payroll->duration_unit == 'Monthly' ? 'SELECTED' : '' }} value="Monthly">@lang('Monthly')</option>
                                                    <option {{ $payroll->duration_unit == 'Yearly' ? 'SELECTED' : '' }}  value="Yearly">@lang('Hour')</option>
                                                    <option {{ $payroll->duration_unit == 'Daliy' ? 'SELECTED' : '' }} value="Daliy">@lang('Week')</option>
                                                </select>
                                                <span class="error error_duration_unit"></span>
                                            </div>

                                            <div class="col-md-3">
                                                <label><b>@lang('Amount per unit duration') :</b> <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" step="any" name="amount_per_unit" id="amount_per_unit"
                                                    class="form-control" placeholder="@lang('Amount per unit duration')" value="{{ $payroll->amount_per_unit }}">
                                                <span class="error error_amount_per_unit"></span>
                                            </div>

                                            <div class="col-md-3">
                                                <label><b>@lang('Total') :</b> <span class="text-danger">*</span></label>
                                                <input readonly type="total" step="any" name="total_amount"
                                                    id="total_amount" class="form-control" placeholder="@lang('total')" value="{{ $payroll->total_amount }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">

                                    <div class="heading_area">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6">
                                                <p class="p-2 text-primary"><b>@lang('Allowances')</b> </p>
                                            </div>

                                            <div class="col-md-6 col-sm-6">
                                                <div class="btn_30_blue_small float-end me-1" id="add_more_allowance">
                                                    <a href="#"><i class="fas fa-plus-square"></i> @lang('Add')</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table modal-table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-navy-blue">@lang('Allowance')</th>
                                                                <th class="text-navy-blue">@lang('Amount Type')</th>
                                                                <th class="text-navy-blue">@lang('Amount')</th>
                                                                <th class="text-right"><i class="fas fa-trash-alt text-dark"></i></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="allowance_body">
                                                            @php $index = 0; @endphp
                                                            @if (count($payroll->allowances) > 0)
                                                                @foreach ($payroll->allowances as $allowance)
                                                                    <tr>
                                                                        <td>
                                                                            <input type="hidden" name="payroll_allowance_id[{{ $index }}]" value="{{ $allowance->id }}">
                                                                            <input type="hidden" class="allowance-{{ $index }}" id="allowances">
                                                                            <input type="text" name="allowance_names[{{ $index }}]" class="form-control" id="allowance_name" placeholder="@lang('Allowance Name')" value="{{ $allowance->allowance_name }}">
                                                                        </td>

                                                                        <td>
                                                                            <select class="form-control" name="al_amount_types[{{ $index }}]" id="al_amount_type">
                                                                                <option {{ $allowance->amount_type == 1 ? 'SELECTED' : '' }}  value="1">@lang('Fixed')</option>
                                                                                <option {{ $allowance->amount_type == 2 ? 'SELECTED' : '' }} value="2">@lang('Percentage')</option>
                                                                            </select>

                                                                            <div class="input-group allowance_percent_field {{ $allowance->amount_type == 1 ? 'd-none' : '' }} ">

                                                                                <input type="number" step="any" name="allowance_percents[{{ $index }}]" class="form-control" autocomplete="off" value="{{ $allowance->amount_type == 2 ? $allowance->allowance_percent : 0.00 }}" id="allowance_percent">

                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text" id="basic-addon1">
                                                                                        <i class="fas fa-percentage input_i"></i>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </td>

                                                                        <td>
                                                                            <input type="number" step="any" name="allowance_amounts[{{ $index }}]" class="form-control" id="allowance_amount" placeholder="@lang('Amount')" value="{{ $allowance->allowance_amount }}">
                                                                        </td>

                                                                        <td class="text-right">
                                                                            <a href="#" id="remove_allowane" class="btn btn-sm btn-danger mt-1">X</a>
                                                                        </td>
                                                                    </tr>
                                                                    @php $index++; @endphp
                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td>
                                                                        <input type="hidden" name="payroll_allowance_id[{{ $index }}]" value="noid">
                                                                        <input type="hidden" class="allowance-{{ $index }}" id="allowances">
                                                                        <input type="text" name="allowance_names[{{ $index }}]" class="form-control" id="allowance_name" placeholder="@lang('Allowance Name')">
                                                                    </td>

                                                                    <td>
                                                                        <select class="form-control" name="al_amount_types[{{ $index }}]" id="al_amount_type">
                                                                            <option value="1">@lang('Fixed')</option>
                                                                            <option value="2">@lang('Percentage')</option>
                                                                        </select>

                                                                        <div class="input-group allowance_percent_field d-none">
                                                                            <input type="number" step="any" name="allowance_percents[{{ $index }}]" class="form-control" autocomplete="off" value="0.00" id="allowance_percent">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text" id="basic-addon1">
                                                                                    <i class="fas fa-percentage input_i"></i>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <input type="number" step="any" name="allowance_amounts[{{ $index }}]" class="form-control" id="allowance_amount" placeholder="@lang('Amount')" value="0.00">
                                                                    </td>

                                                                    <td class="text-right">
                                                                        <a href="#" id="remove_allowane" class="btn btn-sm btn-danger mt-1">X</a>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th class="text-navy-blue" colspan="2">@lang('Total')</th>
                                                                <th class="text-navy-blue" colspan="2">
                                                                    $
                                                                    <span class="span_total_allowance_amount">{{ $payroll->total_allowance_amount }}</span>
                                                                    <input name="total_allowance_amount" type="hidden" id="total_allowance_amount" value="{{ $payroll->total_allowance_amount }}">
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="p-2 text-primary"><b>@lang('Deductions')</b> </p>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="btn_30_blue_small float-end me-1" id="add_more_deduction">
                                                    <a href="#"><i class="fas fa-plus-square"></i> @lang('Add')</a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-navy-blue">@lang('Deduction')</th>
                                                                <th class="text-navy-blue">@lang('Amount Type')</th>
                                                                <th class="text-navy-blue">@lang('Amount')</th>
                                                                <th class="text-right"><i class="fas fa-trash-alt text-dark"></i></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="deduction_body">
                                                            @php $index2 = 0; @endphp
                                                            @if (count($payroll->deductions) > 0)
                                                                @foreach ($payroll->deductions as $deduction)
                                                                    <tr>
                                                                        <td>
                                                                            <input type="hidden" name="payroll_deduction_id[{{$index2}}]" value="{{ $deduction->id }}">
                                                                            <input type="hidden" class="deduction-{{ $index2 }}" id="deductions">
                                                                            <input type="text" name="deduction_names[{{ $index2 }}]" id="deduction_name" class="form-control" placeholder="@lang('Deduction Name')" value="{{ $deduction->deduction_name }}">
                                                                        </td>

                                                                        <td>
                                                                            <select class="form-control" name="de_amount_types[{{ $index2 }}]" id="de_amount_type">
                                                                                <option {{ $deduction->amount_type == 1 ? 'SELECTED' : '' }} value="1">@lang('Fixed')</option>
                                                                                <option {{ $deduction->amount_type == 2 ? 'SELECTED' : '' }} value="2">@lang('Percentage')</option>
                                                                            </select>

                                                                            <div class="input-group deduction_percent_field {{ $deduction->amount_type == 1 ? 'd-none' : '' }} ">

                                                                                <input type="number" step="any" name="deduction_percents[{{ $index2 }}]" class="form-control" autocomplete="off" value="{{ $deduction->amount_type == 2 ? $deduction->deduction_percent : 0.00 }}" id="deduction_percent">

                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text" id="basic-addon1">
                                                                                        <i class="fas fa-percentage input_i"></i>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </td>

                                                                        <td>
                                                                            <input type="number" step="any" name="deduction_amounts[{{ $index2 }}]" id="deduction_amount" class="form-control" placeholder="@lang('Amount')" value="{{ $deduction->deduction_amount }}">
                                                                        </td>

                                                                        <td class="text-right">
                                                                            <a href="#" id="remove_deduction" class="btn btn-sm btn-danger mt-1">X</a>
                                                                        </td>
                                                                    </tr>
                                                                    @php $index2++; @endphp
                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td>
                                                                        <input type="hidden" name="payroll_deduction_id[{{$index2}}]" value="noid">
                                                                        <input type="hidden" class="deduction-{{ $index2 }}" id="deductions">
                                                                        <input type="text"  name="deduction_names[{{ $index2 }}]" id="deduction_name" class="form-control" placeholder="@lang('Allowance Name')">
                                                                    </td>

                                                                    <td>
                                                                        <select class="form-control form-control-sm" name="de_amount_types[{{ $index2 }}]" id="de_amount_type">
                                                                            <option value="1">@lang('Fixed')</option>
                                                                            <option value="2">@lang('Percentage')</option>
                                                                        </select>

                                                                        <div class="input-group deduction_percent_field d-none">
                                                                            <input type="number" step="any" name="deduction_percents[{{ $index2 }}]" class="form-control" autocomplete="off" value="" id="deduction_percent">

                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text" id="basic-addon1">
                                                                                    <i class="fas fa-percentage input_i"></i>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <input type="number" step="any" name="deduction_amounts[{{ $index2 }}]" class="form-control" id="deduction_amount" placeholder="@lang('Amount')" value="0.00">
                                                                    </td>

                                                                    <td class="text-right">
                                                                        <a href="#" id="remove_deduction" class="btn btn-sm btn-danger mt-1">X</a>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th class="text-navy-blue" colspan="2">@lang('Total')</th>
                                                                <th class="text-navy-blue" colspan="2">
                                                                    $ <span class="span_total_deduction_amount">
                                                                        {{ $payroll->total_deduction_amount }}
                                                                    </span>
                                                                    <input name="total_deduction_amount" type="hidden" id="total_deduction_amount" value="{{ $payroll->total_deduction_amount }}">
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6 class="float-end">
                                                    @lang('Gross Amount') : <span class="span_gross_amount"><b>{{ $payroll->gross_amount }}</b></span>
                                                    <input type="hidden" name="gross_amount" id="gross_amount" {{ $payroll->gross_amount }}>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="submit-area py-3 mb-4">
                                    <button type="button" class="btn loading_button d-none"><i
                                            class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                    <button class="btn btn-sm btn-success submit_button float-end">@lang('Generate')</button>
                                </div>
                            </div>
                        </div>
                </section>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    function calculateAmount() {
        var totalHours = $('#duration_time').val() ? $('#duration_time').val() : 0;
        var amount_per_unit = $('#amount_per_unit').val() ? $('#amount_per_unit').val() : 0;
        var calcTotalAmount = parseFloat(totalHours) * parseFloat(amount_per_unit);
        $('#total_amount').val(parseFloat(calcTotalAmount).toFixed(2));

        var allowances = document.querySelectorAll('#allowances');
        allowances.forEach(function (allowances) {
            var className = allowances.getAttribute('class');
            var closestTr = $('.'+className).closest('tr');
            var al_amount_type = closestTr.find('#al_amount_type').val();
            if (al_amount_type == 2) {
                var allowance_percent = closestTr.find('#allowance_percent').val() ? closestTr.find('#allowance_percent').val() : 0;
                var calAllowanceAmount = (parseFloat(calcTotalAmount) / 100) * parseFloat(allowance_percent);
                closestTr.find('#allowance_amount').val(parseFloat(calAllowanceAmount).toFixed(2));
            }
        });

        var deductions = document.querySelectorAll('#deductions');
        deductions.forEach(function (deduction) {
            var className = deduction.getAttribute('class');
            var closestTr = $('.'+className).closest('tr');
            var de_amount_type = closestTr.find('#de_amount_type').val();
            if (de_amount_type == 2) {
                var deduction_percent = closestTr.find('#deduction_percent').val() ? closestTr.find('#deduction_percent').val() : 0;
                var calDeductionAmount = (parseFloat(calcTotalAmount) / 100) * parseFloat(deduction_percent);
                closestTr.find('#deduction_amount').val(parseFloat(calDeductionAmount).toFixed(2));
            }
        });

        var allowanceAmounts = document.querySelectorAll('#allowance_amount');
        var totalAllowanceAmount = 0;
        allowanceAmounts.forEach(function(allowanceAmount){
            totalAllowanceAmount += parseFloat(allowanceAmount.value ? allowanceAmount.value : 0);
        });

        $('.span_total_allowance_amount').html(parseFloat(totalAllowanceAmount).toFixed(2));
        $('#total_allowance_amount').val(parseFloat(totalAllowanceAmount).toFixed(2));

        var deductionAmounts = document.querySelectorAll('#deduction_amount');
        var totalDeductionAmount = 0;
        deductionAmounts.forEach(function(deductionAmount){
            totalDeductionAmount += parseFloat(deductionAmount.value ? deductionAmount.value : 0);
        });

        $('.span_total_deduction_amount').html(parseFloat(totalDeductionAmount).toFixed(2));
        $('#total_deduction_amount').val(parseFloat(totalDeductionAmount).toFixed(2));

        // Calc Gross amount
        var gross_amount = parseFloat(calcTotalAmount) + parseFloat(totalAllowanceAmount) - parseFloat(totalDeductionAmount);
        $('.span_gross_amount').html(parseFloat(gross_amount).toFixed(2));
        $('#gross_amount').val(parseFloat(gross_amount).toFixed(2));
    }
    calculateAmount();

    $(document).on('input', '#duration_time', function () {
        calculateAmount();
    });

    $(document).on('input', '#amount_per_unit', function () {
        calculateAmount();
    });

    $(document).on('input', '#deduction_percent', function () {
        calculateAmount();
    });

    $(document).on('input', '#allowance_percent', function () {
        calculateAmount();
    });

    $(document).on('input', '#allowance_amount', function () {
        calculateAmount();
    });

    $(document).on('input', '#deduction_amount', function () {
        calculateAmount();
    });

    // Add More Allowance
    var index = {{$index}};
    $(document).on('click', '#add_more_allowance', function (e) {
        e.preventDefault();
        var html = '';
        html += '<tr>';
        html += '<td>';
        html += '<input type="hidden" name="payroll_allowance_id['+(index+1)+']" value="noid">';
        html += '<input type="hidden" class="allowance-'+(index+1)+'" id="allowances">';
        html += '<input type="text" name="allowance_names['+(index+1)+']" class="form-control" id="allowance_name" placeholder="@lang('Allowance Name')">';
        html += '</td>';

        html += '<td>';
        html += '<select class="form-control" name="al_amount_types['+(index+1)+']" id="al_amount_type">';
        html += '<option value="1">@lang('Fixed')</option>';
        html += '<option value="2">@lang('Percentage')</option>';
        html += '</select>';

        html += '<div class="input-group allowance_percent_field d-none">';
        html += '<input type="number" step="any" name="allowance_percents['+(index+1)+']" class="form-control" autocomplete="off" value="0.00" id="allowance_percent">';
        html += '<div class="input-group-prepend">';
        html += ' <span class="input-group-text" id="basic-addon1">';
        html += '<i class="fas fa-percentage input_i"></i>';
        html += '</span>';
        html += '</div>';
        html += '</div>';
        html += '</td>';

        html += '<td>';
        html += '<input type="number" step="any" name="allowance_amounts['+(index+1)+']" class="form-control" id="allowance_amount" placeholder="@lang('Amount')" value="0.00">';
        html += '</td>';

        html += '<td class="text-right">';
        html += '<a href="#" id="remove_allowane" class="btn btn-sm btn-danger mt-1">X</a>';
        html += '</td>';
        html += '</tr>';
        $('#allowance_body').append(html);
        index++;
    });

    // Add More Deduction
    var index2 = {{$index2}};
    $(document).on('click', '#add_more_deduction', function (e) {
        e.preventDefault();
        var html = '';
        html += '<tr>';
        html += '<td>';
            html += '<input type="hidden" name="payroll_deduction_id['+(index2+1)+']" value="noid">';
        html += '<input type="hidden" class="deduction-'+(index2+1)+'" id="deductions">';
        html += '<input type="text"  name="deduction_names['+(index2+1)+']" id="deduction_name" class="form-control" placeholder="@lang('Deduction Name')">';
        html += '</td>';

        html += '<td>';
        html += '<select class="form-control" name="de_amount_types['+(index2+1)+']" id="de_amount_type">';
        html += '<option value="1">@lang('Fixed')</option>';
        html += '<option value="2">@lang('Percentage')</option>';
        html += '</select>';

        html += '<div class="input-group deduction_percent_field d-none">';

        html += '<input type="number" step="any" name="deduction_percents['+(index2+1)+']" class="form-control" autocomplete="off" value="0.00" id="deduction_percent">';

        html += '<div class="input-group-prepend">';
        html += '<span class="input-group-text" id="basic-addon1">';
        html += '<i class="fas fa-percentage input_i"></i>';
        html += '</span>';
        html += '</div>';
        html += '</div>';
        html += '</td>';

        html += '<td>';
        html += '<input type="number" step="any" name="deduction_amounts['+(index2+1)+']" class="form-control" id="deduction_amount" placeholder="@lang('Amount')" value="0.00">';
        html += '</td>';

        html += '<td class="text-right">';
        html += '<a href="#" id="remove_deduction" class="btn btn-sm btn-danger mt-1">X</a>';
        html += '</td>';
        html += '</tr>';
        $('#deduction_body').append(html);
        index2++;
    });

    // Remove Allowance
    $(document).on('click', '#remove_allowane', function (e) {
        e.preventDefault();
        $(this).closest('tr').remove();
        calculateAmount();
    });

    // Remove Deduction
    $(document).on('click', '#remove_deduction', function (e) {
        e.preventDefault();
        $(this).closest('tr').remove();
        calculateAmount();
    });

    $(document).on('click', '#al_amount_type', function () {
        //calculateAmount();
        if ($(this).val() == 2) {
            $(this).closest('tr').find('.allowance_percent_field').removeClass('d-none');
            $(this).closest('tr').find('#allowance_amount').prop('readonly', true);
        }else {
            $(this).closest('tr').find('.allowance_percent_field').addClass('d-none');
            $(this).closest('tr').find('#allowance_amount').prop('readonly', false);
        }
    });

    $(document).on('click', '#de_amount_type', function () {
        //calculateAmount();
        if ($(this).val() == 2) {
            $(this).closest('tr').find('.deduction_percent_field').removeClass('d-none');
            $(this).closest('tr').find('#deduction_amount').prop('readonly', true);
        }else {
            $(this).closest('tr').find('.deduction_percent_field').addClass('d-none');
            $(this).closest('tr').find('#deduction_amount').prop('readonly', false);
        }
    });


    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Add product by ajax
    $('#update_payroll_form').on('submit', function(e) {
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
                if ($.isEmptyObject(data.errorMsg)) {
                    toastr.success(data);
                    window.location = "{{ route('hrm.payroll.index') }}";
                } else {
                    toastr.error(data.errorMsg);
                    $('.error').html('');
                    $('.form-control').removeClass('is-invalid');
                }
            },
            error: function(err) {
                $('.loading_button').hide();
                toastr.error("@lang('Please check again all form fields.')",
                    "@lang('Something went wrong.')");
                $('.error').html('');
                $('.form-control').removeClass('is-invalid');
                $.each(err.responseJSON.errors, function(key, error) {
                    //console.log(key);
                    $('.error_' + key + '').html(error[0]);
                    $('#' + key).addClass('is-invalid');
                });
            }
        });
    });
</script>
@endpush
