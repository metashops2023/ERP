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
            <form id="add_role_form" action="{{ route('users.role.store') }}"  method="POST">
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
                                                    <h5>@lang('Add Role')</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-3"><span
                                                        class="text-danger">*</span> <b>@lang('Role Name') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="role_name" class="form-control add_input" id="role_name"
                                                            placeholder="@lang('Role Name')">
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
                                                        <input type="checkbox" id="select_all" data-target="users"
                                                            autocomplete="off"> &nbsp; Select All
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4 offset-1">

                                                <div class="col-md-12">
                                                    <p><strong>@lang('Users')</strong></p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="user_view" class="users"> &nbsp; View User
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="user_add" class="users"
                                                            autocomplete="off"> &nbsp; Add User
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="user_edit" class="users"
                                                            autocomplete="off"> &nbsp; Edit User
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="user_delete" class="users"
                                                            autocomplete="off"> &nbsp; Delete User
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-5">
                                                <div class="col-md-12">
                                                    <p><strong>@lang('Roles')</strong></p>
                                                </div>
                                              
                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="role_view" class="users">
                                                        &nbsp; View Role
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" name="role_add" class="users">
                                                        &nbsp; Add Role
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" name="role_edit" class="users"> &nbsp; Edit Role
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" name="role_delete" class="users"> &nbsp; Delete Role
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
                                                    <input type="checkbox" id="select_all" data-target="contacts" autocomplete="off"> &nbsp;  Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-4 offset-1">
                                                <div class="col-md-12">
                                                    <p><strong>@lang('Suppliers')</strong></p>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="supplier_all" class="contacts"> &nbsp; View All Supplier </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="supplier_add" class="contacts"> &nbsp; Add Supplier </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="supplier_import" class="contacts"> &nbsp; Import Supplier </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="supplier_edit" class="contacts"> &nbsp; Edit Supplier </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="supplier_delete" class="contacts"> &nbsp; Delete Supplier </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="supplier_report" class="contacts"> &nbsp; Supplier Report</p> 
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-5">
                                                <div class="col-md-12">
                                                    <p><strong>@lang('Customers')</strong></p>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row"><p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="customer_all" class="contacts"> &nbsp; View All Customer </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="customer_add" class="contacts"> &nbsp; Add Customer </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="customer_import" class="contacts"> &nbsp; Import Customer </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="customer_edit" class=" contacts"> &nbsp; Edit Customer </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row"><p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="customer_delete" class="contacts"> &nbsp; Delete Customer </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row"><p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="customer_group" class="contacts"> &nbsp; Customer Group -> View/Add/Edit/Delete</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="customer_report" class="contacts"> &nbsp; Customer Report</p> 
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
                                        <p class="p-1 text-primary"><strong>@lang('Products Permissions')</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap"> 
                                                    <input type="checkbox" id="select_all" data-target="product" autocomplete="off"> &nbsp;  Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-4 offset-1">
                                                <div class="col-md-12">
                                                    <p><strong>@lang('Products')</strong></p>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="product_all" class="product"> &nbsp; View All Product </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="product_add" class="product"> &nbsp; Add Product </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="product_edit" class="product"> &nbsp; Edit Product </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="openingStock_add" class="product"> &nbsp; Add/Edit Opening Stock </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="product_delete" class="product"> &nbsp; Delete Product </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="product_settings" class="product"> &nbsp; Product Settings</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="stock_report" class="product"> &nbsp; Stock Report</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="stock_in_out_report" class="product"> &nbsp; Stock In-Out Report</p> 
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
                                                        <input type="checkbox" name="categories" class="product"> &nbsp; Categories</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="brand" class="product"> &nbsp; Brands</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="units" class="product"> &nbsp; Unit</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="variant" class="product"> &nbsp; Variants</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="warranties" class="product"> &nbsp; Warranties</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="selling_price_group" class="product"> &nbsp; Selling Price Group</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="generate_barcode" class="product"> &nbsp; Generate Barcode</p> 
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
                                        <p class="p-1 text-primary"><strong>@lang('Purchases Permissions')</strong> </p>
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
                                                        <input type="checkbox" name="purchase_all" class="purchase"> &nbsp; View All Purchase </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="purchase_add" class="purchase"> &nbsp; Add Purchase </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="purchase_edit" class="purchase"> &nbsp; Edit Purchase </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="purchase_delete" class="purchase" > &nbsp; Delete Purchase </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="status_update" class="purchase"> &nbsp; Update Status </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="purchase_settings" class="purchase"> &nbsp; Purchase Settings </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="purchase_statements" class="purchase"> &nbsp; Purchase Statements</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="purchase_sale_report" class="purchase"> &nbsp; Purchase & Sale Report</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="pro_purchase_report" class="purchase"> &nbsp; Product Purchase Report</p> 
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
                                                        <input type="checkbox" name="purchase_payment" class="purchase"> &nbsp; View/Add/Delete Purchase Payment </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="purchase_return" class="purchase"> &nbsp; Access Purchase Return </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="purchase_payment_report" class="report"> &nbsp; Purchase Payment Report</p> 
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
                                                    <p class="checkbox_input_wrap"> 
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
                                                        <input type="checkbox" name="adjustment_all" class="adjustment"> &nbsp; View All Adjustment </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="adjustment_add_from_location" class="adjustment"> &nbsp; Add Adjustment @lang('From Business Location')</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="adjustment_add_from_warehouse" class="adjustment"> &nbsp; Add Adjustment From Warehouse</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="adjustment_delete" class="adjustment" > &nbsp; Delete Adjustment </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="stock_adjustment_report" class="adjustment"> &nbsp; Stock Adjustment Report</p> 
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
                                        <p class="p-1 text-primary"><strong>@lang('Expenses Permissions')</strong> </p>
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
                                                        <input type="checkbox" name="view_expense" class="expense"> &nbsp; View Expense </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="add_expense" class="expense"> &nbsp; Add Expense </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="edit_expense" class="expense"> &nbsp; Edit Expense </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="delete_expense" class="expense"> &nbsp; Delete Expense </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="expense_category" class="expense"> &nbsp; Expense Category -> View/Add/Edit/Delete </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="category_wise_expense" class="expense"> &nbsp; View Category Wise Expense </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="expense_report" class="expense"> &nbsp; Expense Report</p> 
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
                                        <p class="p-1 text-primary"><strong>@lang('Sales Permissions')</strong> </p>
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
                                                        <input type="checkbox" name="create_add_sale" class="sale"> &nbsp; Create Add Sale </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="view_add_sale" class="sale"> &nbsp; Manage Add Sale </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="edit_add_sale" class="sale"> &nbsp; Edit Add Sale </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="delete_add_sale" class="sale"> &nbsp; Delete Add Sale </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="add_sale_settings" class="sale"> &nbsp; Add Sale Settings </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="sale_draft" class="sale"> &nbsp; List Draft </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="sale_quotation" class="sale"> &nbsp; List Quotations </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="sale_payment" class="sale"> &nbsp; View/Add/Edit Payment </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="edit_price_sale_screen" class="sale"> &nbsp; Edit product price from sales screen </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="edit_discount_sale_screen" class="sale"> &nbsp; Edit Product Discount In Sale Scr. </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="shipment_access" class="sale"> &nbsp; Access Shipments </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="view_product_cost_is_sale_screed" class="sale"> &nbsp; View Product Cost In sale screen </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="view_own_sale" class="sale"> &nbsp; View only own Add/POS Sale </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="return_access" class="sale"> &nbsp; Access Sale Returns </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="discounts" class="sale"> &nbsp; Manage Offers </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="sale_statements" class="sale"> &nbsp;  Sale Statements</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="sale_return_statements" class="sale"> &nbsp;  Sale Return Statements</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="pro_sale_report" class="sale"> &nbsp;  Sale Product Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="sale_payment_report" class="sale"> &nbsp; Receive Payment Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="c_register_report" class="sale"> &nbsp; Cash Register report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="sale_representative_report" class="sale"> &nbsp; Sales Representative Report</p> 
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
                                                        <input type="checkbox" name="pos_all" class="sale"> &nbsp; Manage Pos Sale</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="pos_add" class="sale"> &nbsp; Add POS Sale </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="pos_edit" class="sale"> &nbsp; Edit POS Sale </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="pos_delete" class="sale"> &nbsp; Delete POS Sale </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="pos_sale_settings" class="sale"> &nbsp; POS Sale Settings </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="edit_price_pos_screen" class="sale"> &nbsp; Edit Product Price From POS Screen </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="edit_discount_pos_screen" class="sale"> &nbsp; Edit Product Discount From POS Screen </p> 
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
                                        <p class="p-1 text-primary"><strong>@lang('Cash Register Permissions')</strong> </p>
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
                                                        <input type="checkbox" name="register_view" class="cash_register"> &nbsp; View Cash Register</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="register_close" class="cash_register"> &nbsp; Close Cash Register </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="another_register_close" class="another_register_close"> &nbsp; Close Another Cash Register </p> 
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
                                        <p class="p-1 text-primary"><strong>@lang('All Report Permissions')</strong> </p>
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
                                                        <input type="checkbox" name="tax_report" class="report"> &nbsp; Tax Report</p> 
                                                    </div>
                                                </div>
            
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="production_report" class="report"> &nbsp; Production Report</p> 
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
                                        <p class="p-1 text-primary"><strong>@lang('Setup Permissions')</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap"> 
                                                    <input type="checkbox" id="select_all" data-target="settings"> &nbsp; Select All</p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-1">
                                                <div class="col-md-12">
                                                    <p><strong>@lang('Setup')</strong></p>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="tax" class="settings"> &nbsp; Tax</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                       <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="branch" class="settings"> &nbsp; Business Location</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="warehouse" class="settings"> &nbsp; Warehouse</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="g_settings" class="settings"> &nbsp; General Settings</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="p_settings" class="settings"> &nbsp; Payment settings</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="inv_sc" class="settings"> &nbsp; Invoice Schemas</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="inv_lay" class="settings"> &nbsp; Invoice Layout</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="barcode_settings" class="settings"> &nbsp; Barcode Settings</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="cash_counters" class="settings"> &nbsp; Cash Counters</p> 
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
                                        <p class="p-1 text-primary"><strong>@lang('Dashboard Permissions')</strong> </p>
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
                                                        <input type="checkbox" name="dash_data"> &nbsp; View Dashboard Data </p> 
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
                                                        <input type="checkbox" name="ac_access"> &nbsp; Access Accounting </p> 
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
                                            <p class="p-1 text-primary"><strong>@lang('HRM Permissions')</strong> </p>
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
                                                            <input type="checkbox" name="hrm_dashboard" class="HRMS"> &nbsp; HRM Dashboard</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="attendance" class="HRMS"> &nbsp;  Attendance</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="payroll" class="HRMS"> &nbsp; Payroll</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="payroll_report" class="HRMS"> &nbsp; Payroll Report</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="payroll_payment_report" class="HRMS"> &nbsp; Payroll Payment Report</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="attendance_report" class="HRMS"> &nbsp; Attendance Report</p> 
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
                                                            <input type="checkbox" name="leave_type" class="HRMS"> &nbsp; Leave Type</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="leave_assign" class="HRMS"> &nbsp; Leave Assign</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="shift" class="HRMS"> &nbsp; Shift</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="view_allowance_and_deduction" class="HRMS"> &nbsp; Allowance and deduction</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="holiday" class="HRMS"> &nbsp; Holiday</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="department" class="HRMS"> &nbsp; Departments</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="designation" class="HRMS"> &nbsp; Designation</p> 
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
                                            <p class="p-1 text-primary"><strong>@lang('Manage Task Permissions')</strong> </p>
                                        </div>

                                        <div class="element-body">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" id="select_all"  data-target="Essentials"> &nbsp; Select All </p> 
                                                    </div>
                                                </div>
                    
                                                <div class="col-md-6 offset-1">
                                                    <div class="col-md-12">
                                                        <p><strong>@lang('Manage Task')</strong></p>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap mt-1"> 
                                                            <input type="checkbox" name="assign_todo" class="Essentials"> &nbsp; Todo</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="work_space" class="Essentials"> &nbsp; Work Space</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="memo" class="Essentials"> &nbsp; Memo</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="msg" class="Essentials"> &nbsp; Message</p> 
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
                                            <p class="p-1 text-primary"><strong>@lang('Manufacturing Permissions')</strong> </p>
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
                                                            <input type="checkbox" name="process_view" class=" Manufacturing"> &nbsp; View Process</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="process_add" class="Manufacturing"> &nbsp; Add Process</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="process_edit" class="Manufacturing"> &nbsp;  Edit Process</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="process_delete" class="Manufacturing"> &nbsp; Delete Process</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="production_view" class=" Manufacturing"> &nbsp; View Production</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="production_add" class="Manufacturing"> &nbsp; Add Production</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="production_edit" class="Manufacturing"> &nbsp;  Edit Production</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="production_delete" class="Manufacturing"> &nbsp; Delete Production</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="manuf_settings" class="Manufacturing"> &nbsp; Manufacturing Settings</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="manuf_report" class="Manufacturing"> &nbsp; Manufacturing Report</p> 
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
                                        <p class="p-1 text-primary"><strong>@lang('Others Permissions')</strong> </p>
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
                                                        <input type="checkbox" name="today_summery" class="others"> &nbsp; Today Summery</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="communication" class="others"> &nbsp; Communication</p> 
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
