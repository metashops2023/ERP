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
        <form id="add_user_form" action="{{ route('contacts.customers.import.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <section class="mt-5">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form_element m-0 mt-4">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>@lang('Import Customers') </h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label for="inputEmail3" class="col-4"><b>@lang('File To Import') :</b> </label>
                                                <div class="col-10">
                                                    <input type="file" name="import_file" class="form-control">
                                                    <span class="error" style="color: red;">
                                                        {{ $errors->first('import_file') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label for="add_branch_id" class="col-4"><b>@lang('Business Name') :</b> <span class="text-danger">*</span> </label>
                                                <div class="col-10">
                                                    <select name="add_branch_id" id="add_branch_id" class="form-control">
                                                        @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}">
                                                            {{ $branch->name . '/' . $branch->branch_code }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <!-- <input type="text" name="business_name" class="form-control" placeholder="@lang('Business name')" /> -->
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
                                                    <a href="{{ asset('import_template/customer_import_template.xlsx') }}" class="btn btn-sm btn-success" download>@lang('Download Template File, Click Here')</a>
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
                                    <div class="heading">
                                        <h4>@lang('Instructions')</h4>
                                    </div>
                                    <div class="top_note">
                                        <p class="p-0 m-0"><b>@lang('Follow the instructions carefully before importing the file').</b></p>
                                        <p>@lang('The columns of the file should be in the following order').</p>
                                    </div>

                                    <div class="instruction_table">
                                        <table class="table table-sm modal-table table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">@lang('Column Number')</th>
                                                    <th class="text-start">@lang('Column Name')</th>
                                                    <th class="text-start">@lang('Instruction')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-start">1</td>
                                                    <td class="text-start"> @lang('Customer ID') </td>
                                                    <td class="text-start"> @lang('Optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">2</td>
                                                    <td class="text-start"> @lang('Business Name') </td>
                                                    <td class="text-start text-danger">@lang('Required')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">3</td>
                                                    <td class="text-start"> @lang('Name')</td>
                                                    <td class="text-start text-danger"> @lang('Required')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">4</td>
                                                    <td class="text-start"> @lang('Phone') </td>
                                                    <td class="text-start text-danger"> <b>@lang('Required')</b> <br>
                                                        (<small>@lang('Must be unique').</small>)</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">5</td>
                                                    <td class="text-start"> @lang('Alternative Number')</td>
                                                    <td class="text-start">@lang('Optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">6</td>
                                                    <td class="text-start">@lang('Landline')</td>
                                                    <td class="text-start"> @lang('Optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">7</td>
                                                    <td class="text-start">@lang('Email')</td>
                                                    <td class="text-start">@lang('Optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">8</td>
                                                    <td class="text-start">@lang('Date Of Birth')</td>
                                                    <td class="text-start">@lang('Optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">9</td>
                                                    <td class="text-start">@lang('Tax Number')</td>
                                                    <td class="text-start">@lang('Optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">10</td>
                                                    <td class="text-start">@lang('Opening Balance') </td>
                                                    <td class="text-start">@lang('Optional') <br>
                                                        (<small>@lang('Opening Balance will be added in customer balance due').</small>)</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">12</td>
                                                    <td class="text-start">@lang('Address')</td>
                                                    <td class="text-start">@lang('Optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">13</td>
                                                    <td class="text-start">@lang('City')</td>
                                                    <td class="text-start">@lang('Optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">13</td>
                                                    <td class="text-start">@lang('State')</td>
                                                    <td class="text-start">@lang('Optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">14</td>
                                                    <td class="text-start">@lang('Country')</td>
                                                    <td class="text-start">@lang('Optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">15</td>
                                                    <td class="text-start">@lang('Zip-Code')</td>
                                                    <td class="text-start">@lang('Optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">16</td>
                                                    <td class="text-start">@lang('Shipping Address')</td>
                                                    <td class="text-start">@lang('Optional')</td>
                                                </tr>


                                                <tr>
                                                    <td class="text-start">17</td>
                                                    <td class="text-start">@lang('Pay term Number')</td>
                                                    <td class="text-start">@lang('Optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">17</td>
                                                    <td class="text-start">@lang('Pay term')</td>
                                                    <td class="text-start">@lang('Optional (If exists 1=Day,2=Month)')</td>
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
