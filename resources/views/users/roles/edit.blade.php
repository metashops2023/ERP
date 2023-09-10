@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
        p.checkbox_input_wrap {font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="edit_role_form" action="{{ route('users.role.update', $role->id) }}"  method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form_element m-0 mt-4">
                                    <div class="section-header">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5>@lang('Edit Role')</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-3"><span
                                                        class="text-danger">*</span> <strong>@lang('Role Name') :</strong> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="role_name" class="form-control add_input" id="role_name"
                                                            placeholder="@lang('Role Name')" value="{{ $role->name }}">
                                                        <span class="error error_role_name">{{ $errors->first('role_name') }}</span>
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
                                        <p class="p-1 text-primary"><strong>@lang('Users Permissions')</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap ">
                                                        <input type="checkbox" id="select_all" data-target="users"> &nbsp; Select All
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4 offset-1">
                                                <div class="col-md-12">
                                                    <p><strong>@lang('Users')</strong></p>
                                                </div>
                                                
                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->permission->user['user_view'] == '1' ? 'CHECKED' : '' }} name="user_view" class="users"> &nbsp; View
                                                        User
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->permission->user['user_add'] == '1' ? 'CHECKED' : '' }} name="user_add" class="users"> &nbsp; Add User
                                                    </p>

                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->permission->user['user_edit'] == '1' ? 'CHECKED' : '' }} name="user_edit" class="users"> &nbsp; Edit User
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->permission->user['user_delete'] == '1' ? 'CHECKED' : '' }} name="user_delete" class="users"> &nbsp; Delete User
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-5">
                                                <div class="col-md-12">
                                                    <p><strong>@lang('Roles')</strong></p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->permission->user['role_view'] == '1' ? 'CHECKED' : '' }}  name="role_view" class="users">
                                                        &nbsp; View Role
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" {{ $role->permission->user['role_add'] == '1' ? 'CHECKED' : '' }} name="role_add" class="users">
                                                        &nbsp; Add Role
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" {{ $role->permission->user['role_edit'] == '1' ? 'CHECKED' : '' }} name="role_edit" class="users">
                                                        &nbsp; Edit Role
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" {{ $role->permission->user['role_delete'] == '1' ? 'CHECKED' : '' }} name="role_delete"
                                                            class="users"> &nbsp; Delete Role
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><strong>@lang('Contacts Permissions')</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap "> 
                                                    <input type="checkbox" id="select_all" data-target="contacts"> &nbsp;  Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-4 offset-1">
                                                <div class="col-md-12">
                                                    <p><strong>@lang('Suppliers')</strong></p>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->contact['supplier_all'] == '1' ? 'CHECKED' : '' }} name="supplier_all" class="contacts"> &nbsp; View All Supplier </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->contact['supplier_add'] == '1' ? 'CHECKED' : '' }} name="supplier_add" class="contacts"> &nbsp; Add Supplier </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->contact['supplier_import'] == '1' ? 'CHECKED' : '' }} name="supplier_import" class="contacts"> &nbsp; Import Supplier </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->contact['supplier_edit'] == '1' ? 'CHECKED' : '' }} name="supplier_edit" class="contacts"> &nbsp; Edit Supplier </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->contact['supplier_delete'] == '1' ? 'CHECKED' : '' }} name="supplier_delete" class="contacts"> &nbsp; Delete Supplier </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" 
                                                            @if (isset($role->permission->contact['supplier_report']))
                                                                {{ $role->permission->contact['supplier_report'] == '1' ? 'CHECKED' : '' }}
                                                            @endif
                                                            name="supplier_report" class="report"> &nbsp; Supplier Report</p> 
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-5">

                                                <div class="col-md-12">
                                                    <p><strong>@lang('Customers')</strong></p>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row"><p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->contact['customer_all'] == '1' ? 'CHECKED' : '' }} name="customer_all" class="contacts"> &nbsp; View All Customer </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->contact['customer_add'] == '1' ? 'CHECKED' : '' }} name="customer_add" class="contacts"> &nbsp; Add Customer </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->contact['customer_import'] == '1' ? 'CHECKED' : '' }} name="customer_import" class="contacts"> &nbsp; Import Customer </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->contact['customer_edit'] == '1' ? 'CHECKED' : '' }} name="customer_edit" class=" contacts"> &nbsp; Edit Customer </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row"><p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->contact['customer_delete'] == '1' ? 'CHECKED' : '' }} name="customer_delete" class="contacts"> &nbsp; Delete Customer </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row"><p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->contact['customer_group'] == '1' ? 'CHECKED' : '' }} name="customer_group" class="contacts"> &nbsp; Customer Group -> View/Add/Edit/Delete</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                            <input type="checkbox"
                                                                @if (isset($role->permission->contact['customer_report']))
                                                                    {{ $role->permission->contact['customer_report'] == '1' ? 'CHECKED' : '' }}
                                                                @endif 
                                                            name="customer_report" class="report"> &nbsp; Customer Report</p> 
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
                                        <p class="p-1 text-primary"><strong>@lang('Product Permissions')</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap "> 
                                                    <input type="checkbox" id="select_all" data-target="product" autocomplete="off"> &nbsp;  Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-4 offset-1">
                                                <div class="col-md-12">
                                                    <p><strong>@lang('Products')</strong></p>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->product['product_all'] == '1' ? 'CHECKED' : '' }} name="product_all" class="product"> &nbsp; View All Product </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->product['product_add'] == '1' ? 'CHECKED' : '' }} name="product_add" class="product"> &nbsp; Add Product </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->product['product_edit'] == '1' ? 'CHECKED' : '' }} name="product_edit" class="product"> &nbsp; Edit Product </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->product['openingStock_add'] == '1' ? 'CHECKED' : '' }} name="openingStock_add" class="product"> &nbsp; Add Opening Stock </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->product['product_delete'] == '1' ? 'CHECKED' : '' }} name="product_delete" class="product"> &nbsp; Delete Product </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" 
                                                            @if (isset($role->permission->product['product_settings']))
                                                                {{ $role->permission->product['product_settings'] == '1' ? 'CHECKED' : '' }}
                                                            @endif
                                                        name="product_settings" class="product"> &nbsp; Product Settings</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" 
                                                            @if (isset($role->permission->product['stock_report']))
                                                                {{ $role->permission->product['stock_report'] == '1' ? 'CHECKED' : '' }}
                                                            @endif
                                                         name="stock_report" class="report"> &nbsp; stock Report</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" 
                                                            @if (isset($role->permission->product['stock_in_out_report']))
                                                                {{ $role->permission->product['stock_in_out_report'] == '1' ? 'CHECKED' : '' }}
                                                            @endif
                                                        name="stock_in_out_report" class="product"> &nbsp; Stock In-Out Report</p> 
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-5">
                                                <div class="col-md-12">
                                                    <p><strong>@lang('Others')</strong></p>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->product['categories'] == '1' ? 'CHECKED' : '' }} name="categories" class="product"> &nbsp; Categories</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->product['brand'] == '1' ? 'CHECKED' : '' }} name="brand" class="product"> &nbsp; Brands</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->product['units'] == '1' ? 'CHECKED' : '' }} name="units" class="product"> &nbsp; Unit</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->product['variant'] == '1' ? 'CHECKED' : '' }} name="variant" class="product"> &nbsp; Variants</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->product['warranties'] == '1' ? 'CHECKED' : '' }} name="warranties" class="product"> &nbsp; Warranties</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->product['selling_price_group'] == '1' ? 'CHECKED' : '' }} name="selling_price_group" class="product"> &nbsp; Selling Price Group</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->product['generate_barcode'] == '1' ? 'CHECKED' : '' }}  name="generate_barcode" class="product"> &nbsp; Generate Barcode</p> 
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
                                        <p class="p-1 text-primary"><strong>@lang('Purchase Permission')</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap "> 
                                                    <input type="checkbox" id="select_all" data-target="purchase" autocomplete="off"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-4 offset-1">

                                                <div class="col-md-12">
                                                    <p><strong>@lang('Purchases')</strong></p>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->purchase['purchase_all'] == '1' ? 'CHECKED' : '' }} name="purchase_all" class="purchase"> &nbsp; View All Purchase </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->purchase['purchase_add'] == '1' ? 'CHECKED' : '' }} name="purchase_add" class="purchase"> &nbsp; Add Purchase </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->purchase['purchase_edit'] == '1' ? 'CHECKED' : '' }} name="purchase_edit" class="purchase"> &nbsp; Edit Purchase </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->purchase['purchase_delete'] == '1' ? 'CHECKED' : '' }} name="purchase_delete" class="purchase" > &nbsp; Delete Purchase </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->purchase['status_update'] == '1' ? 'CHECKED' : '' }} name="status_update" class="purchase"> &nbsp; Update Status </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" 
                                                            @if (isset($role->permission->purchase['purchase_settings']))
                                                                {{ $role->permission->purchase['purchase_settings'] == '1' ? 'CHECKED' : '' }}
                                                            @endif
                                                        name="purchase_settings" class="purchase"> &nbsp; Purchase Settings </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox"
                                                            @if (isset($role->permission->purchase['purchase_statements']))
                                                                {{ $role->permission->purchase['purchase_statements'] == '1' ? 'CHECKED' : '' }}
                                                            @endif
                                                         name="purchase_statements" class="purchase"> &nbsp; Purchase Statements</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" 
                                                            @if (isset($role->permission->purchase['purchase_sale_report']))
                                                                {{ $role->permission->purchase['purchase_sale_report'] == '1' ? 'CHECKED' : '' }}
                                                            @endif
                                                        name="purchase_sale_report" class="purchase"> &nbsp; Purchase & Sale Report</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox"
                                                            @if (isset($role->permission->purchase['pro_purchase_report']))
                                                                {{ $role->permission->purchase['pro_purchase_report'] == '1' ? 'CHECKED' : '' }}
                                                            @endif
                                                         name="pro_purchase_report" class="purchase"> &nbsp; Product Purchase Report</p> 
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-5">
                                                <div class="col-md-12">
                                                    <p><strong>@lang('Others')</strong></p>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->purchase['purchase_payment'] == '1' ? 'CHECKED' : '' }} name="purchase_payment" class="purchase"> &nbsp; View/Add/Delete Purchase Payment </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->purchase['purchase_return'] == '1' ? 'CHECKED' : '' }} name="purchase_return" class="purchase"> &nbsp; Access Purchase Return </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox"
                                                            @if (isset($role->permission->purchase['purchase_payment_report']))
                                                                {{ $role->permission->purchase['purchase_payment_report'] == '1' ? 'CHECKED' : '' }}
                                                            @endif
                                                        name="purchase_payment_report" class="purchase"> &nbsp; Purchase Payment Report</p> 
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
                                        <p class="p-1 text-primary"><strong>@lang('Adjustment Permissions')</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap "> 
                                                    <input type="checkbox" id="select_all" data-target="adjustment" autocomplete="off"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-1">

                                                <div class="col-md-12">
                                                    <p><strong>@lang('Stock Adjustments')</strong></p>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->s_adjust['adjustment_all'] == '1' ? 'CHECKED' : '' }} name="adjustment_all" class="adjustment"> &nbsp; View All Adjustment </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->s_adjust['adjustment_add_from_location'] == '1' ? 'CHECKED' : '' }} name="adjustment_add_from_location" class="adjustment"> &nbsp; Add Adjustment From Business Locaton </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->s_adjust['adjustment_add_from_warehouse'] == '1' ? 'CHECKED' : '' }} name="adjustment_add_from_warehouse" class="adjustment"> &nbsp; Add Adjustment From Warehouse </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->s_adjust['adjustment_delete'] == '1' ? 'CHECKED' : '' }} name="adjustment_delete" class="adjustment" > &nbsp; Delete Adjustment </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" 
                                                            @if (isset($role->permission->s_adjust['stock_adjustment_report']))
                                                                {{ $role->permission->s_adjust['stock_adjustment_report'] == '1' ? 'CHECKED' : '' }} 
                                                            @endif
                                                        name="stock_adjustment_report" class="adjustment"> &nbsp; Stock Adjustment Report</p> 
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
                                        <p class="p-1 text-primary"><strong>@lang('Expenses Permission')</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap "> 
                                                    <input type="checkbox" id="select_all" data-target="expense" autocomplete="off"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-1">
                                                <div class="col-md-12">
                                                    <p><strong>@lang('Expenses')</strong></p>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->expense['view_expense'] == '1' ? 'CHECKED' : '' }}  name="view_expense" class="expense"> &nbsp; View Expense </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->expense['add_expense'] == '1' ? 'CHECKED' : '' }}  name="add_expense" class="expense"> &nbsp; Add Expense </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->expense['edit_expense'] == '1' ? 'CHECKED' : '' }}  name="edit_expense" class="expense"> &nbsp; Edit Expense </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->expense['delete_expense'] == '1' ? 'CHECKED' : '' }}  name="delete_expense" class="expense"> &nbsp; Delete Expense </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->expense['expense_category'] == '1' ? 'CHECKED' : '' }} name="expense_category" class="expense"> &nbsp; Expense Category -> View/Add/Edit/Delete </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->expense['category_wise_expense'] == '1' ? 'CHECKED' : '' }} name="category_wise_expense" class="expense"> &nbsp; View Category Wise Expense </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" 
                                                            @if (isset($role->permission->expense['expense_report']))
                                                                {{ $role->permission->expense['expense_report'] == '1' ? 'CHECKED' : '' }} 
                                                            @endif
                                                            name="expense_report" class="expense"> &nbsp; Expense Report</p> 
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
                                        <p class="p-1 text-primary"><strong>@lang('Sales Permission')</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap "> 
                                                    <input type="checkbox" id="select_all" data-target="sale" autocomplete="off"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-4 offset-1">
                                                <div class="col-md-12">
                                                    <p><strong>@lang('Sales')</strong></p>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->sale['create_add_sale'] == '1' ? 'CHECKED' : '' }} name="create_add_sale" class="sale"> &nbsp; Create Add Sale </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->sale['view_add_sale'] == '1' ? 'CHECKED' : '' }} name="view_add_sale" class="sale"> &nbsp; Manage Add Sale </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->sale['edit_add_sale'] == '1' ? 'CHECKED' : '' }} name="edit_add_sale" class="sale"> &nbsp; Edit Add Sale </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->sale['delete_add_sale'] == '1' ? 'CHECKED' : '' }} name="delete_add_sale" class="sale"> &nbsp; Delete Add Sale </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" 
                                                            @if (isset($role->permission->sale['add_sale_settings']))
                                                                {{ $role->permission->sale['add_sale_settings'] == '1' ? 'CHECKED' : '' }} 
                                                            @endif
                                                        name="add_sale_settings" class="sale"> &nbsp; Add Sale Settings </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->sale['sale_draft'] == '1' ? 'CHECKED' : '' }} name="sale_draft" class="sale"> &nbsp; List Draft </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->sale['sale_quotation'] == '1' ? 'CHECKED' : '' }} name="sale_quotation" class="sale"> &nbsp; List Quotations </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->sale['sale_payment'] == '1' ? 'CHECKED' : '' }} name="sale_payment" class="sale"> &nbsp; View/Add/Edit Payment </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->sale['edit_price_sale_screen'] == '1' ? 'CHECKED' : '' }} name="edit_price_sale_screen" class="sale"> &nbsp; Edit product price from sales screen </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->sale['edit_discount_sale_screen'] == '1' ? 'CHECKED' : '' }} name="edit_discount_sale_screen" class="sale"> &nbsp; Edit Product Discount In Sale Scr. </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->sale['shipment_access'] == '1' ? 'CHECKED' : '' }}  name="shipment_access" class="sale"> &nbsp; Access Shipments </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->sale['view_product_cost_is_sale_screed'] == '1' ? 'CHECKED' : '' }} name="view_product_cost_is_sale_screed" class="sale"> &nbsp; View Product Cost is sale screen </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->sale['view_own_sale'] == '1' ? 'CHECKED' : '' }} name="view_own_sale" class="sale"> &nbsp; View only own Add/POS Sale </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->sale['return_access'] == '1' ? 'CHECKED' : '' }} name="return_access" class="sale"> &nbsp; Access Sale Return </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input  
                                                        @if (isset($role->permission->sale['discounts']))
                                                            {{ $role->permission->sale['discounts'] == '1' ? 'CHECKED' : '' }} 
                                                        @endif 
                                                    type="checkbox" name="discounts" class="sale"> &nbsp; Manage Offers </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" 
                                                            @if (isset($role->permission->sale['sale_statements']))
                                                                {{ $role->permission->sale['sale_statements'] == '1' ? 'CHECKED' : '' }} 
                                                            @endif
                                                        name="sale_statements" class="sale"> &nbsp; Sale Statements</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" 
                                                            @if (isset($role->permission->sale['sale_return_statements']))
                                                                {{ $role->permission->sale['sale_return_statements'] == '1' ? 'CHECKED' : '' }} 
                                                            @endif
                                                        name="sale_return_statements" class="sale"> &nbsp; Sale Return Statements</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" 
                                                            @if (isset($role->permission->sale['pro_sale_report']))
                                                                {{ $role->permission->sale['pro_sale_report'] == '1' ? 'CHECKED' : '' }} 
                                                            @endif
                                                        name="pro_sale_report" class="sale"> &nbsp;  Sale Product Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" 
                                                            @if (isset($role->permission->sale['sale_payment_report']))
                                                                {{ $role->permission->sale['sale_payment_report'] == '1' ? 'CHECKED' : '' }} 
                                                            @endif
                                                        name="sale_payment_report" class="sale"> &nbsp; Receive Payment Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" 
                                                            @if (isset($role->permission->sale['c_register_report']))
                                                                {{ $role->permission->sale['c_register_report'] == '1' ? 'CHECKED' : '' }} 
                                                            @endif
                                                        name="c_register_report" class="sale"> &nbsp; Cash Register report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" 
                                                            @if (isset($role->permission->sale['sale_representative_report']))
                                                                {{ $role->permission->sale['sale_representative_report'] == '1' ? 'CHECKED' : '' }} 
                                                            @endif
                                                        name="sale_representative_report" class="sale"> &nbsp; Sales Representative Report</p> 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">

                                                <div class="col-md-12">
                                                    <p><strong>@lang('POS Sale')</strong></p>
                                                </div> 

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->sale['pos_all'] == '1' ? 'CHECKED' : '' }} name="pos_all" class="sale"> &nbsp; Manage Pos Sale</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->sale['pos_add'] == '1' ? 'CHECKED' : '' }} name="pos_add" class="sale"> &nbsp; Add POS Sale </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->sale['pos_edit'] == '1' ? 'CHECKED' : '' }} name="pos_edit" class="sale"> &nbsp; Edit POS Sale </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->sale['pos_delete'] == '1' ? 'CHECKED' : '' }} name="pos_delete" class="sale"> &nbsp; Delete POS Sale </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" 
                                                            @if (isset($role->permission->sale['pos_sale_settings']))
                                                                {{ $role->permission->sale['pos_sale_settings'] == '1' ? 'CHECKED' : '' }} 
                                                            @endif
                                                        name="pos_sale_settings" class="sale"> &nbsp; POS Sale Settings </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->sale['edit_price_pos_screen'] == '1' ? 'CHECKED' : '' }} name="edit_price_pos_screen" class="sale"> &nbsp; Edit Product Price From POS Screen </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->sale['edit_discount_pos_screen'] == '1' ? 'CHECKED' : '' }} name="edit_discount_pos_screen" class="sale"> &nbsp; Edit Product Discount From POS Screen </p> 
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
                                        <p class="p-1 text-primary"><strong>@lang('Cash Register Permission')</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap"> 
                                                    <input type="checkbox" id="select_all" data-target="cash_register" autocomplete="off"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-1">

                                                <div class="col-md-12">
                                                    <p><strong>@lang('Cash Register')</strong></p>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->register['register_view'] == '1' ? 'CHECKED' : '' }} name="register_view" class="cash_register"> &nbsp; View Cash Register</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->register['register_close'] == '1' ? 'CHECKED' : '' }} name="register_close" class="cash_register"> &nbsp; Close Cash Register </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" 
                                                            @if (isset($role->permission->register['another_register_close']))
                                                                {{ $role->permission->register['another_register_close'] == '1' ? 'CHECKED' : '' }} 
                                                            @endif
                                                        name="another_register_close" class="another_register_close"> &nbsp; Close Another Cash Register </p> 
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
                                        <p class="p-1 text-primary"><strong>@lang('All Report Permission')</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap"> 
                                                    <input type="checkbox" id="select_all" data-target="report"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-1">

                                                <div class="col-md-12">
                                                    <p><strong>@lang('Reports')</strong></p>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->report['tax_report'] == '1' ? 'CHECKED' : '' }}  name="tax_report" class="report"> &nbsp; Tax Report</p> 
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
                                        <p class="p-1 text-primary"><strong>@lang('Setup Permission')</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap"> 
                                                    <input type="checkbox" id="select_all" data-target="settings"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-1">

                                                <div class="col-md-12">
                                                    <p><strong>@lang('Setup')</strong></p>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->setup['tax'] == '1' ? 'CHECKED' : '' }} name="tax" class="settings"> &nbsp; Tax</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                       <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->setup['branch'] == '1' ? 'CHECKED' : '' }} name="branch" class="settings"> &nbsp; Branch</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->setup['warehouse'] == '1' ? 'CHECKED' : '' }} name="warehouse" class="settings"> &nbsp; Warehouse</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->setup['g_settings'] == '1' ? 'CHECKED' : '' }} name="g_settings" class="settings"> &nbsp; General Settings</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input {{ $role->permission->setup['p_settings'] == '1' ? 'CHECKED' : '' }} type="checkbox" name="p_settings" class="settings"> &nbsp; Payment settings</p> 
                                                    </div>
                                                </div>
                                           
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->setup['inv_sc'] == '1' ? 'CHECKED' : '' }} name="inv_sc" class="settings"> &nbsp; Invoice Schemas</p> 
                                                    </div>
                                                </div>
                                          
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->setup['inv_lay'] == '1' ? 'CHECKED' : '' }} name="inv_lay" class="settings"> &nbsp; Invoice Layout</p> 
                                                    </div>
                                                </div>
                                         
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->setup['barcode_settings'] == '1' ? 'CHECKED' : '' }} name="barcode_settings" class="settings"> &nbsp; Barcode Settings</p> 
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->setup['cash_counters'] == '1' ? 'CHECKED' : '' }} name="cash_counters" class="settings"> &nbsp; Cash Counters</p> 
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
                                        <p class="p-1 text-primary"><strong>@lang('Dashboard Permission')</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                
                                            </div>
                                            
                                            <div class="col-md-6 offset-1">

                                                <div class="col-md-12">
                                                    <p><strong>@lang('Dashboard')</strong></p>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->dashboard['dash_data'] == '1' ? 'CHECKED' : '' }} name="dash_data"> &nbsp; View Dashboard Data </p> 
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
                                        <p class="p-1 text-primary"><strong>@lang('Accounting Permission')</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                
                                            </div>
                                            
                                            <div class="col-md-6 offset-1">

                                                <div class="col-md-12">
                                                    <p><strong>@lang('Accounting')</strong></p>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->accounting['ac_access'] == '1' ? 'CHECKED' : '' }} name="ac_access"> &nbsp; Access Accounting </p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($addons->hrm == 1)
                                <div class="col-md-8">
                                    <div class="form_element m-0 mt-2">
                                        <div class="heading_area">
                                            <p class="p-1 text-primary"><strong>@lang('HRM Permission')</strong> </p>
                                        </div>

                                        <div class="element-body">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" id="select_all"  data-target="HRMS"> &nbsp; Select All </p> 
                                                    </div>
                                                </div>
                    
                                                <div class="col-md-4 offset-1">

                                                    <div class="col-md-12">
                                                        <p><strong>@lang('HRM')</strong></p>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap mt-1"> 
                                                            <input type="checkbox" {{ $role->permission->hrms['hrm_dashboard'] == '1' ? 'CHECKED' : '' }}  name="hrm_dashboard" class="HRMS"> &nbsp; Hrm Dashboard</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" {{ $role->permission->hrms['attendance'] == '1' ? 'CHECKED' : '' }} name="attendance" class="HRMS"> &nbsp; Attendance</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" {{ $role->permission->hrms['payroll'] == '1' ? 'CHECKED' : '' }} name="payroll" class="HRMS"> &nbsp; Payroll</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" 
                                                            name="payroll_report"
                                                                @if (isset($role->permission->hrms['payroll_report']))
                                                                    {{ $role->permission->hrms['payroll_report'] == '1' ? 'CHECKED' : '' }} 
                                                                @endif
                                                             class="HRMS"> &nbsp; Payroll Report</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" 
                                                            @if (isset($role->permission->hrms['payroll_payment_report']))
                                                                {{ $role->permission->hrms['payroll_payment_report'] == '1' ? 'CHECKED' : '' }} 
                                                            @endif
                                                            name="payroll_payment_report" class="HRMS"> &nbsp; Payroll Payment Report</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" 
                                                                @if (isset($role->permission->hrms['attendance_report']))
                                                                    {{ $role->permission->hrms['attendance_report'] == '1' ? 'CHECKED' : '' }} 
                                                                @endif
                                                            name="attendance_report" class="HRMS"> &nbsp; Attendance Report</p> 
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-5">
                                                    <div class="col-md-12">
                                                        <p><strong>@lang('Others')</strong></p>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap mt-1"> 
                                                            <input type="checkbox" {{ $role->permission->hrms['leave_type'] == '1' ? 'CHECKED' : '' }}  name="leave_type" class="HRMS"> &nbsp; Leave Type</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" {{ $role->permission->hrms['leave_assign'] == '1' ? 'CHECKED' : '' }}  name="leave_assign" class="HRMS"> &nbsp; Leave Assign</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" {{ $role->permission->hrms['shift'] == '1' ? 'CHECKED' : '' }} name="shift" class="HRMS"> &nbsp; Shift</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" {{ $role->permission->hrms['view_allowance_and_deduction'] == '1' ? 'CHECKED' : '' }} name="view_allowance_and_deduction" class="HRMS"> &nbsp; Allowance and deduction</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" {{ $role->permission->hrms['holiday'] == '1' ? 'CHECKED' : '' }} name="holiday" class="HRMS"> &nbsp; Holiday</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" {{ $role->permission->hrms['department'] == '1' ? 'CHECKED' : '' }} name="department" class="HRMS"> &nbsp; Departments</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" {{ $role->permission->hrms['designation'] == '1' ? 'CHECKED' : '' }} name="designation" class="HRMS"> &nbsp; Designation</p> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($addons->todo == 1)
                                <div class="col-md-8">
                                    <div class="form_element m-0 mt-2">
                                        <div class="heading_area">
                                            <p class="p-1 text-primary"><strong>@lang('Manage Task Permission')</strong> </p>
                                        </div>

                                        <div class="element-body">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" id="select_all"  data-target="Essentials"> &nbsp; Select All </p> 
                                                    </div>
                                                </div>
                    
                                                <div class="col-md-4 offset-1">

                                                    <div class="col-md-12">
                                                        <p><strong>@lang('Manage Task')</strong></p>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap mt-1"> 
                                                            <input type="checkbox" {{ $role->permission->essential['assign_todo'] == '1' ? 'CHECKED' : '' }} name="assign_todo" class="Essentials"> &nbsp; Todo</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input 
                                                                @if (isset($role->permission->essential['work_space']))
                                                                    {{ $role->permission->essential['work_space'] == '1' ? 'CHECKED' : '' }}  
                                                                @endif
                                                            type="checkbox" name="work_space" class="Essentials"> 
                                                            &nbsp; Work Space</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                                <input 
                                                                    @if (isset($role->permission->essential['work_space']))
                                                                        {{ $role->permission->essential['memo'] == '1' ? 'CHECKED' : '' }}  
                                                                    @endif
                                                                type="checkbox" name="memo" class="Essentials"> 
                                                                &nbsp; Memo
                                                            </p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" {{ $role->permission->essential['msg'] == '1' ? 'CHECKED' : '' }} name="msg" class="Essentials"> &nbsp; Message</p> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($addons->manufacturing == 1)
                                <div class="col-md-8">
                                    <div class="form_element m-0 mt-2">
                                        <div class="heading_area">
                                            <p class="p-1 text-primary"><b>@lang('Manufacturing Permission')</b> </p>
                                        </div>

                                        <div class="element-body">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" id="select_all" data-target="Manufacturing"> &nbsp; Select All </p> 
                                                    </div>
                                                </div>
                    
                                                <div class="col-md-6 offset-1">

                                                    <div class="col-md-12">
                                                        <p><strong>@lang('Manufacturing')</strong></p>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap mt-1"> 
                                                            <input type="checkbox" {{ $role->permission->manufacturing['process_view'] == '1' ? 'CHECKED' : '' }} name="process_view" class=" Manufacturing"> &nbsp; View Process</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" {{ $role->permission->manufacturing['process_add'] == '1' ? 'CHECKED' : '' }} name="process_add" class="Manufacturing"> &nbsp; Add Process</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" {{ $role->permission->manufacturing['process_edit'] == '1' ? 'CHECKED' : '' }} name="process_edit" class="Manufacturing"> &nbsp;  Edit Process</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" {{ $role->permission->manufacturing['process_delete'] == '1' ? 'CHECKED' : '' }} name="process_delete" class="Manufacturing"> &nbsp; Delete Process</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" {{ $role->permission->manufacturing['production_view'] == '1' ? 'CHECKED' : '' }} name="production_view" class=" Manufacturing"> &nbsp; View Production</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" {{ $role->permission->manufacturing['production_add'] == '1' ? 'CHECKED' : '' }} name="production_add" class="Manufacturing"> &nbsp; Add Production</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" {{ $role->permission->manufacturing['production_edit'] == '1' ? 'CHECKED' : '' }} name="production_edit" class="Manufacturing"> &nbsp;  Edit Production</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" {{ $role->permission->manufacturing['production_delete'] == '1' ? 'CHECKED' : '' }} name="production_delete" class="Manufacturing"> &nbsp; Delete Production</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" {{ $role->permission->manufacturing['manuf_settings'] == '1' ? 'CHECKED' : '' }} name="manuf_settings" class="Manufacturing"> &nbsp; Manufacturing Settings</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" {{ $role->permission->manufacturing['manuf_report'] == '1' ? 'CHECKED' : '' }} name="manuf_report" class="Manufacturing"> &nbsp; Manufacturing Report</p> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>@lang('Others Permission')</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap"> 
                                                    <input type="checkbox" id="select_all" data-target="others"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-1">
                                                <div class="col-md-12">
                                                    <p><strong>@lang('Others')</strong></p>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->others['today_summery'] == '1' ? 'CHECKED' : '' }} name="today_summery" class="others"> &nbsp; Today Summery</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->others['communication'] == '1' ? 'CHECKED' : '' }}   name="communication" class="others"> &nbsp; Communication</p> 
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
        $(document).on('click', '#select_all', function() {
            var target = $(this).data('target');
            if ($(this).is(':CHECKED', true)) {
                $('.' + target).prop('checked', true);
            } else {
                $('.' + target).prop('checked', false);
            }
        });
    </script>
@endpush
