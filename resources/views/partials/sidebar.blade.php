<div id="primary_nav" class="g_blue toggle-leftbar">
    <div class="first__left">
        <div class="main__nav">
            <ul id="" class="float-right ul">
            <div class="logo__sec" style="width:100%;text-align:center;margin-top:5px;padding:3px;margin-bottom:10px">
                    <a href="{{ route('dashboard.dashboard') }}" class="logo">
                        @if (auth()->user()->branch)
                            @if (auth()->user()->branch->logo != 'default.png')
                                <img style="height: 40px; width:110px;"
                                src="{{ asset('/uploads/branch_logo/' . auth()->user()->branch->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:white;letter-spacing:1px;padding-top:15px;display:inline-block;">{{ auth()->user()->branch->name }}</span>
                            @endif
                        @else
                            @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                                <img style="height: 40px; width:110px;"
                                src="{{ asset('/uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}"
                                alt="logo" class="logo__img">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:white;letter-spacing:1px;padding-top:15px;display:inline-block;">{{
                                json_decode($generalSettings->business, true)['shop_name'] }}</span>
                            @endif
                        @endif
                    </a>
                </div>
                <li data-menu="dashboardmenu" class="">
                    <a href="{{ route('dashboard.dashboard') }}" class="">
                    <!-- <i class="fa fa-television" aria-hidden="true"></i> -->
                    <i class="fa fa-window-maximize" aria-hidden="true"></i>

                        <p class="title">@lang('Dashboard')</p>
                    </a>
                </li>
                @if (json_decode($generalSettings->modules, true)['contacts'] == '1')
                    @if (auth()->user()->permission->contact['supplier_all'] == '1' ||
                            auth()->user()->permission->contact['supplier_add'] == '1' ||
                            auth()->user()->permission->contact['supplier_import'] == '1' ||
                            auth()->user()->permission->contact['customer_all'] == '1' ||
                            auth()->user()->permission->contact['customer_add'] == '1' ||
                            auth()->user()->permission->contact['customer_import'] == '1' ||
                            auth()->user()->permission->contact['customer_group'] == '1' ||
                            (isset(auth()->user()->permission->contact['supplier_report']) &&
                                auth()->user()->permission->contact['supplier_report'] == '1') ||
                            (isset(auth()->user()->permission->contact['customer_report']) &&
                                auth()->user()->permission->contact['customer_report'] == '1'))
                        <li data-menu="contact" class="{{ request()->is('contacts*') ? 'menu_active' : '' }}">
                            <a href="#" class=""><i class="fa fa-phone-square" aria-hidden="true"></i>
                                <p class="title">@lang('menu.contacts')</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if (auth()->user()->permission->product['product_all'] == '1' ||
                        auth()->user()->permission->product['product_add'] == '1' ||
                        auth()->user()->permission->product['categories'] == '1' ||
                        auth()->user()->permission->product['brand'] == '1' ||
                        auth()->user()->permission->product['units'] == '1' ||
                        auth()->user()->permission->product['variant'] == '1' ||
                        auth()->user()->permission->product['warranties'] == '1' ||
                        auth()->user()->permission->product['selling_price_group'] == '1' ||
                        auth()->user()->permission->product['generate_barcode'] == '1' ||
                        (isset(auth()->user()->permission->product['product_settings']) &&
                            auth()->user()->permission->product['product_settings'] == '1') ||
                        (isset(auth()->user()->permission->product['stock_report']) &&
                            auth()->user()->permission->product['stock_report'] == '1') ||
                        (isset(auth()->user()->permission->product['stock_in_out_report']) &&
                            auth()->user()->permission->product['stock_in_out_report'] == '1'))
                    <li data-menu="product" class="{{ request()->is('product*') ? 'menu_active' : '' }}">
                        <a href="#">
                        <i class="fa fa-archive" aria-hidden="true"></i>
                            <p class="title">@lang('menu.product')</p>
                        </a>
                    </li>
                @endif

                @if (json_decode($generalSettings->modules, true)['purchases'] == '1')

                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                        <li data-menu="purchases" class="{{ request()->is('purchases*') ? 'menu_active' : '' }}">
                            <a href="#" class="">
                            <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                <p class="title">@lang('menu.purchases')</p>
                            </a>
                        </li>
                    @else
                        @if (auth()->user()->branch_id && auth()->user()->branch->purchase_permission == 1)

                            @if (auth()->user()->permission->purchase['purchase_all'] == '1')
                                <li data-menu="purchases"
                                    class="{{ request()->is('purchases*') ? 'menu_active' : '' }}">
                                    <a href="#" class="">
                                        <img src="{{ asset('/backend/asset/img/icon/bill.svg') }}">
                                        <p class="title">@lang('menu.purchases')</p>
                                    </a>
                                </li>
                            @endif
                        @endif
                    @endif
                @endif

                @if (auth()->user()->permission->sale['pos_all'] == '1' ||
                        auth()->user()->permission->sale['pos_add'] == '1' ||
                        auth()->user()->permission->sale['create_add_sale'] == '1' ||
                        auth()->user()->permission->sale['view_add_sale'] == '1' ||
                        auth()->user()->permission->sale['sale_draft'] == '1' ||
                        auth()->user()->permission->sale['sale_quotation'] == '1' ||
                        auth()->user()->permission->sale['shipment_access'] == '1' ||
                        auth()->user()->permission->sale['return_access'] == '1' ||
                        (isset(auth()->user()->permission->sale['pos_sale_settings']) &&
                            auth()->user()->permission->sale['pos_sale_settings'] == '1') ||
                        (isset(auth()->user()->permission->sale['add_sale_settings']) &&
                            auth()->user()->permission->sale['add_sale_settings'] == '1') ||
                        (isset(auth()->user()->permission->sale['discounts']) &&
                            auth()->user()->permission->sale['discounts'] == '1') ||
                        (isset(auth()->user()->permission->sale['sale_statements']) &&
                            auth()->user()->permission->sale['sale_statements'] == '1') ||
                        (isset(auth()->user()->permission->sale['sale_return_statements']) &&
                            auth()->user()->permission->sale['sale_return_statements'] == '1') ||
                        (isset(auth()->user()->permission->sale['pro_sale_report']) &&
                            auth()->user()->permission->sale['pro_sale_report'] == '1') ||
                        (isset(auth()->user()->permission->sale['sale_payment_report']) &&
                            auth()->user()->permission->sale['sale_payment_report'] == '1') ||
                        (isset(auth()->user()->permission->sale['c_register_report']) &&
                            auth()->user()->permission->sale['c_register_report'] == '1') ||
                        (isset(auth()->user()->permission->sale['sale_representative_report']) &&
                            auth()->user()->permission->sale['sale_representative_report'] == '1'))
                    <li data-menu="sales" class="{{ request()->is('sales*') ? 'menu_active' : '' }}">
                        <a href="#">
                        <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                            <p class="title">@lang('menu.sales')</p>
                        </a>
                    </li>
                @endif

                @if (json_decode($generalSettings->modules, true)['transfer_stock'] == '1')
                    <li data-menu="transfer" class="{{ request()->is('transfer/stocks*') ? 'menu_active' : '' }}">
                        <a href="#">
                        <i class="fa fa-retweet" aria-hidden="true"></i>
                            <p class="title">@lang('menu.transfer')</p>
                        </a>
                    </li>
                @endif

                @if (json_decode($generalSettings->modules, true)['stock_adjustment'] == '1')

                    @if (auth()->user()->permission->s_adjust['adjustment_all'] == '1' ||
                            auth()->user()->permission->s_adjust['adjustment_add_from_location'] == '1' ||
                            auth()->user()->permission->s_adjust['adjustment_add_from_warehouse'] == '1' ||
                            (isset(auth()->user()->permission->s_adjust['stock_adjustment_report']) &&
                                auth()->user()->permission->s_adjust['stock_adjustment_report'] == '1'))
                        <li data-menu="adjustment"
                            class="{{ request()->is('stock/adjustments*') ? 'menu_active' : '' }}">
                            <a href="#">
                            <!-- <i class="fa fa-sliders" aria-hidden="true"></i> -->
                            <i class="fa fa-compass" aria-hidden="true"></i>
                                <p class="title">@lang('menu.adjustment')</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if (json_decode($generalSettings->modules, true)['expenses'] == '1')

                    @if (auth()->user()->permission->expense['view_expense'] == '1' ||
                            auth()->user()->permission->expense['add_expense'] == '1' ||
                            auth()->user()->permission->expense['expense_category'] == '1' ||
                            auth()->user()->permission->expense['category_wise_expense'] == '1' ||
                            (isset(auth()->user()->permission->expense['expense_report']) &&
                                auth()->user()->permission->expense['expense_report'] == '1'))
                        <li data-menu="expenses" class="{{ request()->is('expenses*') ? 'menu_active' : '' }}">
                            <a href="#">
                            <!-- <i class="fa fa-money" aria-hidden="true"></i> -->
                            <i class="fa fa-credit-card" aria-hidden="true"></i>
                                <p class="title">@lang('menu.expenses')</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if (json_decode($generalSettings->modules, true)['accounting'] == '1')

                    @if (auth()->user()->permission->accounting['ac_access'] == '1')
                        <li data-menu="accounting" class="{{ request()->is('accounting*') ? 'menu_active' : '' }}">
                            <a href="#">
                            <i class="fa fa-calculator" aria-hidden="true"></i>
                                <p class="title">@lang('menu.accounting')</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if (auth()->user()->permission->user['user_view'] == '1' ||
                        auth()->user()->permission->user['user_add'] == '1' ||
                        auth()->user()->permission->user['role_view'] == '1' ||
                        auth()->user()->permission->user['role_add'] == '1')
                    <li data-menu="users" class="{{ request()->is('users*') ? 'menu_active' : '' }}">
                        <a href="#">
                        <i class="fa fa-users" aria-hidden="true"></i>
                            <p class="title">@lang('menu.users')</p>
                        </a>
                    </li>
                @endif

                @if ($addons->hrm)

                    @if (auth()->user()->permission->hrms['hrm_dashboard'] == '1' ||
                            auth()->user()->permission->hrms['leave_type'] == '1' ||
                            auth()->user()->permission->hrms['leave_assign'] == '1' ||
                            auth()->user()->permission->hrms['shift'] == '1' ||
                            auth()->user()->permission->hrms['attendance'] == '1' ||
                            auth()->user()->permission->hrms['view_allowance_and_deduction'] == '1' ||
                            auth()->user()->permission->hrms['payroll'] == '1' ||
                            auth()->user()->permission->hrms['department'] == '1' ||
                            auth()->user()->permission->hrms['designation'] == '1' ||
                            (isset(auth()->user()->permission->hrms['payroll_report']) &&
                                auth()->user()->permission->hrms['payroll_report'] == '1') ||
                            (isset(auth()->user()->permission->hrms['payroll_payment_report']) &&
                                auth()->user()->permission->hrms['payroll_payment_report'] == '1') ||
                            (isset(auth()->user()->permission->hrms['attendance_report']) &&
                                auth()->user()->permission->hrms['attendance_report'] == '1'))
                        <li data-menu="hrm" class="{{ request()->is('hrm*') ? 'menu_active' : '' }}">
                            <a href="#">
                            <i class="fa fa-database" aria-hidden="true"></i>
                                <p class="title">@lang('menu.hrm')</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if ($addons->manufacturing == 1)

                    @if (auth()->user()->permission->manufacturing['process_view'] == '1' ||
                            auth()->user()->permission->manufacturing['production_view'] == '1' ||
                            auth()->user()->permission->manufacturing['manuf_settings'] == '1' ||
                            auth()->user()->permission->manufacturing['manuf_report'] == '1')
                        <li data-menu="manufacture" class="{{ request()->is('manufacturing*') ? 'menu_active' : '' }}">
                            <a href="#">
                            <i class="fa fa-wrench" aria-hidden="true"></i>
                                <p class="title">@lang('menu.manufacturing')</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if ($addons->todo == 1)

                    @if (json_decode($generalSettings->modules, true)['requisite'] == '1')

                        @if (auth()->user()->permission->essential['assign_todo'] == '1' ||
                                auth()->user()->permission->essential['work_space'] == '1' ||
                                auth()->user()->permission->essential['memo'] == '1' ||
                                auth()->user()->permission->essential['msg'] == '1')
                            <li data-menu="essentials" class="{{ request()->is('essentials*') ? 'menu_active' : '' }}">
                                <a href="#">
                                <i class="fa fa-briefcase" aria-hidden="true"></i>

                                    <p class="title">@lang('menu.essentials')</p>
                                </a>
                            </li>
                        @endif
                    @endif
                @endif

                {{-- @if ($addons->service == 1)
                    <li class="">
                        <a href="#">
                        <i class="fa fa-phone" aria-hidden="true"></i>
                            <p class="title">@lang('menu.service')</p>
                        </a>
                    </li>
                @endif

                @if ($addons->e_commerce == 1)
                    <li class="">
                        <a href="#">
                            <img src="{{ asset('/backend/asset/img/icon/ecommerce2.svg') }}">
                            <p class="title">@lang('menu.e_commerce')</p>
                        </a>
                    </li>
                @endif --}}

                <li data-menu="communication" class="{{ request()->is('communication*') ? 'menu_active' : '' }}">
                    <a href="#">
                    <!-- <i class="fa fa-volume-control-phone" aria-hidden="true"></i> -->
                    <i class="fa fa-phone" aria-hidden="true"></i>
                        <p class="title">@lang('menu.communication')</p>
                    </a>
                </li>
                @if (auth()->user()->permission->report['tax_report'] == '1')
                    <li data-menu="reports" class="{{ request()->is('reports*') ? 'menu_active' : '' }}">
                        <a href="#">
                        <i class="fa fa-clone" aria-hidden="true"></i>
                            <p class="title">@lang('Reports')</p>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->permission->setup['branch'] == '1' ||
                        auth()->user()->permission->setup['warehouse'] == '1' ||
                        auth()->user()->permission->setup['tax'] == '1' ||
                        auth()->user()->permission->setup['g_settings'] == '1' ||
                        auth()->user()->permission->setup['p_settings'] == '1' ||
                        auth()->user()->permission->setup['inv_sc'] == '1' ||
                        auth()->user()->permission->setup['inv_lay'] == '1' ||
                        auth()->user()->permission->setup['barcode_settings'] == '1' ||
                        auth()->user()->permission->setup['cash_counters'] == '1')
                    <li data-menu="settings" class="{{ request()->is('settings*') ? 'menu_active' : '' }}">
                        <a href="#">
                        <i class="fa fa-cog" aria-hidden="true"></i>
                            <p class="title">@lang('menu.setup')</p>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="category-bar">
        <div id="sidebar_t">
            <div class="sub-menu_t" id="product">
                <div class="sub-menu-width">
                    <div class="model__close bg-secondary-2">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="text-muted float-start mt-1"><strong>@lang('Product Management')</strong></p>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            @if (auth()->user()->permission->product['product_add'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('products.add.view') }}" class="bar-link">
                                            <span>
                                                <i class="fas fa-plus-circle"></i>
                                            </span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.add_product')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->product['product_all'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('products.all.product') }}" class="bar-link">
                                            <span>
                                                <i class="fas fa-sitemap"></i>
                                            </span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.product_list')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->product['product_add'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('product.import.create') }}" class="bar-link">
                                            <span>
                                                <i class="fas fa-file-import"></i>
                                            </span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.import_products')</p>
                                </div>
                            @endif
                        </div>

                        <hr class="p-0 m-0 my-1">

                        <div class="row">
                            @if (auth()->user()->permission->product['categories'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('product.categories.index') }}" class="bar-link">
                                            <span>
                                                <i class="fas fa-th-large"></i>
                                            </span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.categories')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->product['brand'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('product.brands.index') }}" class="bar-link">
                                            <span>
                                                <i class="fas fa-band-aid"></i>
                                            </span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.brand')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->product['units'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('settings.units.index') }}" class="bar-link">
                                            <span><i class="fas fa-weight-hanging"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.units')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->product['variant'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('product.variants.index') }}" class="bar-link">
                                            <span>
                                                <i class="fas fa-align-center"></i>
                                            </span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.variants')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->product['warranties'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('product.warranties.index') }}" class="bar-link">
                                            <span>
                                                <i class="fas fa-shield-alt"></i>
                                            </span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.warranties')</p>
                                </div>
                            @endif
                        </div>

                        <hr class="p-0 m-0 my-1">

                        <div class="row">
                            @if (auth()->user()->permission->product['selling_price_group'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('product.selling.price.groups.index') }}" class="bar-link">
                                            <span>
                                                <i class="fas fa-layer-group"></i>
                                            </span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.selling_price_group')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->product['generate_barcode'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('barcode.index') }}" class="bar-link">
                                            <span>
                                                <i class="fas fa-barcode"></i>
                                            </span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.generate_barcode')</p>
                                </div>
                            @endif

                            @if (isset(auth()->user()->permission->product['product_settings']) &&
                                    auth()->user()->permission->product['product_settings'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('products.settings') }}" class="bar-link">
                                            <span>
                                                <i class="fas fa-sliders-h"></i>
                                            </span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.product_settings')</p>
                                </div>
                            @endif
                        </div>

                        @if (
                            (isset(auth()->user()->permission->product['stock_report']) &&
                                auth()->user()->permission->product['stock_report'] == '1') ||
                                (isset(auth()->user()->permission->product['stock_in_out_report']) &&
                                    auth()->user()->permission->product['stock_in_out_report'] == '1'))
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <p class="text-muted mt-1 ms-3"><strong>@lang('Product Reports')</strong></p>
                                    <hr class="p-0 m-0 my-1">
                                </div>

                                @if (isset(auth()->user()->permission->product['stock_report']) &&
                                        auth()->user()->permission->product['stock_report'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('reports.stock.index') }}" class="bar-link">
                                                <span><i class="fas fa-sitemap"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.stock_report')</p>
                                    </div>
                                @endif

                                @if (isset(auth()->user()->permission->product['stock_in_out_report']) &&
                                        auth()->user()->permission->product['stock_in_out_report'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('reports.stock.in.out.index') }}" class="bar-link">
                                                <span><i class="fas fa-cubes"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.stock_in_out_report')</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if ($addons->branches == 1)

                <div class="sub-menu_t" id="superadmin">
                    <div class="sub-menu-width">
                        <div class="model__close bg-secondary-2">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="text-muted float-start mt-1"><strong>@lang('Superadmin')</strong></p>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div class="row">
                                @if (auth()->user()->permission->setup['branch'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('settings.branches.index') }}" class="bar-link">
                                                <span><i class="fas fa-project-diagram"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.branches')</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (json_decode($generalSettings->modules, true)['contacts'] == '1')

                <div class="sub-menu_t" id="contact">
                    <div class="sub-menu-width">
                        <div class="model__close bg-secondary-2">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="text-muted float-start mt-1"><strong>@lang('Contact Management')</strong></p>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div class="row">
                                @if (auth()->user()->permission->contact['supplier_all'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('contacts.supplier.index') }}" class="bar-link">
                                                <span><i class="fas fa-address-card"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.suppliers')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->contact['supplier_import'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('contacts.suppliers.import.create') }}"
                                                class="bar-link">
                                                <span><i class="fas fa-file-import"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.import_suppliers')</p>
                                    </div>
                                @endif
                            </div>

                            <hr class="p-0 m-0 my-1">

                            <div class="row">
                                @if (auth()->user()->permission->contact['customer_all'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('contacts.customer.index') }}" class="bar-link">
                                                <span><i class="far fa-address-card"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.customers') </p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->contact['customer_import'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('contacts.customers.import.create') }}"
                                                class="bar-link">
                                                <span><i class="fas fa-file-upload"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.import_customers')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->contact['customer_group'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('contacts.customers.groups.index') }}"
                                                class="bar-link">
                                                <span><i class="fas fa-users"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.customer_groups')</p>
                                    </div>
                                @endif
                            </div>

                            @if (
                                (isset(auth()->user()->permission->contact['supplier_report']) &&
                                    auth()->user()->permission->contact['supplier_report'] == '1') ||
                                    (isset(auth()->user()->permission->contact['customer_report']) &&
                                        auth()->user()->permission->contact['customer_report'] == '1'))
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <p class="text-muted mt-1 ms-3"><strong>@lang('Contact Reports')</strong></p>
                                        <hr class="p-0 m-0 my-1">
                                    </div>

                                    @if (isset(auth()->user()->permission->contact['supplier_report']) &&
                                            auth()->user()->permission->contact['supplier_report']
                                    )
                                        <div
                                            class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                            <div class="switch_bar">
                                                <a href="{{ route('reports.supplier.index') }}" class="bar-link">
                                                    <span><i class="fas fa-id-card"></i></span>
                                                </a>
                                            </div>
                                            <p class="switch_text">@lang('menu.supplier_report')</p>
                                        </div>
                                    @endif

                                    @if (isset(auth()->user()->permission->contact['customer_report']) &&
                                            auth()->user()->permission->contact['customer_report']
                                    )
                                        <div
                                            class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                            <div class="switch_bar">
                                                <a href="{{ route('reports.customer.index') }}" class="bar-link">
                                                    <span><i class="far fa-id-card"></i></span>
                                                </a>
                                            </div>
                                            <p class="switch_text">@lang('menu.customer_report')</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if (json_decode($generalSettings->modules, true)['purchases'] == '1')

                @if (!auth()->user()->branch_id)

                    <div class="sub-menu_t" id="purchases">
                        <div class="sub-menu-width">
                            <div class="model__close bg-secondary-2">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="text-muted float-start mt-1"><strong>@lang('Purchase Management')</strong></p>
                                    </div>
                                </div>
                            </div>

                            <div class="container-fluid">
                                <div class="row">
                                    @if (auth()->user()->permission->purchase['purchase_add'] == '1')
                                        <div
                                            class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                            <div class="switch_bar">
                                                <a href="{{ route('purchases.create') }}" class="bar-link">
                                                    <span><i class="fas fa-shopping-cart"></i></span>
                                                </a>
                                            </div>
                                            <p class="switch_text">@lang('menu.add_purchase')</p>
                                        </div>
                                    @endif

                                    @if (auth()->user()->permission->purchase['purchase_all'] == '1')
                                        <div
                                            class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                            <div class="switch_bar">
                                                <a href="{{ route('purchases.index_v2') }}" class="bar-link">
                                                    <span><i class="fas fa-list"></i></span>
                                                </a>
                                            </div>
                                            <p class="switch_text">@lang('menu.purchase_list')</p>
                                        </div>

                                        <div
                                            class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                            <div class="switch_bar">
                                                <a href="{{ route('purchases.product.list') }}" class="bar-link">
                                                    <span><i class="fas fa-list"></i></span>
                                                </a>
                                            </div>
                                            <p class="switch_text">@lang('menu.purchase_product_list')</p>
                                        </div>

                                        {{-- <div
                                            class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                            <div class="switch_bar">
                                                <a href="{{ route('purchases.po.list') }}" class="bar-link">
                                                    <span><i class="fas fa-list"></i></span>
                                                </a>
                                            </div>
                                            <p class="switch_text">@lang('menu.po_list')</p>
                                        </div> --}}
                                    @endif

                                    @if (isset(auth()->user()->permission->purchase['purchase_settings']) &&
                                            auth()->user()->permission->purchase['purchase_settings'] == '1')
                                        <div
                                            class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                            <div class="switch_bar">
                                                <a href="{{ route('purchase.settings') }}" class="bar-link">
                                                    <span><i class="fas fa-sliders-h"></i></span>
                                                </a>
                                            </div>
                                            <p class="switch_text">@lang('menu.purchase_settings')</p>
                                        </div>
                                    @endif
                                </div>

                                <hr class="p-0 m-0 my-1">

                                @if (auth()->user()->permission->purchase['purchase_return'] == '1')
                                    <div class="row">
                                        <div
                                            class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                            <div class="switch_bar">
                                                <a href="{{ route('purchases.returns.supplier.return') }}"
                                                    class="bar-link">
                                                    <span><i class="fas fa-plus-circle"></i></span>
                                                </a>
                                            </div>
                                            <p class="switch_text"> @lang('menu.add_return')</p>
                                        </div>

                                        <div
                                            class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                            <div class="switch_bar">
                                                <a href="{{ route('purchases.returns.index') }}" class="bar-link">
                                                    <span><i class="fas fa-undo"></i></span>
                                                </a>
                                            </div>
                                            <p class="switch_text">@lang('menu.purchase_return_list')</p>
                                        </div>
                                    </div>
                                @endif

                                @if (
                                    (isset(auth()->user()->permission->purchase['purchase_statements']) &&
                                        auth()->user()->permission->purchase['purchase_statements'] == '1') ||
                                        (isset(auth()->user()->permission->purchase['purchase_sale_report']) &&
                                            auth()->user()->permission->purchase['purchase_sale_report'] == '1') ||
                                        (isset(auth()->user()->permission->purchase['pro_purchase_report']) &&
                                            auth()->user()->permission->purchase['pro_purchase_report'] == '1') ||
                                        (isset(auth()->user()->permission->purchase['purchase_payment_report']) &&
                                            auth()->user()->permission->purchase['purchase_payment_report'] == '1'))

                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                            <p class="text-muted ms-3"><strong>@lang('Purchase Reports')</strong></p>
                                            <hr class="p-0 m-0 my-1">
                                        </div>

                                        @if (isset(auth()->user()->permission->purchase['purchase_statements']) &&
                                                auth()->user()->permission->purchase['purchase_statements'] == '1')
                                            <div
                                                class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                <div class="switch_bar">
                                                    <a href="{{ route('reports.purchases.statement.index') }}"
                                                        class="bar-link">
                                                        <span><i class="fas fa-tasks"></i></span>
                                                    </a>
                                                </div>
                                                <p class="switch_text">@lang('menu.purchase_statements')</p>
                                            </div>
                                        @endif

                                        @if (isset(auth()->user()->permission->purchase['purchase_sale_report']) &&
                                                auth()->user()->permission->purchase['purchase_sale_report'] == '1')
                                            <div
                                                class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                <div class="switch_bar">
                                                    <a href="{{ route('reports.sales.purchases.index') }}"
                                                        class="bar-link">
                                                        <span><i class="far fa-chart-bar"></i></span>
                                                    </a>
                                                </div>
                                                <p class="switch_text">@lang('menu.purchase_sale')</p>
                                            </div>
                                        @endif

                                        @if (isset(auth()->user()->permission->purchase['pro_purchase_report']) &&
                                                auth()->user()->permission->purchase['pro_purchase_report'] == '1')
                                            <div
                                                class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                <div class="switch_bar">
                                                    <a href="{{ route('reports.product.purchases.index') }}"
                                                        class="bar-link">
                                                        <span><i class="fas fa-shopping-cart"></i></span>
                                                    </a>
                                                </div>
                                                <p class="switch_text">@lang('menu.product_purchase_report')</p>
                                            </div>
                                        @endif

                                        @if (isset(auth()->user()->permission->purchase['purchase_payment_report']) &&
                                                auth()->user()->permission->purchase['purchase_payment_report'] == '1')
                                            <div
                                                class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                <div class="switch_bar">
                                                    <a href="{{ route('reports.purchase.payments.index') }}"
                                                        class="bar-link">
                                                        <span><i class="fas fa-money-check-alt"></i></span>
                                                    </a>
                                                </div>
                                                <p class="switch_text">@lang('menu.purchase_payment_report')</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @elseif(auth()->user()->branch_id && auth()->user()->branch->purchase_permission == 1)
                    @if (auth()->user()->permission->purchase['purchase_all'] == '1')

                        <div class="sub-menu_t" id="purchases">
                            <div class="sub-menu-width">
                                <div class="model__close bg-secondary-2">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="text-muted float-start mt-1"><strong>@lang('Purchase Management')</strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-fluid">
                                    <div class="row">
                                        @if (auth()->user()->permission->purchase['purchase_add'] == '1')
                                            <div
                                                class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                <div class="switch_bar">
                                                    <a href="{{ route('purchases.create') }}" class="bar-link">
                                                        <span><i class="fas fa-shopping-cart"></i></span>
                                                    </a>
                                                </div>
                                                <p class="switch_text">@lang('menu.add_purchase')</p>
                                            </div>
                                        @endif

                                        @if (auth()->user()->permission->purchase['purchase_all'] == '1')
                                            <div
                                                class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                <div class="switch_bar">
                                                    <a href="{{ route('purchases.index_v2') }}" class="bar-link">
                                                        <span><i class="fas fa-list"></i></span>
                                                    </a>
                                                </div>
                                                <p class="switch_text">@lang('menu.purchase_list')</p>
                                            </div>

                                            <div
                                                class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                <div class="switch_bar">
                                                    <a href="{{ route('purchases.product.list') }}" class="bar-link">
                                                        <span><i class="fas fa-list"></i></span>
                                                    </a>
                                                </div>
                                                <p class="switch_text">@lang('menu.purchase_product_list')</p>
                                            </div>

                                            {{-- <div
                                                class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                <div class="switch_bar">
                                                    <a href="{{ route('purchases.po.list') }}" class="bar-link">
                                                        <span><i class="fas fa-list"></i></span>
                                                    </a>
                                                </div>
                                                <p class="switch_text">@lang('menu.po_list')</p>
                                            </div> --}}
                                        @endif

                                        @if (isset(auth()->user()->permission->purchase['purchase_settings']) &&
                                                auth()->user()->permission->purchase['purchase_settings'] == '1')
                                            <div
                                                class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                <div class="switch_bar">
                                                    <a href="{{ route('purchase.settings') }}" class="bar-link">
                                                        <span><i class="fas fa-sliders-h"></i></span>
                                                    </a>
                                                </div>
                                                <p class="switch_text">@lang('menu.purchase_settings')</p>
                                            </div>
                                        @endif
                                    </div>

                                    <hr class="p-0 m-0 my-1">

                                    <div class="row">
                                        @if (auth()->user()->permission->purchase['purchase_return'] == '1')
                                            <div
                                                class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                <div class="switch_bar">
                                                    <a href="{{ route('purchases.returns.supplier.return') }}"
                                                        class="bar-link">
                                                        <span><i class="fas fa-plus-circle"></i></span>
                                                    </a>
                                                </div>
                                                <p class="switch_text"> @lang('menu.add_return')</p>
                                            </div>

                                            <div
                                                class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                <div class="switch_bar">
                                                    {{-- <span class="notify-grin">30</span> --}}
                                                    <a href="{{ route('purchases.returns.index') }}"
                                                        class="bar-link">
                                                        <span><i class="fas fa-undo"></i></span>
                                                    </a>
                                                </div>
                                                <p class="switch_text"> @lang('menu.purchase_return_list')</p>
                                            </div>
                                        @endif
                                    </div>

                                    @if (
                                        (isset(auth()->user()->permission->purchase['purchase_statements']) &&
                                            auth()->user()->permission->purchase['purchase_statements'] == '1') ||
                                            (isset(auth()->user()->permission->purchase['purchase_sale_report']) &&
                                                auth()->user()->permission->purchase['purchase_sale_report'] == '1') ||
                                            (isset(auth()->user()->permission->purchase['pro_purchase_report']) &&
                                                auth()->user()->permission->purchase['pro_purchase_report'] == '1') ||
                                            (isset(auth()->user()->permission->purchase['purchase_payment_report']) &&
                                                auth()->user()->permission->purchase['purchase_payment_report'] == '1'))

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                                <p class="text-muted ms-3"><strong>@lang('Purchase Reports')</strong></p>
                                                <hr class="p-0 m-0 my-1">
                                            </div>

                                            @if (isset(auth()->user()->permission->purchase['purchase_statements']) &&
                                                    auth()->user()->permission->purchase['purchase_statements'] == '1')
                                                <div
                                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                    <div class="switch_bar">
                                                        <a href="{{ route('reports.purchases.statement.index') }}"
                                                            class="bar-link">
                                                            <span><i class="fas fa-tasks"></i></span>
                                                        </a>
                                                    </div>
                                                    <p class="switch_text">@lang('menu.purchase_statements')</p>
                                                </div>
                                            @endif

                                            @if (isset(auth()->user()->permission->purchase['purchase_sale_report']) &&
                                                    auth()->user()->permission->purchase['purchase_sale_report'] == '1')
                                                <div
                                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                    <div class="switch_bar">
                                                        <a href="{{ route('reports.sales.purchases.index') }}"
                                                            class="bar-link">
                                                            <span><i class="far fa-chart-bar"></i></span>
                                                        </a>
                                                    </div>
                                                    <p class="switch_text">@lang('menu.purchase_sale')</p>
                                                </div>
                                            @endif

                                            @if (isset(auth()->user()->permission->purchase['pro_purchase_report']) &&
                                                    auth()->user()->permission->purchase['pro_purchase_report'] == '1')
                                                <div
                                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                    <div class="switch_bar">
                                                        <a href="{{ route('reports.product.purchases.index') }}"
                                                            class="bar-link">
                                                            <span><i class="fas fa-shopping-cart"></i></span>
                                                        </a>
                                                    </div>
                                                    <p class="switch_text">@lang('menu.product_purchase_report')</p>
                                                </div>
                                            @endif

                                            @if (isset(auth()->user()->permission->purchase['purchase_payment_report']) &&
                                                    auth()->user()->permission->purchase['purchase_payment_report'] == '1')
                                                <div
                                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                    <div class="switch_bar">
                                                        <a href="{{ route('reports.purchase.payments.index') }}"
                                                            class="bar-link">
                                                            <span><i class="fas fa-money-check-alt"></i></span>
                                                        </a>
                                                    </div>
                                                    <p class="switch_text">@lang('menu.purchase_payment_report')</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                @endif
            @endif

            <div class="sub-menu_t" id="sales">
                <div class="sub-menu-width">
                    <div class="model__close bg-secondary-2">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="text-muted float-start mt-1"><strong>@lang('Sale Management')</strong></p>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row">

                            @if (json_decode($generalSettings->modules, true)['add_sale'] == '1')

                                @if (auth()->user()->permission->sale['create_add_sale'])
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('sales.create') }}" class="bar-link">
                                                <span><i class="fas fa-cart-plus"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text"> @lang('menu.add_sale')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->sale['view_add_sale'])
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('sales.index2') }}" class="bar-link">
                                                <span><i class="fas fa-tasks"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.add_sale_list')</p>
                                    </div>
                                @endif

                                @if (isset(auth()->user()->permission->sale['add_sale_settings']) &&
                                        auth()->user()->permission->sale['add_sale_settings'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('sales.add.sale.settings') }}" class="bar-link">
                                                <span><i class="fas fa-sliders-h"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.add_sale_settings')</p>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <hr class="p-0 m-0 my-1">

                        <div class="row">
                            @if (json_decode($generalSettings->modules, true)['pos'] == '1')

                                @if (auth()->user()->permission->sale['pos_add'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('sales.pos.create') }}" class="bar-link">
                                                <span><i class="fas fa-cash-register"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.pos')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->sale['pos_all'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('sales.pos.list') }}" class="bar-link">
                                                <span><i class="fas fa-tasks"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.pos_sale_list')</p>
                                    </div>
                                @endif

                                @if (isset(auth()->user()->permission->sale['pos_sale_settings']) &&
                                        auth()->user()->permission->sale['pos_sale_settings'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('sales.pos.settings') }}" class="bar-link">
                                                <span><i class="fas fa-sliders-h"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.pos_sale_settings')</p>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <hr class="p-0 m-0 my-1">

                        <div class="row">
                            <div
                                class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                <div class="switch_bar">
                                    <a href="{{ route('sales.order.list') }}" class="bar-link">
                                        <span><i class="fa fa-file-alt"></i></span>
                                    </a>
                                </div>
                                <p class="switch_text">@lang('menu.sales_order_list')</p>
                            </div>

                            @if (auth()->user()->permission->sale['sale_draft'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('sales.drafts') }}" class="bar-link">
                                            <span><i class="fas fa-drafting-compass"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.draft_list')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->sale['sale_quotation'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('sales.quotations') }}" class="bar-link">
                                            <span><i class="fas fa-quote-right"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.quotation_list')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->sale['view_add_sale'] || auth()->user()->permission->sale['pos_all'])
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('sales.product.list') }}" class="bar-link">
                                            <span><i class="fas fa-tasks"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.sold_product_list')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->sale['shipment_access'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('sales.shipments') }}" class="bar-link">
                                            <span><i class="fas fa-shipping-fast"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.shipments')</p>
                                </div>
                            @endif
                        </div>

                        <hr class="p-0 m-0 my-1">

                        <div class="row">
                            @if (auth()->user()->permission->sale['return_access'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('sale.return.random.create') }}" class="bar-link">
                                            <span><i class="fas fa-undo"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.add_sale_return')</p>
                                </div>

                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('sales.returns.index') }}" class="bar-link">
                                            <span><i class="fas fa-undo"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.sale_return_list')</p>
                                </div>
                            @endif

                            @if (isset(auth()->user()->permission->sale['discounts']) && auth()->user()->permission->sale['discounts'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('sales.discounts.index') }}" class="bar-link">
                                            <span><i class="fas fa-percentage"></i></span>
                                        </a>
                                    </div>

                                    <p class="switch_text">@lang('menu.discounts')</p>
                                </div>
                            @endif
                        </div>

                        @if (
                            (isset(auth()->user()->permission->sale['pro_sale_report']) &&
                                auth()->user()->permission->sale['pro_sale_report'] == '1') ||
                                (isset(auth()->user()->permission->sale['sale_payment_report']) &&
                                    auth()->user()->permission->sale['sale_payment_report'] == '1') ||
                                (isset(auth()->user()->permission->sale['c_register_report']) &&
                                    auth()->user()->permission->sale['c_register_report'] == '1') ||
                                (isset(auth()->user()->permission->sale['sale_representative_report']) &&
                                    auth()->user()->permission->sale['sale_representative_report'] == '1'))
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <p class="text-muted mt-1 ms-3"><strong>@lang('Sale Reports')</strong></p>
                                    <hr class="p-0 m-0 my-1">
                                </div>

                                @if (isset(auth()->user()->permission->sale['sale_statements']) &&
                                        auth()->user()->permission->sale['sale_statements'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('reports.sale.statement.index') }}" class="bar-link">
                                                <span><i class="fas fa-tasks"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.sale_statement')</p>
                                    </div>
                                @endif

                                @if (isset(auth()->user()->permission->sale['sale_return_statements']) &&
                                        auth()->user()->permission->sale['sale_return_statements'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('reports.sale.return.statement.index') }}"
                                                class="bar-link">
                                                <span><i class="fas fa-tasks"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.return_statement')</p>
                                    </div>
                                @endif

                                @if (isset(auth()->user()->permission->sale['pro_sale_report']) &&
                                        auth()->user()->permission->sale['pro_sale_report'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('reports.product.sales.index') }}" class="bar-link">
                                                <span><i class="fas fa-cart-arrow-down"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.product_sale_report')</p>
                                    </div>
                                @endif

                                @if (isset(auth()->user()->permission->sale['sale_payment_report']) &&
                                        auth()->user()->permission->sale['sale_payment_report'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('reports.sale.payments.index') }}" class="bar-link">
                                                <span><i class="fas fa-hand-holding-usd"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.sale_payment_report')</p>
                                    </div>
                                @endif

                                @if (isset(auth()->user()->permission->sale['c_register_report']) &&
                                        auth()->user()->permission->sale['c_register_report'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('reports.cash.registers.index') }}" class="bar-link">
                                                <span><i class="fas fa-cash-register"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.register_report')</p>
                                    </div>
                                @endif

                                @if (isset(auth()->user()->permission->sale['sale_representative_report']) &&
                                        auth()->user()->permission->sale['sale_representative_report'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('reports.sale.representive.index') }}"
                                                class="bar-link">
                                                <span><i class="fas fa-user-tie"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.sales_representative_report')</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if (json_decode($generalSettings->modules, true)['transfer_stock'] == '1')
                <div class="sub-menu_t" id="transfer">
                    <div class="sub-menu-width">
                        <div class="model__close bg-secondary-2">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="text-muted float-start mt-1"><strong>@lang('Stock Transfer Management')</strong></p>
                                </div>
                            </div>
                        </div>
                        <div class="container-fluid">
                            <div class="row">
                                <div
                                    class="col-lg-12 col-md-12 col-sm-12 col-10 p-1 ms-4 text-center d-flex justify-content-top align-items-start flex-column">
                                    <p>{!! __('menu.transfer_stock_heading_1') !!}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('transfer.stock.to.branch.create') }}" class="bar-link">
                                            <span><i class="fas fa-exchange-alt"></i></span>
                                        </a>
                                    </div>

                                    <p class="switch_text">@lang('menu.add_transfer')
                                        <small class="ml-1"><b>(@lang('menu.to_branch'))</small></b>
                                    </p>
                                </div>

                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('transfer.stock.to.branch.index') }}" class="bar-link">
                                            <span><i class="fas fa-list-ul"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.transfer_list')</p>
                                </div>

                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('transfer.stocks.to.warehouse.receive.stock.index') }}"
                                            class="bar-link">
                                            <span><i class="fas fa-check-double"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.receive_stocks')
                                        <small class="ml-1"><b>(@lang('(From)') @lang('B.Location'))</small></b>
                                    </p>
                                </div>
                            </div>

                            <hr class="p-0 m-0 my-1">

                            <div class="row">
                                <div
                                    class="col-lg-12 col-md-12 col-sm-12 col-10 p-1 ms-4 text-center d-flex justify-content-top align-items-start flex-column">
                                    <p>{!! __('menu.transfer_stock_heading_2') !!}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('transfer.stock.to.warehouse.create') }}"
                                            class="bar-link">
                                            <span><i class="fas fa-exchange-alt"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.add_transfer')
                                        <small class="ml-1">(@lang('menu.to_warehouse'))</small>
                                    </p>
                                </div>

                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('transfer.stock.to.warehouse.index') }}" class="bar-link">
                                            <span><i class="fas fa-list-ul"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.transfer_list')
                                        <small class="ml-1">(@lang('menu.to_warehouse'))</small>
                                    </p>
                                </div>

                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('transfer.stocks.to.branch.receive.stock.index') }}"
                                            class="bar-link">
                                            <span><i class="fas fa-check-double"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.receive_stocks')</p>
                                </div>
                            </div>

                            @if ($addons->branches == 1)
                                <hr class="p-0 m-0 my-1">
                                <div class="row">
                                    <div
                                        class="col-lg-12 col-md-12 col-sm-12 col-10 p-1 ms-4 text-center d-flex justify-content-top align-items-start flex-column">
                                        <p>{!! __('menu.transfer_stock_heading_3') !!}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('transfer.stock.branch.to.branch.create') }}"
                                                class="bar-link">
                                                <span><i class="fas fa-exchange-alt"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.add_transfer')</p>
                                    </div>

                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('transfer.stock.branch.to.branch.transfer.list') }}"
                                                class="bar-link">
                                                <span><i class="fas fa-list-ul"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.transfer_list')</p>
                                    </div>

                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('transfer.stock.branch.to.branch.receivable.list') }}"
                                                class="bar-link">
                                                <span><i class="fas fa-check-double"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.receive_stocks')</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if (json_decode($generalSettings->modules, true)['stock_adjustment'] == '1')
                <div class="sub-menu_t" id="adjustment">
                    <div class="sub-menu-width">
                        <div class="model__close bg-secondary-2">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="text-muted float-start mt-1"><strong>@lang('Stock Adjustment')</strong></p>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div class="row">
                                @if (auth()->user()->permission->s_adjust['adjustment_add_from_location'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('stock.adjustments.create') }}" class="bar-link">
                                                <span><i class="fas fa-plus-square"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.add_stock_adjustment_from_branch')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->s_adjust['adjustment_add_from_warehouse'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('stock.adjustments.create.from.warehouse') }}"
                                                class="bar-link">
                                                <span><i class="fas fa-plus-square"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.add_stock_adjustment_from_warehouse')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->s_adjust['adjustment_add_from_location'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('stock.adjustments.index') }}" class="bar-link">
                                                <span><i class="fas fa-th-list"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.stock_adjustment_list')</p>
                                    </div>
                                @endif
                            </div>

                            @if (isset(auth()->user()->permission->s_adjust['stock_adjustment_report']) &&
                                    auth()->user()->permission->s_adjust['stock_adjustment_report'] == '1')
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <p class="text-muted mt-1 ms-3"><strong>@lang('Stock Adjustment Reports')</strong></p>
                                        <hr class="p-0 m-0 my-1">
                                    </div>

                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('reports.stock.adjustments.index') }}"
                                                class="bar-link">
                                                <span><i class="fas fa-sliders-h"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.stock_adjustment_report')</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if (json_decode($generalSettings->modules, true)['expenses'] == '1')
                <div class="sub-menu_t" id="expenses">
                    <div class="sub-menu-width">
                        <div class="model__close bg-secondary-2">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="text-muted float-start mt-1"><strong>@lang('Expense Management')</strong></p>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div class="row">
                                @if (auth()->user()->permission->expense['add_expense'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('expenses.create') }}" class="bar-link">
                                                <span><i class="fas fa-plus-square"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.add_expense')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->expense['expense_category'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('expenses.categories.index') }}" class="bar-link">
                                                <span><i class="fas fa-cubes"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.expense_categories')</p>
                                    </div>
                                @endif
                            </div>

                            <hr class="p-0 m-0 my-1">

                            <div class="row">
                                @if (auth()->user()->permission->expense['view_expense'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('expenses.index') }}" class="bar-link">
                                                <span><i class="far fa-list-alt"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.expense_list')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->expense['category_wise_expense'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('expenses.category.wise.expense') }}"
                                                class="bar-link">
                                                <span><i class="far fa-list-alt"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.category_wise_expenses')</p>
                                    </div>
                                @endif
                            </div>
                            @if (isset(auth()->user()->permission->expense['expense_report']) &&
                                    auth()->user()->permission->expense['expense_report'] == '1')
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <p class="text-muted mt-1 ms-3"><strong>@lang('Expense Reports')</strong></p>
                                        <hr class="p-0 m-0 my-1">
                                    </div>

                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('reports.expenses.index') }}" class="bar-link">
                                                <span><i class="far fa-money-bill-alt"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.expense_report')</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if (json_decode($generalSettings->modules, true)['accounting'] == '1')

                @if (auth()->user()->permission->accounting['ac_access'] == 1)
                    <div class="sub-menu_t" id="accounting">
                        <div class="sub-menu-width">
                            <div class="model__close bg-secondary-2">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="text-muted float-start mt-1"><strong>@lang('Account Management')</strong></p>
                                    </div>
                                </div>
                            </div>

                            <div class="container-fluid">
                                <div class="row">
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('accounting.banks.index') }}" class="bar-link">
                                                <span><i class="fas fa-university"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.bank')</p>
                                    </div>

                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('accounting.accounts.index') }}" class="bar-link">
                                                <span><i class="fas fa-money-check-alt"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.accounts')</p>
                                    </div>
                                </div>

                                <hr class="p-0 m-0 my-1">

                                <div class="row">
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('accounting.assets.index') }}" class="bar-link">
                                                <span><i class="fas fa-luggage-cart"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.assets')</p>
                                    </div>

                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('accounting.loan.index') }}" class="bar-link">
                                                <span><i class="fas fa-hand-holding-usd"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.loans')</p>
                                    </div>

                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('accounting.contras.index') }}" class="bar-link">
                                                <span><i class="fas fa-hand-holding-usd"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.contra')</p>
                                    </div>
                                </div>

                                <hr class="p-0 m-0 my-1">

                                <div class="row">
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('accounting.balance.sheet') }}" class="bar-link">
                                                <span><i class="fas fa-balance-scale"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.balance_sheet')</p>
                                    </div>

                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('accounting.trial.balance') }}" class="bar-link">
                                                <span><i class="fas fa-balance-scale-right"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.trial_balance')</p>
                                    </div>

                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('accounting.cash.flow') }}" class="bar-link">
                                                <span><i class="fas fa-money-bill-wave"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.cash_flow')</p>
                                    </div>

                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('accounting.profit.loss.account') }}"
                                                class="bar-link">
                                                <span><i class="fas fa-chart-line"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.profit_loss_account')</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <p class="text-muted mt-1 ms-3"><strong>@lang('Account Reports')</strong></p>
                                        <hr class="p-0 m-0 my-1">
                                    </div>

                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('reports.profit.loss.index') }}" class="bar-link">
                                                <span><i class="fas fa-chart-line"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.profit_loss')</p>
                                    </div>

                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('reports.financial.index') }}" class="bar-link">
                                                <span><i class="fas fa-money-bill-wave"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.financial_report')</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <div class="sub-menu_t" id="users">
                <div class="sub-menu-width">
                    <div class="model__close bg-secondary-2">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="text-muted float-start mt-1"><strong>@lang('User Management')</strong></p>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            @if (auth()->user()->permission->user['user_add'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('users.create') }}" class="bar-link">
                                            <span><i class="fas fa-user-plus"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.add_user')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->user['user_view'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('users.index') }}" class="bar-link">
                                            <span><i class="fas fa-list-ol"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.user_list')</p>
                                </div>
                            @endif
                        </div>

                        <hr class="p-0 m-0 my-1">

                        <div class="row">
                            @if (auth()->user()->permission->user['role_add'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('users.role.create') }}" class="bar-link">
                                            <span><i class="fas fa-plus-circle"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.add_role')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->user['role_view'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('users.role.index') }}" class="bar-link">
                                            <span><i class="fas fa-th-list"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.role_list')</p>
                                </div>
                            @endif
                        </div>

                        <hr class="p-0 m-0 my-1">

                    </div>
                </div>
            </div>

            @if ($addons->hrm == 1)
                <div class="sub-menu_t" id="hrm">
                    <div class="sub-menu-width">
                        <div class="model__close bg-secondary-2">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="text-muted float-start mt-1"><strong>@lang('Human Resource Management System')</strong></p>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div class="row">
                                @if (auth()->user()->permission->hrms['hrm_dashboard'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('hrm.dashboard.index') }}" class="bar-link">
                                                <span><i class="fas fa-tachometer-alt"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.hrm_dashboard')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->hrms['leave_type'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('hrm.leave.type') }}" class="bar-link">
                                                <span><i class="fas fa-th-large"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.leave_type')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->hrms['leave_assign'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('hrm.leave') }}" class="bar-link">
                                                <span><i class="fas fa-level-down-alt"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.leave')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->hrms['shift'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('hrm.attendance.shift') }}" class="bar-link">
                                                <span><i class="fas fa-network-wired"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.shift')</p>
                                    </div>
                                @endif
                            </div>

                            <hr class="p-0 m-0 my-1">

                            <div class="row">
                                @if (auth()->user()->permission->hrms['attendance'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('hrm.attendance') }}" class="bar-link">
                                                <span><i class="fas fa-paste"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.attendance')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->hrms['view_allowance_and_deduction'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('hrm.allowance') }}" class="bar-link">
                                                <span><i class="fas fa-plus"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.allowance_deduction')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->hrms['payroll'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('hrm.payroll.index') }}" class="bar-link">
                                                <span><i class="far fa-money-bill-alt"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.payroll')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->hrms['holiday'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('hrm.holidays') }}" class="bar-link">
                                                <span><i class="fas fa-toggle-off"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.holiday')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->hrms['department'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('hrm.departments') }}" class="bar-link">
                                                <span><i class="far fa-building"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.department')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->hrms['designation'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('hrm.designations') }}" class="bar-link">
                                                <span><i class="fas fa-map-marker-alt"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.designation')</p>
                                    </div>
                                @endif
                            </div>

                            @if (
                                (isset(auth()->user()->permission->hrms['payroll_report']) &&
                                    auth()->user()->permission->hrms['payroll_report'] == '1') ||
                                    (isset(auth()->user()->permission->hrms['payroll_payment_report']) &&
                                        auth()->user()->permission->hrms['payroll_payment_report'] == '1') ||
                                    (isset(auth()->user()->permission->hrms['attendance_report']) &&
                                        auth()->user()->permission->hrms['attendance_report'] == '1'))
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <p class="text-muted mt-1 ms-3"><strong>@lang('HRM Reports')</strong></p>
                                        <hr class="p-0 m-0 my-1">
                                    </div>

                                    @if (isset(auth()->user()->permission->hrms['payroll_report']) &&
                                            auth()->user()->permission->hrms['payroll_report'] == '1')
                                        <div
                                            class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                            <div class="switch_bar">
                                                <a href="{{ route('reports.payroll') }}" class="bar-link">
                                                    <span><i class="fas fa-money-bill-alt"></i></span>
                                                </a>
                                            </div>
                                            <p class="switch_text">@lang('menu.payroll_report')</p>
                                        </div>
                                    @endif

                                    @if (isset(auth()->user()->permission->hrms['payroll_payment_report']) &&
                                            auth()->user()->permission->hrms['payroll_payment_report'] == '1')
                                        <div
                                            class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                            <div class="switch_bar">
                                                <a href="{{ route('reports.payroll.payment') }}" class="bar-link">
                                                    <span><i class="fas fa-money-bill-alt"></i></span>
                                                </a>
                                            </div>
                                            <p class="switch_text">@lang('menu.payroll_payment_report')</p>
                                        </div>
                                    @endif

                                    @if (isset(auth()->user()->permission->hrms['attendance_report']) &&
                                            auth()->user()->permission->hrms['attendance_report'] == '1')
                                        <div
                                            class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                            <div class="switch_bar">
                                                <a href="{{ route('reports.attendance') }}" class="bar-link">
                                                    <span><i class="fas fa-paste"></i></span>
                                                </a>
                                            </div>
                                            <p class="switch_text">@lang('menu.attendance_report')</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="sub-menu_t" id="settings">
                <div class="sub-menu-width">
                    <div class="model__close bg-secondary-2">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="text-muted float-start mt-1"><strong>@lang('Set-up')</strong></p>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            @if (auth()->user()->permission->setup['g_settings'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('settings.general.index') }}" class="bar-link">
                                            <span><i class="fas fa-cogs"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.general_settings')</p>
                                </div>
                            @endif

                            @if ($addons->branches == 1)
                                @if (auth()->user()->permission->setup['branch'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('settings.branches.index') }}" class="bar-link">
                                                <span><i class="fas fa-project-diagram"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.branches')</p>
                                    </div>
                                @endif
                            @endif

                            @if (auth()->user()->permission->setup['warehouse'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('settings.warehouses.index') }}" class="bar-link">
                                            <span><i class="fas fa-warehouse"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.warehouses') </p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->setup['tax'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('settings.taxes.index') }}" class="bar-link">
                                            <span><i class="fas fa-percentage"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.taxes')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->setup['p_settings'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('settings.payment.method.index') }}" class="bar-link">
                                            <span><i class="fas fa-credit-card"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.payment_methods')</p>
                                </div>

                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('settings.payment.method.settings.index') }}"
                                            class="bar-link">
                                            <span><i class="fas fa-credit-card"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.payment_method_settings')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->setup['inv_sc'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('invoices.schemas.index') }}" class="bar-link">
                                            <span><i class="fas fa-file-invoice-dollar"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.invoice_schema')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->setup['inv_lay'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('invoices.layouts.index') }}" class="bar-link">
                                            <span><i class="fas fa-file-invoice"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.invoice_layout')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->setup['barcode_settings'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('settings.barcode.index') }}" class="bar-link">
                                            <span><i class="fas fa-barcode"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.barcode_settings')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->setup['cash_counters'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('settings.cash.counter.index') }}" class="bar-link">
                                            <span><i class="fas fa-store"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.cash_counter')</p>
                                </div>
                            @endif

                            <div
                                class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                <div class="switch_bar">
                                    <a href="{{ route('settings.release.note.index') }}" class="bar-link">
                                        <span><i class="far fa-arrow-alt-circle-up"></i></span>
                                    </a>
                                </div>
                                <p class="switch_text">@lang('Version Release Notes')</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($addons->manufacturing == 1)
                <div class="sub-menu_t" id="manufacture">
                    <div class="sub-menu-width">
                        <div class="model__close bg-secondary-2">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="text-muted float-start mt-1"><strong>@lang('Manufacturing')</strong></p>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div class="row">
                                @if (auth()->user()->permission->manufacturing['process_view'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('manufacturing.process.index') }}" class="bar-link">
                                                <span><i class="fas fa-dumpster-fire"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.process')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->manufacturing['production_view'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('manufacturing.productions.index') }}"
                                                class="bar-link">
                                                <span><i class="fas fa-shapes"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.productions')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->manufacturing['manuf_settings'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('manufacturing.settings.index') }}"
                                                class="bar-link">
                                                <span><i class="fas fa-sliders-h"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.manufacturing_setting')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->manufacturing['manuf_report'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('manufacturing.report.index') }}" class="bar-link">
                                                <span><i class="fas fa-file-alt"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.manufacturing_report')</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="sub-menu_t" id="essentials">
                <div class="sub-menu-width">
                    <div class="model__close bg-secondary-2">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="text-muted float-start mt-1"><strong>@lang('Task Management')</strong></p>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            @if (auth()->user()->permission->essential['assign_todo'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('todo.index') }}" class="bar-link">
                                            <span><i class="fas fa-th-list"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.todo')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->essential['work_space'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('workspace.index') }}" class="bar-link">
                                            <span><i class="fas fa-th-large"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.work_space')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->essential['memo'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('memos.index') }}" class="bar-link">
                                            <span><i class="fas fa-file-alt"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.memo')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->essential['msg'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('messages.index') }}" class="bar-link">
                                            <span><i class="fas fa-envelope"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.message')</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="sub-menu_t" id="communication">
                <div class="sub-menu-width">
                    <div class="model__close bg-secondary-2">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="text-muted float-start mt-1"><strong>@lang('Communication')</strong></p>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div
                                class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                <div class="switch_bar">
                                    <a href="#" class="bar-link">
                                        <span><i class="fas fa-exclamation"></i></span>
                                    </a>
                                </div>
                                <p class="switch_text">@lang('menu.notice_board')</p>
                            </div>
                        </div>
                        <hr class="p-0 m-0 my-1">
                        <div class="row">
                            <div
                                class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                <div class="switch_bar">
                                    <a href="#" class="bar-link">
                                        <span><i class="far fa-envelope"></i></span>
                                    </a>
                                </div>
                                <p class="switch_text">@lang('menu.email')</p>
                            </div>

                            <div
                                class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                <div class="switch_bar">
                                    <a href="{{ route('communication.email.settings') }}" class="bar-link">
                                        <span><i class="fas fa-sliders-h"></i></span>
                                    </a>
                                </div>
                                <p class="switch_text">@lang('menu.email_settings')</p>
                            </div>
                        </div>
                        <hr class="p-0 m-0 my-1">
                        <div class="row">
                            <div
                                class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                <div class="switch_bar">
                                    <a href="#" class="bar-link">
                                        <span><i class="fas fa-sms"></i></span>
                                    </a>
                                </div>
                                <p class="switch_text">@lang('menu.sms')</p>
                            </div>

                            <div
                                class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                <div class="switch_bar">
                                    <a href="{{ route('communication.sms.settings') }}" class="bar-link">
                                        <span><i class="fas fa-sliders-h"></i></span>
                                    </a>
                                </div>
                                <p class="switch_text">@lang('menu.sms_settings')</p>
                            </div>
                        </div>
                        <hr class="p-0 m-0 my-1">
                    </div>
                </div>
            </div>

            <div class="sub-menu_t" id="reports">
                <div class="sub-menu-width">
                    <div class="model__close bg-secondary-2">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="text-muted float-start mt-1"><strong>@lang('Common Reports')</strong></p>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            @if (auth()->user()->permission->report['tax_report'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('reports.taxes.index') }}" class="bar-link">
                                            <span><i class="fas fa-percent"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.tax_report')</p>
                                </div>
                            @endif

                            <div
                                class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                <div class="switch_bar">
                                    <a href="{{ route('reports.user.activities.log.index') }}" class="bar-link">
                                        <span><i class="fa fa-clipboard-list"></i></span>
                                    </a>
                                </div>
                                <p class="switch_text">@lang('menu.user_activities_log')</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
