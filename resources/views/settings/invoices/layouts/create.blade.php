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
            <form id="add_layout_form" action="{{ route('invoices.layouts.store') }}" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form_element m-0 mt-4">
                                    <div class="py-2 px-2 form-header">
                                        <div class="row">
                                            <div class="col-8"><h5>@lang('Add Invoice Layout')</h5> </div>
                                        </div>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-3"><span class="text-danger">*</span> <b>@lang('Name') :</b> </label>
                                                    <div class="col-9">
                                                        <input type="text" name="name" class="form-control" placeholder="@lang('Layout Name')" autofocus>
                                                        <span class="error error_name"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-3"><b>@lang('Design') :</b></label>

                                                    <div class="col-9">
                                                        <select name="design" id="design" class="form-control">
                                                            <option value="1">@lang('Classic (For normal printer)')</option>
                                                            <option value="2">@lang('Slim (For POS printer)')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap bordered">
                                                            <input type="checkbox" checked name="show_shop_logo"> &nbsp; Show Business/Shop Logo</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap">
                                                            <input type="checkbox" checked name="show_seller_info"> &nbsp; Show Seller Info</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap">
                                                            <input type="checkbox" checked name="show_total_in_word"> &nbsp; Show Total In Word</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>@lang('Header Option')</b></p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" name="is_header_less" id="is_header_less"> &nbsp;<b>@lang('Is Headerless') ?</b> <i data-bs-toggle="tooltip" data-bs-placement="top" title="If you check this option then print header info will not come in the print preview. Use case, When the print page is pre-generated Like Pad.Where header info previously exists." class="fas fa-info-circle tp"></i>
                                                </p>
                                            </div>

                                            <div class="col-md-9 hideable_field d-none">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><span class="text-danger">*</span>  <b>@lang('Gap From Top (inc)') : </b> </label>
                                                    <div class="col-8">
                                                        <input type="number" name="gap_from_top" id="gap_from_top" class="form-control" placeholder="@lang('Gap From Top')">
                                                        <span class="error error_gap_from_top"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Sub Heading') 1 : </b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="sub_heading_1" id="sub_heading_1" class="form-control" placeholder="@lang('Sub Heading Line 1')">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Sub Heading') 2 : </b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="sub_heading_2" id="sub_heading_2" class="form-control" placeholder="@lang('Sub Heading Line 2')">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-2"><b>@lang('Header Text') : </b> </label>
                                                    <div class="col-10">
                                                        <input type="text" name="header_text" class="form-control form-control-sm"  placeholder="@lang('Header text')">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>@lang('Invoice Heading')</b></p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-5"><span class="text-danger">*</span> <b>@lang('Invoice Heading') :</b> </label>
                                                    <div class="col-7">
                                                        <input type="text" name="invoice_heading" class="form-control" id="invoice_heading" placeholder="@lang('Invoice Heading')">
                                                        <span class="error error_invoice_heading"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-5"><span class="text-danger">*</span> <b>@lang('Quotation Heading') :</b> </label>
                                                    <div class="col-7">
                                                        <input type="text" name="quotation_heading" id="quotation_heading" class="form-control" placeholder="@lang('Quotation Heading')">
                                                        <span class="error error_quotation_heading"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-5"><span class="text-danger">*</span> <b>@lang('Draft Heading') : </b> </label>
                                                    <div class="col-7">
                                                        <input type="text" name="draft_heading" id="draft_heading" class="form-control" placeholder="@lang('Draft Heading')">
                                                        <span class="error error_draft_heading"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-5"><span class="text-danger">*</span> <b>@lang('Challan Heading') : </b> </label>
                                                    <div class="col-7">
                                                        <input type="text" name="challan_heading" id="challan_heading" class="form-control" placeholder="@lang('Challan Heading')">
                                                        <span class="error error_challan_heading"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>@lang('Field For Branch')</b></p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap">
                                                        <input type="checkbox" name="branch_landmark" > &nbsp; <b>@lang('Landmark')</b> </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" checked name="branch_city"> &nbsp;<b>@lang('City')</b>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                    <input type="checkbox" checked name="branch_state"> &nbsp; <b>@lang('State')</b></p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                    <input type="checkbox" checked name="branch_zipcode"> &nbsp; <b>@lang('Zip-Code')</b></p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                    <input type="checkbox" checked name="branch_phone"> &nbsp; <b>@lang('Phone')</b></p>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                    <input type="checkbox" checked name="branch_alternate_number"> &nbsp; <b>@lang('Alternative Number')</b></p>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                    <input type="checkbox" checked name="branch_email"> &nbsp; <b>@lang('Email')</b></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>@lang('Field For Customer')</b></p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap">
                                                        <input type="checkbox" checked name="customer_name"> &nbsp;<b>@lang('Name')</b></p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap ">
                                                    <input type="checkbox" checked name="customer_tax_no"> &nbsp; <b>@lang('Tax Number')</b></p>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                    <input type="checkbox" checked name="customer_address"> &nbsp;<b>@lang('Address')</b> </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                    <input type="checkbox" checked name="customer_phone"> &nbsp;<b>@lang('Phone')</b></p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>@lang('Field For Product')</b></p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" checked name="product_w_type"> &nbsp;<b>@lang('Product Warranty Type')</b></p>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                    <input type="checkbox" checked name="product_w_duration"> &nbsp; <b>@lang('Product Warranty Duration')</b></p>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="row">
                                                   <p class="checkbox_input_wrap">
                                                    <input type="checkbox" checked name="product_discount"> &nbsp; <b>@lang('Product Discount')</b></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                    <input type="checkbox" checked name="product_tax" > &nbsp; <b>@lang('Product Tax')</b></p>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap ">
                                                    <input type="checkbox" name="product_imei"><b>&nbsp; Show sale description</b></p>
                                                </div>
                                                <small class="text-muted">(Product IMEI or Serial Number)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>@lang('Bank Details')</b></p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Account No') :</b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="account_no" class="form-control" placeholder="@lang('Account Number')">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Account Name'):</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="account_name" class="form-control" placeholder="@lang('Account Name')">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Bank Name') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="bank_name" class="form-control" placeholder="@lang('Bank Name')">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Bank Branch') :</b></label>

                                                    <div class="col-8">
                                                        <input type="text" name="bank_branch" class="form-control" placeholder="@lang('Bank Branch')">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>@lang('Footer Text')</b></p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Invoice Notice') :</b></label>
                                                    <div class="col-8">
                                                        <textarea name="invoice_notice" class="form-control" cols="10" rows="3" placeholder="@lang('Invoice Notice')"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Footer Text') :</b> </label>
                                                    <div class="col-8">
                                                        <textarea name="footer_text" class="form-control" cols="10" rows="3" placeholder="@lang('Footer text')"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="submit-area py-3 mb-4">
                                    <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> @lang('Loading')...</b></button>
                                    <button class="btn btn-sm btn-success submit_button float-end">@lang('Save')</button>
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
    // Add Invoice layout by ajax
    $(document).on('submit', '#add_layout_form', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                toastr.success(data);
                $('.loading_button').hide();
                 window.location = "{{ route('invoices.layouts.index') }}";
            },
            error: function(err) {
                $('.loading_button').hide();
                toastr.error("@lang('Please check again all form fields.')",
                    "@lang('Something went wrong.')");
                $('.error').html('');
                $.each(err.responseJSON.errors, function(key, error) {
                    //console.log(key);
                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    $('#is_header_less').on('change', function () {
       if ($(this).is(':CHECKED', true)) {
           $('.hideable_field').show();
       } else{
        $('.hideable_field').hide();
       }
    });
</script>
@endpush
