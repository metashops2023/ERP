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
            <form id="add_user_form" action="{{ route('product.import.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form_element m-0 mt-4">
                                    <div class="py-2 px-2 form-header">
                                        <div class="row">
                                            <div class="col-6"><h5>@lang('Import Products') </h5></div>
                                        </div>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('File To Import') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="file" name="import_file" class="form-control">
                                                        <span class="error" style="color: red;">
                                                            {{ $errors->first('import_file') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <div class="col-8">
                                                        <button class="btn btn-sm btn-primary float-start mt-1">@lang('Upload')</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('Download Simple') :</b> </label>
                                                    <div class="col-8">
                                                        <a href="{{ asset('import_template/product_import_template.csv') }}" class="btn btn-sm btn-success" download>@lang('Download Template File, Click Here')</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="element-body">
                                        <div class="heading"><h4>@lang('Instructions')</h4></div>
                                        <div class="top_note">
                                            <p class="p-0 m-0"><b>@lang('Follow the instructions carefully before importing the file').</b></p>
                                            <p>@lang('The columns of the file should be in the following order').</p>
                                        </div>

                                        <div class="instruction_table">
                                            <table class="table table-sm modal-table table-striped">
                                                <thead>
                                                    <tr >
                                                        <th class="text-start">@lang('Column Number')</th>
                                                        <th class="text-start">@lang('Column Name')</th>
                                                        <th class="text-start">@lang('Instruction')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-start">1</td>
                                                        <td class="text-start"> @lang('Product Name (Required)')</td>
                                                        <td class="text-start"> @lang('Name of the product')</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">2</td>
                                                        <td class="text-start"> @lang('Product code(SKU) (Optional)')</td>
                                                        <td class="text-start">@lang('Product code(SKU). If blank an SKU will be automatically generated')</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">3</td>
                                                        <td class="text-start"> @lang('Unit (Required)')</td>
                                                        <td class="text-start"> @lang('Name of the unit')</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">4</td>
                                                        <td class="text-start"> @lang('Category (Required)')</td>
                                                        <td class="text-start"> <b>@lang('Name of the Category')</b> <br>
                                                            (<small>@lang('If not found new category with the given name will be created')</small>)</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">5</td>
                                                        <td class="text-start"> @lang('Child category (Optional)')</td>
                                                        <td class="text-start"> <b>@lang('Name of the Sub-Category. If not found new sub-category with the given name under the parent Category will be created')</b></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">6</td>
                                                        <td class="text-start">@lang('Brand (Optional)')</td>
                                                        <td class="text-start"> <b>@lang('Name of the brand')</b> <br>
                                                            (<small>@lang('If not found new brand with the given name will be created')</small>)</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">7</td>
                                                        <td class="text-start">@lang('Barcode Type (Optional, Default: C128)')</td>
                                                        <td class="text-start"> @lang('Barcode Type for the product'). <br>
                                                            (<span><b>@lang('Currently supported'): C128, C39, EAN-13, EAN-8, UPC-A, UPC-E, ITF-14</b> </span>)</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">8</td>
                                                        <td class="text-start">@lang('Alert quantity (Optional)')</td>
                                                        <td class="text-start"> @lang('Alert quantity')</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">9</td>
                                                        <td class="text-start">@lang('Expiry Date (Optional)')</td>
                                                        <td class="text-start">@lang('Stock Expiry Date') <br>
                                                            (<span><b>@lang('Format'): mm-dd-yyyy; Ex: 11-25-2018</b> </span>)
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">10</td>
                                                        <td class="text-start">@lang('Warranty')</td>
                                                        <td class="text-start">@lang('Name of the Warranty') </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">11</td>
                                                        <td class="text-start">@lang('Description (Optional)')</td>
                                                        <td class="text-start">@lang('Description of product') </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">12</td>
                                                        <td class="text-start">@lang('Tax (Optional)')</td>
                                                        <td class="text-start">@lang('Only in numbers')</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">13</td>
                                                        <td class="text-start">@lang('UNIT COST Excluding Tax (Required)')</td>
                                                        <td class="text-start">@lang('Only in numbers')</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">13</td>
                                                        <td class="text-start">@lang('UNIT COST Including Tax (Optional)')</td>
                                                        <td class="text-start">@lang('Only in numbers')</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">14</td>
                                                        <td class="text-start">@lang('Profit Margin') % @lang('Optional')</td>
                                                        <td class="text-start">
                                                            @lang('Profit Margin') (@lang('Only in numbers'))
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">15</td>
                                                        <td class="text-start">@lang('Opening Stock (Only in numbers)')</td>
                                                        <td class="text-start">
                                                            @lang('Selling Price') (@lang('Only in numbers'))
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">16</td>
                                                        <td class="text-start">@lang('Opening stock Branch (Optional)') <br>
                                                            (<small>@lang('If blank first Branch will be used')</small>)  </td>
                                                        <td class="text-start">
                                                            @lang('Only Branch Code')
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </section>
            </form>
        </div>
    </div>
@endsection

