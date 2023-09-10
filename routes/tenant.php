<?php

declare(strict_types=1);

use App\Models\AdminAndUser;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerCreditLimit;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Models\CustomerOpeningBalance;
use App\Models\SupplierOpeningBalance;
use App\Http\Controllers\Auth\ResetPasswordController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\CheckTenantForMaintenanceMode;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    // InitializeTenancyByPath::class,
    PreventAccessFromCentralDomains::class,
    CheckTenantForMaintenanceMode::class,
])->group(function () {
    // Route::get('/tenants', function () {
    //     // dd(\App\Models\User::all());
    //     return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    // });

    Auth::routes();
    Route::post('change-current-password', [ResetPasswordController::class, 'resetCurrentPassword'])->name('password.updateCurrent');
    Route::get('maintenance/mode', fn () => view('maintenance/maintenance'))->name('maintenance.mode');
    Route::get('pin_login', fn () => view('auth.pin_login'));

    Route::get('change/lang/{lang}', 'App\Http\Controllers\DashboardController@changeLang')->name('change.lang');

    Route::get('/', 'App\Http\Controllers\DashboardController@index')->name('dashboard.dashboard');
    Route::get('dashboard/card/amount', 'App\Http\Controllers\DashboardController@cardData')->name('dashboard.card.data');
    Route::get('dashboard/stock/alert', 'App\Http\Controllers\DashboardController@stockAlert')->name('dashboard.stock.alert');
    Route::get('dashboard/sale/order', 'App\Http\Controllers\DashboardController@saleOrder')->name('dashboard.sale.order');
    Route::get('dashboard/sale/due', 'App\Http\Controllers\DashboardController@saleDue')->name('dashboard.sale.due');
    Route::get('dashboard/purchase/due', 'App\Http\Controllers\DashboardController@purchaseDue')->name('dashboard.purchase.due');
    Route::get('dashboard/today/summery', 'App\Http\Controllers\DashboardController@todaySummery')->name('dashboard.today.summery');

    Route::group(['prefix' => 'common/ajax/call', 'namespace' => 'App\Http\Controllers'], function () {
        Route::get('branch/authenticated/users/{branchId}', 'CommonAjaxCallController@branchAuthenticatedUsers');
        Route::get('category/subcategories/{categoryId}', 'CommonAjaxCallController@categorySubcategories');
        Route::get('only/search/product/for/reports/{product_name}', 'CommonAjaxCallController@onlySearchProductForReports')->name('common.ajax.call.search.products.only.for.report.filter');
        Route::get('search/final/sale/invoices/{invoiceId}', 'CommonAjaxCallController@searchFinalSaleInvoices');
        Route::get('get/sale/products/{saleId}', 'CommonAjaxCallController@getSaleProducts');
        Route::get('customer_info/{customerId}', 'CommonAjaxCallController@customerInfo');
        Route::get('recent/sales/{create_by}', 'CommonAjaxCallController@recentSale');
        Route::get('recent/quotations/{create_by}', 'CommonAjaxCallController@recentQuotations');
        Route::get('recent/drafts/{create_by}', 'CommonAjaxCallController@recentDrafts');
        Route::get('branch/warehouse/{branch_id}', 'CommonAjaxCallController@branchWarehouses');
        Route::get('branch/allow/login/users/{branchId}', 'CommonAjaxCallController@branchAllowLoginUsers');
        Route::get('branch/users/{branchId}', 'CommonAjaxCallController@branchUsers');
        Route::get('get/supplier/{supplierId}', 'CommonAjaxCallController@getSupplier');
    });

    //Product section route group
    Route::group(['prefix' => 'product', 'namespace' => 'App\Http\Controllers'], function () {
        // Branch route group
        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', 'CategoryController@index')->name('product.categories.index');
            Route::get('form/category', 'CategoryController@getAllFormCategory')->name('product.categories.all.category.form');
            Route::post('store', 'CategoryController@store')->name('product.categories.store');
            Route::get('edit/{categoryId}', 'CategoryController@edit')->name('product.categories.edit');
            Route::post('update', 'CategoryController@update')->name('product.categories.update');
            Route::delete('delete/{categoryId}', 'CategoryController@delete')->name('product.categories.delete');
        });

        Route::group(['prefix' => 'sub-categories'], function () {
            Route::get('/', 'SubCategoryController@index')->name('product.subcategories.index');
            Route::post('store', 'SubCategoryController@store')->name('product.subcategories.store');
            Route::post('update', 'SubCategoryController@update')->name('product.subcategories.update');
            Route::delete('delete/{categoryId}', 'SubCategoryController@delete')->name('product.subcategories.delete');
            Route::get('edit/{id}', 'SubCategoryController@edit');
        });

        // Brand route group
        Route::group(['prefix' => 'brands'], function () {
            Route::get('/', 'BrandController@index')->name('product.brands.index');
            Route::get('all', 'BrandController@getAllBrand')->name('product.brands.all.brand');
            Route::post('store', 'BrandController@store')->name('product.brands.store');
            Route::post('update', 'BrandController@update')->name('product.brands.update');
            Route::delete('delete/{brandId}', 'BrandController@delete')->name('product.brands.delete');
            Route::get('edit/{id}', 'BrandController@edit')->name('product.brands.edit');
        });

        // Products route group
        Route::group(['prefix' => '/'], function () {
            Route::get('all', 'ProductController@allProduct')->name('products.all.product');
            Route::get('view/{productId}', 'ProductController@view')->name('products.view');
            Route::get('get/all/product', 'ProductController@getAllProduct')->name('products.get.all.product');
            Route::get('add', 'ProductController@create')->name('products.add.view');
            Route::get('get/form/part/{type}', 'ProductController@getFormPart');
            Route::post('store', 'ProductController@store')->name('products.add.store');
            Route::get('edit/{productId}', 'ProductController@edit')->name('products.edit');
            Route::get('product/variants/{productId}', 'ProductController@getProductVariants')->name('products.get.product.variants');
            Route::get('combo/product/{productId}', 'ProductController@getComboProducts')->name('products.get.combo.products');
            Route::post('update/{productId}', 'ProductController@update')->name('products.update');
            Route::get('default/profit', 'ProductController@defaultProfit')->name('products.add.get.default.profit');
            Route::delete('delete/{productId}', 'ProductController@delete')->name('products.delete');
            Route::delete('multiple/delete', 'ProductController@multipleDelete')->name('products.multiple.delete');
            Route::get('all/form/variant', 'ProductController@getAllFormVariants')->name('products.add.get.all.from.variant');
            Route::get('search/product/{productCode}', 'ProductController@searchProduct');
            Route::get('get/product/stock/{productId}', 'ProductController@getProductStock');
            Route::get('change/status/{productId}', 'ProductController@changeStatus')->name('products.change.status');
            Route::get('check/purchase/generate/barcode/{productId}', 'ProductController@chackPurchaseAndGenerateBarcode')->name('products.check.purchase.and.generate.barcode');
            Route::get('get/opening/stock/{productId}', 'ProductController@openingStock')->name('products.opening.stock');
            Route::get('add/price/groups/{productId}/{type}', 'ProductController@addPriceGroup')->name('products.add.price.groups');
            Route::post('save/price/groups', 'ProductController@savePriceGroup')->name('products.save.price.groups');
            Route::post('opening/stock/update', 'ProductController@openingStockUpdate')->name('products.opening.stock.update');
            Route::post('add/category', 'ProductController@addCategory')->name('products.add.category');
            Route::post('add/brand', 'ProductController@addBrand')->name('products.add.brand');
            Route::post('add/unit', 'ProductController@addUnit')->name('products.add.unit');
            Route::post('add/warranty', 'ProductController@addWarranty')->name('products.add.warranty');
            Route::get('settings', 'ProductController@settings')->name('products.settings');
            Route::post('settings/store', 'ProductController@settingsStore')->name('products.settings.store');

            Route::group(['prefix' => 'import/price/group/products'], function () {
                Route::get('export', 'ImportPriceGroupProductController@export')->name('products.export.price.group.products');
            });
        });

        // Selling price group route group
        Route::group(['prefix' => 'selling/price/groups'], function () {
            Route::get('/', 'PriceGroupController@index')->name('product.selling.price.groups.index');
            Route::post('store', 'PriceGroupController@store')->name('product.selling.price.groups.store');
            Route::get('edit/{id}', 'PriceGroupController@edit')->name('product.selling.price.groups.edit');
            Route::post('update/{id}', 'PriceGroupController@update')->name('product.selling.price.groups.update');
            Route::delete('delete/{id}', 'PriceGroupController@delete')->name('product.selling.price.groups.delete');
            Route::get('change/status/{id}', 'PriceGroupController@changeStatus')->name('product.selling.price.groups.change.status');
        });

        // Variants route group
        Route::group(['prefix' => 'variants'], function () {
            Route::get('/', 'BulkVariantController@index')->name('product.variants.index');
            Route::get('all', 'BulkVariantController@getAllVariant')->name('product.variants.all.variant');
            Route::post('store', 'BulkVariantController@store')->name('product.variants.store');
            Route::post('update', 'BulkVariantController@update')->name('product.variants.update');
            Route::delete('delete/{id}', 'BulkVariantController@delete')->name('product.variants.delete');
        });

        // Barcode route group
        Route::group(['prefix' => 'barcode'], function () {
            Route::get('/', 'BarcodeController@index')->name('barcode.index');
            Route::post('preview', 'BarcodeController@preview')->name('barcode.preview');
            Route::get('supplier/products', 'BarcodeController@supplierProduct')->name('barcode.supplier.get.products');
            Route::post('multiple/generate/completed', 'BarcodeController@multipleGenerateCompleted')->name('barcode.multiple.generate.completed');
            Route::get('search/product/{searchKeyword}', 'BarcodeController@searchProduct');
            Route::get('get/selected/product/{productId}', 'BarcodeController@getSelectedProduct');
            Route::get('get/selected/product/variant/{productId}/{variantId}', 'BarcodeController@getSelectedProductVariant');
            Route::get('generate/product/barcode/{productId}', 'BarcodeController@genrateProductBarcode')->name('products.generate.product.barcode');
            Route::get('get/spacific/supplier/product/{productId}', 'BarcodeController@getSpacificSupplierProduct')->name('barcode.get.spacific.supplier.product');

            // Generate bar-codes on purchase.
            Route::get('purchase/products/{purchaseId}', 'BarcodeController@onPurchaseBarcode')->name('barcode.on.purchase.barcode');
            Route::get('get/purchase/products/{purchaseId}', 'BarcodeController@getPurchaseProduct')->name('barcode.get.purchase.products');
        });

        // Import product route group
        Route::group(['prefix' => 'imports'], function () {
            Route::get('create', 'ProductImportController@create')->name('product.import.create');
            Route::post('store', 'ProductImportController@store')->name('product.import.store');
        });

        // Warranty route group
        Route::group(['prefix' => 'warranties'], function () {
            Route::get('/', 'WarrantyController@index')->name('product.warranties.index');
            Route::get('all', 'WarrantyController@allWarranty')->name('product.warranties.all.warranty');
            Route::post('store', 'WarrantyController@store')->name('product.warranties.store');
            Route::post('update', 'WarrantyController@update')->name('product.warranties.update');
            Route::delete('delete/{warrantyId}', 'WarrantyController@delete')->name('product.warranties.delete');
        });

        Route::group(['prefix' => 'reports', 'namespace' => 'report'], function () {

            Route::group(['prefix' => 'stock'], function () {
                Route::get('/', 'StockReportController@index')->name('reports.stock.index');
                Route::get('print/branch/stocks', 'StockReportController@printBranchStock')->name('reports.stock.print.branch.stock');
                Route::get('warehouse/stock', 'StockReportController@warehouseStock')->name('reports.stock.warehouse.stock');
                Route::get('all/parent/categories', 'StockReportController@allParentCategories')->name('reports.stock.all.parent.categories');
            });

            Route::group(['prefix' => 'stock/in/out'], function () {
                Route::get('/', 'StockInOutReportController@index')->name('reports.stock.in.out.index');
                Route::get('print', 'StockInOutReportController@print')->name('reports.stock.in.out.print');
            });
        });
    });

    // Contact route group
    Route::group(['prefix' => 'contacts', 'namespace' => 'App\Http\Controllers'], function () {
        // Supplier route group
        Route::group(['prefix' => 'suppliers'], function () {

            Route::get('/', 'SupplierController@index')->name('contacts.supplier.index');
            Route::get('add', 'SupplierController@create')->name('contacts.supplier.create');
            Route::post('store', 'SupplierController@store')->name('contacts.supplier.store');
            Route::get('edit/{supplierId}', 'SupplierController@edit')->name('contacts.supplier.edit');
            Route::post('update', 'SupplierController@update')->name('contacts.supplier.update');
            Route::delete('delete/{supplierId}', 'SupplierController@delete')->name('contacts.supplier.delete');
            Route::get('change/status/{supplierId}', 'SupplierController@changeStatus')->name('contacts.supplier.change.status');
            Route::get('view/{supplierId}', 'SupplierController@view')->name('contacts.supplier.view');
            Route::get('uncompleted/orders/{supplierId}', 'SupplierController@uncompletedOrders')->name('suppliers.uncompleted.orders');
            Route::get('ledgers/{supplierId}', 'SupplierController@ledgers')->name('contacts.supplier.ledgers');
            Route::get('print/ledger/{supplierId}', 'SupplierController@ledgerPrint')->name('contacts.supplier.ledger.print');
            Route::get('all/payment/list/{supplierId}', 'SupplierController@allPaymentList')->name('suppliers.all.payment.list');
            Route::get('all/payment/print/{supplierId}', 'SupplierController@allPaymentPrint')->name('suppliers.all.payment.print');
            Route::get('payment/{supplierId}', 'SupplierController@payment')->name('suppliers.payment');
            Route::post('payment/{supplierId}', 'SupplierController@paymentAdd')->name('suppliers.payment.add');
            Route::get('return/payment/{supplierId}', 'SupplierController@returnPayment')->name('suppliers.return.payment');
            Route::post('return/payment/{supplierId}', 'SupplierController@returnPaymentAdd')->name('suppliers.return.payment.add');
            Route::get('payment/details/{paymentId}', 'SupplierController@paymentDetails')->name('suppliers.view.details');
            Route::delete('payment/delete/{paymentId}', 'SupplierController@paymentDelete')->name('suppliers.payment.delete');
            Route::get('amountsBranchWise/{supplierId}', 'SupplierController@supplierAmountsBranchWise')->name('contacts.supplier.amounts.branch.wise');

            Route::group(['prefix' => 'import'], function () {
                Route::get('/', 'SupplierImportController@create')->name('contacts.suppliers.import.create');
                Route::post('store', 'SupplierImportController@store')->name('contacts.suppliers.import.store');
            });
        });

        // Customers route group
        Route::group(['prefix' => 'customers'], function () {

            Route::get('/', 'CustomerController@index')->name('contacts.customer.index');
            Route::get('add', 'CustomerController@create')->name('contacts.customer.create');
            Route::post('store', 'CustomerController@store')->name('contacts.customer.store');
            Route::post('addOpeningBalance', 'CustomerController@addOpeningBalance')->name('contacts.customer.add.opening.balance');
            Route::get('edit/{customerId}', 'CustomerController@edit')->name('contacts.customer.edit');
            Route::post('update', 'CustomerController@update')->name('contacts.customer.update');
            Route::delete('delete/{customerId}', 'CustomerController@delete')->name('contacts.customer.delete');
            Route::get('change/status/{customerId}', 'CustomerController@changeStatus')->name('contacts.customer.change.status');
            Route::get('view/{customerId}', 'CustomerController@view')->name('contacts.customer.view');
            Route::get('ledgers/list/{customerId}', 'CustomerController@ledgerList')->name('contacts.customer.ledger.list');
            Route::get('print/ledger/{customerId}', 'CustomerController@ledgerPrint')->name('contacts.customer.ledger.print');
            Route::get('payment/{customerId}', 'CustomerController@payment')->name('customers.payment');
            Route::post('payment/{customerId}', 'CustomerController@paymentAdd')->name('customers.payment.add');

            Route::get('return/payment/{customerId}', 'CustomerController@returnPayment')->name('customers.return.payment');
            Route::post('return/payment/{customerId}', 'CustomerController@returnPaymentAdd')->name('customers.return.payment.add');

            Route::get('all/payment/list/{customerId}', 'CustomerController@allPaymentList')->name('customers.all.payment.list');
            Route::get('all/payment/print/{customerId}', 'CustomerController@allPaymentPrint')->name('customers.all.payment.print');
            Route::get('payment/details/{paymentId}', 'CustomerController@paymentDetails')->name('customers.view.details');
            Route::delete('payment/delete/{paymentId}', 'CustomerController@paymentDelete')->name('customers.payment.delete');
            Route::get('amountsBranchWise/{customerId}', 'CustomerController@customerAmountsBranchWise')->name('contacts.customer.amounts.branch.wise');

            Route::group(['prefix' => 'money/receipt'], function () {
                Route::get('/voucher/list/{customerId}', 'MoneyReceiptController@moneyReceiptList')->name('money.receipt.voucher.list');
                Route::get('create/{customerId}', 'MoneyReceiptController@moneyReceiptCreate')->name('money.receipt.voucher.create');
                Route::post('store/{customerId}', 'MoneyReceiptController@store')->name('money.receipt.voucher.store');
                Route::get('edit/{receiptId}', 'MoneyReceiptController@edit')->name('money.receipt.voucher.edit');
                Route::post('update/{receiptId}', 'MoneyReceiptController@update')->name('money.receipt.voucher.update');
                Route::get('voucher/print/{receiptId}', 'MoneyReceiptController@moneyReceiptPrint')->name('money.receipt.voucher.print');
                Route::get('voucher/status/change/modal/{receiptId}', 'MoneyReceiptController@changeStatusModal')->name('money.receipt.voucher.status.change.modal');
                Route::post('voucher/status/change/{receiptId}', 'MoneyReceiptController@changeStatus')->name('money.receipt.voucher.status.change');
                Route::delete('voucher/delete/{receiptId}', 'MoneyReceiptController@delete')->name('money.receipt.voucher.delete');
            });

            Route::group(['prefix' => 'groups'], function () {
                Route::get('/', 'CustomerGroupController@index')->name('contacts.customers.groups.index');
                Route::get('all/groups', 'CustomerGroupController@allBanks')->name('contacts.customers.groups.all.group');
                Route::post('store', 'CustomerGroupController@store')->name('contacts.customers.groups.store');
                Route::post('update', 'CustomerGroupController@update')->name('contacts.customers.groups.update');
                Route::delete('delete/{groupId}', 'CustomerGroupController@delete')->name('customers.groups.delete');
            });

            Route::group(['prefix' => 'import'], function () {
                Route::get('/', 'CustomerImportController@create')->name('contacts.customers.import.create');
                Route::post('store', 'CustomerImportController@store')->name('contacts.customers.import.store');
            });
        });

        Route::group(['prefix' => 'reports', 'namespace' => 'report'], function () {

            Route::group(['prefix' => 'suppliers'], function () {
                Route::get('/', 'SupplierReportController@index')->name('reports.supplier.index');
                Route::get('print', 'SupplierReportController@print')->name('reports.supplier.print');
            });

            Route::group(['prefix' => 'customers'], function () {
                Route::get('/', 'CustomerReportController@index')->name('reports.customer.index');
                Route::get('print', 'CustomerReportController@print')->name('reports.customer.print');
            });
        });
    });

    // Purchase route group
    Route::group(['prefix' => 'purchases', 'namespace' => 'App\Http\Controllers'], function () {

        Route::get('v2', 'PurchaseController@index_v2')->name('purchases.index_v2');
        Route::get('product/list', 'PurchaseController@purchaseProductList')->name('purchases.product.list');
        Route::get('po/list', 'PurchaseController@poList')->name('purchases.po.list');
        Route::get('show/{purchaseId}', 'PurchaseController@show')->name('purchases.show');
        Route::get('order/show/{purchaseId}', 'PurchaseController@showOrder')->name('purchases.show.order');
        Route::get('order/print/supplier/copy/{purchaseId}', 'PurchaseController@printSupplierCopy')->name('purchases.order.supplier.copy.print');
        Route::get('create', 'PurchaseController@create')->name('purchases.create');
        Route::post('store', 'PurchaseController@store')->name('purchases.store');
        Route::get('edit/{purchaseId}/{editType}', 'PurchaseController@edit')->name('purchases.edit');
        Route::get('editable/purchase/{purchaseId}/{editType}', 'PurchaseController@editablePurchase')->name('purchases.get.editable.purchase');
        Route::post('update/{editType}', 'PurchaseController@update')->name('purchases.update');
        Route::get('get/all/supplier', 'PurchaseController@getAllSupplier')->name('purchases.get.all.supplier');
        Route::get('get/all/unit', 'PurchaseController@getAllUnit')->name('purchases.get.all.unites');
        Route::get('get/all/tax', 'PurchaseController@getAllTax')->name('purchases.get.all.taxes');
        Route::get('search/product/{product_code}', 'PurchaseController@searchProduct');
        Route::delete('delete/{purchaseId}', 'PurchaseController@delete')->name('purchase.delete');
        Route::post('add/supplier', 'PurchaseController@addSupplier')->name('purchases.add.supplier');
        Route::get('add/product/modal/view', 'PurchaseController@addProductModalVeiw')->name('purchases.add.product.modal.view');
        Route::post('add/product', 'PurchaseController@addProduct')->name('purchases.add.product');
        Route::get('recent/product/{productId}', 'PurchaseController@getRecentProduct');
        Route::get('add/quick/supplier/modal', 'PurchaseController@addQuickSupplierModal')->name('purchases.add.quick.supplier.modal');
        Route::get('payment/modal/{purchaseId}', 'PurchaseController@paymentModal')->name('purchases.payment.modal');
        Route::post('payment/store/{purchaseId}', 'PurchaseController@paymentStore')->name('purchases.payment.store');
        Route::get('payment/edit/{paymentId}', 'PurchaseController@paymentEdit')->name('purchases.payment.edit');
        Route::post('payment/update/{paymentId}', 'PurchaseController@paymentUpdate')->name('purchases.payment.update');
        Route::get('return/payment/modal/{purchaseId}', 'PurchaseController@returnPaymentModal')->name('purchases.return.payment.modal');
        Route::post('return/payment/store/{purchaseId}', 'PurchaseController@returnPaymentStore')->name('purchases.return.payment.store');
        Route::get('return/payment/edit/{paymentId}', 'PurchaseController@returnPaymentEdit')->name('purchases.return.payment.edit');
        Route::post('return/payment/update/{paymentId}', 'PurchaseController@returnPaymentUpdate')->name('purchases.return.payment.update');
        Route::get('payment/details/{paymentId}', 'PurchaseController@paymentDetails')->name('purchases.payment.details');
        Route::delete('payment/delete/{paymentId}', 'PurchaseController@paymentDelete')->name('purchases.payment.delete');
        Route::get('payment/list/{purchaseId}', 'PurchaseController@paymentList')->name('purchase.payment.list');
        Route::get('settings', 'PurchaseController@settings')->name('purchase.settings');
        Route::post('settings/store', 'PurchaseController@settingsStore')->name('purchase.settings.store');

        Route::group(['prefix' => '/'], function () {
            Route::get('po/process/receive/{purchaseId}', 'PurchaseOrderReceiveController@processReceive')->name('purchases.po.receive.process');
            Route::post('po/process/receive/store/{purchaseId}', 'PurchaseOrderReceiveController@processReceiveStore')->name('purchases.po.receive.process.store');
        });

        // Purchase Return route
        Route::group(['prefix' => 'returns'], function () {
            Route::get('/', 'PurchaseReturnController@index')->name('purchases.returns.index');
            Route::get('show/{returnId}', 'PurchaseReturnController@show')->name('purchases.returns.show');
            Route::get('add/{purchaseId}', 'PurchaseReturnController@create')->name('purchases.returns.create');
            Route::get('get/purchase/{purchaseId}', 'PurchaseReturnController@getPurchase')->name('purchases.returns.get.purchase');
            Route::post('store/{purchaseId}', 'PurchaseReturnController@store')->name('purchases.returns.store');
            Route::delete('delete/{purchaseReturnId}', 'PurchaseReturnController@delete')->name('purchases.returns.delete');
            Route::get('create', 'PurchaseReturnController@supplierReturn')->name('purchases.returns.supplier.return');
            Route::get('search/product/{productCode}/{warehouseId}', 'PurchaseReturnController@searchProduct');
            Route::get('check/single/product/stock/{product_id}/{warehouse_id}', 'PurchaseReturnController@checkSingleProductStock');
            Route::get('check/variant/product/stock/{product_id}/{variant_id}/{warehouse_id}', 'PurchaseReturnController@checkVariantProductStock');
            Route::post('supplier/return/store', 'PurchaseReturnController@supplierReturnStore')->name('purchases.returns.supplier.return.store');
            Route::get('supplier/return/edit/{purchaseReturnId}', 'PurchaseReturnController@supplierReturnEdit')->name('purchases.returns.supplier.return.edit');
            Route::get('get/editable/supplierReturn/{purchaseReturnId}', 'PurchaseReturnController@getEditableSupplierReturn')->name('purchases.return.get.editable.supplier.return');
            Route::post('supplier/return/update/{purchaseReturnId}', 'PurchaseReturnController@supplierReturnUpdate')->name('purchases.returns.supplier.return.update');
            Route::post('return/payments/{returnId}', 'PurchaseReturnController@returnPaymentList')->name('purchases.returns.purchase.return.payment.list');
        });

        Route::group(['prefix' => 'reports', 'namespace' => 'report'], function () {

            Route::group(['prefix' => 'purchase/statements'], function () {
                Route::get('/', 'PurchaseStatementController@index')->name('reports.purchases.statement.index');
                Route::get('print', 'PurchaseStatementController@print')->name('reports.purchases.statement.print');
            });

            Route::group(['prefix' => 'product/purchases'], function () {
                Route::get('/', 'ProductPurchaseReportController@index')->name('reports.product.purchases.index');
                Route::get('print', 'ProductPurchaseReportController@print')->name('reports.product.purchases.print');
            });

            Route::group(['prefix' => 'purchase/payments'], function () {
                Route::get('/', 'PurchasePaymentReportController@index')->name('reports.purchase.payments.index');
                Route::get('print', 'PurchasePaymentReportController@print')->name('reports.purchase.payments.print');
            });

            Route::group(['prefix' => 'sales/purchase'], function () {
                Route::get('/', 'SalePurchaseReportController@index')->name('reports.sales.purchases.index');
                Route::get('sale/purchase/amounts', 'SalePurchaseReportController@salePurchaseAmounts')->name('reports.profit.sales.purchases.amounts');
                Route::get('filter/sale/purchase/amounts', 'SalePurchaseReportController@filterSalePurchaseAmounts')->name('reports.profit.sales.filter.purchases.amounts');
                Route::get('print', 'SalePurchaseReportController@printSalePurchase')->name('reports.sales.purchases.print');
            });
        });
    });

    // Sale route group sales/recent/sales
    Route::group(['prefix' => 'sales', 'namespace' => 'App\Http\Controllers'], function () {

        Route::get('v2', 'SaleController@index2')->name('sales.index2');
        Route::get('pos/list', 'SaleController@posList')->name('sales.pos.list');
        Route::get('product/list', 'SaleController@soldProductList')->name('sales.product.list');
        Route::get('show/{saleId}', 'SaleController@show')->name('sales.show');
        Route::get('pos/show/{saleId}', 'SaleController@posShow')->name('sales.pos.show');
        Route::get('print/{saleId}', 'SaleController@print')->name('sales.print');
        Route::get('packing/Slip/{saleId}', 'SaleController@packingSlip')->name('sales.packing.slip');
        Route::get('drafts', 'SaleController@drafts')->name('sales.drafts');
        Route::get('draft/details/{draftId}', 'SaleController@draftDetails')->name('sales.drafts.details');
        Route::get('sales/order/list', 'SaleController@salesOrderList')->name('sales.order.list');
        Route::get('quotations', 'SaleController@quotations')->name('sales.quotations');
        Route::get('quotation/details/{quotationId}', 'SaleController@quotationDetails')->name('sales.quotations.details');
        Route::get('create', 'SaleController@create')->name('sales.create');
        Route::post('store', 'SaleController@store')->name('sales.store');
        Route::get('edit/{saleId}', 'SaleController@edit')->name('sales.edit');
        Route::post('update/{saleId}', 'SaleController@update')->name('sales.update');
        Route::get('get/all/customer', 'SaleController@getAllCustomer')->name('sales.get.all.customer');
        Route::get('get/all/users', 'SaleController@getAllUser')->name('sales.get.all.users');
        Route::get('get/all/unit', 'SaleController@getAllUnit')->name('sales.get.all.unites');
        Route::get('get/all/tax', 'SaleController@getAllTax')->name('sales.get.all.taxes');
        Route::get('search/product/{status}/{product_code}/{price_group_id}/{warehouse_id}', 'SaleController@searchProduct');
        Route::delete('delete/{saleId}', 'SaleController@delete')->name('sales.delete');
        Route::get('edit/shipment/{saleId}', 'SaleController@editShipment')->name('sales.shipment.edit');
        Route::post('update/shipment/{saleId}', 'SaleController@updateShipment')->name('sales.shipment.update');
        Route::post('change/status/{saleId}', 'SaleController@changeStatus')->name('sales.change.status');
        Route::get('check/branch/variant/qty/{status}/{product_id}/{variant_id}/{price_group_id}/{warehouse_id}', 'SaleController@checkVariantProductStock');
        Route::get('check/single/product/stock/{status}/{product_id}/{price_group_id}/{warehouse_id}', 'SaleController@checkSingleProductStock');

        Route::get('shipments', 'SaleController@shipments')->name('sales.shipments');

        // Sale payment route
        Route::get('payment/{saleId}', 'SaleController@paymentModal')->name('sales.payment.modal');
        Route::post('payment/add/{saleId}', 'SaleController@paymentAdd')->name('sales.payment.add');

        Route::get('payment/view/{saleId}', 'SaleController@viewPayment')->name('sales.payment.view');
        Route::get('payment/edit/{paymentId}', 'SaleController@paymentEdit')->name('sales.payment.edit');
        Route::post('payment/update/{paymentId}', 'SaleController@paymentUpdate')->name('sales.payment.update');
        Route::get('payment/details/{paymentId}', 'SaleController@paymentDetails')->name('sales.payment.details');

        Route::delete('payment/delete/{paymentId}', 'SaleController@paymentDelete')->name('sales.payment.delete');

        Route::get('return/payment/{saleId}', 'SaleController@returnPaymentModal')->name('sales.return.payment.modal');
        Route::post('return/payment/add/{saleId}', 'SaleController@returnPaymentAdd')->name('sales.return.payment.add');
        Route::get('return/payment/edit/{paymentId}', 'SaleController@returnPaymentEdit')->name('sales.return.payment.edit');
        Route::post('return/payment/update/{paymentId}', 'SaleController@returnPaymentUpdate')->name('sales.return.payment.update');

        Route::get('add/product/modal/view', 'SaleController@addProductModalVeiw')->name('sales.add.product.modal.view');
        Route::post('add/product', 'SaleController@addProduct')->name('sales.add.product');
        Route::get('get/recent/product/{product_id}', 'SaleController@getRecentProduct');
        Route::get('get/product/price/group', 'SaleController@getProductPriceGroup')->name('sales.product.price.groups');

        Route::get('notification/form/{saleId}', 'SaleController@getNotificationForm')->name('sales.notification.form');

        Route::get('settings', 'SaleController@settings')->name('sales.add.sale.settings');
        Route::post('settings/store', 'SaleController@settingsStore')->name('sales.add.sale.settings.store');

        // Sale return route
        Route::group(['prefix' => 'returns'], function () {

            Route::get('/', 'SaleReturnController@index')->name('sales.returns.index');
            Route::get('show/{returnId}', 'SaleReturnController@show')->name('sales.returns.show');

            Route::delete('delete/{saleReturnId}', 'SaleReturnController@delete')->name('sales.returns.delete');
            Route::get('payment/list/{returnId}', 'SaleReturnController@returnPaymentList')->name('sales.returns.payment.list');

            Route::group(['prefix' => 'random'], function () {

                Route::get('create', 'RandomSaleReturnController@create')->name('sale.return.random.create');
                Route::post('store', 'RandomSaleReturnController@store')->name('sale.return.random.store');
                Route::get('edit/{returnId}', 'RandomSaleReturnController@edit')->name('sale.return.random.edit');
                Route::post('update/{returnId}', 'RandomSaleReturnController@update')->name('sale.return.random.update');
                Route::get('search/product/{product_code}', 'RandomSaleReturnController@searchProduct');
            });
        });

        //Pos cash register routes
        Route::group(['prefix' => 'cash/register'], function () {

            Route::get('/', 'CashRegisterController@create')->name('sales.cash.register.create');
            Route::post('store', 'CashRegisterController@store')->name('sales.cash.register.store');
            Route::get('close/cash/register/modal/view', 'CashRegisterController@closeCashRegisterModalView')->name('sales.cash.register.close.modal.view');
            Route::get('cash/register/details', 'CashRegisterController@cashRegisterDetails')->name('sales.cash.register.details');
            Route::get('cash/register/details/for/report/{crId}', 'CashRegisterController@cashRegisterDetailsForReport')->name('sales.cash.register.details.for.report');
            Route::post('close', 'CashRegisterController@close')->name('sales.cash.register.close');
        });

        // Pos routes
        Route::group(['prefix' => 'pos'], function () {

            Route::get('create', 'POSController@create')->name('sales.pos.create');
            Route::get('product/list', 'POSController@posProductList')->name('sales.pos.product.list');
            Route::post('store', 'POSController@store')->name('sales.pos.store');
            Route::get('pick/hold/invoice', 'POSController@pickHoldInvoice');
            Route::get('edit/{saleId}', 'POSController@edit')->name('sales.pos.edit');
            Route::get('invoice/products/{saleId}', 'POSController@invoiceProducts')->name('sales.pos.invoice.products');
            Route::post('update', 'POSController@update')->name('sales.pos.update');
            Route::get('suspended/sale/list', 'POSController@suspendedList')->name('sales.pos.suspended.list');
            Route::get('branch/stock', 'POSController@branchStock')->name('sales.pos.branch.stock');
            Route::get('add/customer/modal', 'POSController@addQuickCustomerModal')->name('sales.pos.add.quick.customer.modal');
            Route::post('add/customer', 'POSController@addCustomer')->name('sales.pos.add.customer');
            Route::get('get/recent/product/{product_id}', 'POSController@getRecentProduct');
            Route::get('close/cash/registser/modal/view', 'POSController@close');
            Route::get('search/exchangeable/invoice', 'POSController@searchExchangeableInv')->name('sales.pos.serc.ex.inv');
            Route::post('prepare/exchange', 'POSController@prepareExchange')->name('sales.pos.prepare.exchange');
            Route::post('exchange/confirm', 'POSController@exchangeConfirm')->name('sales.pos.exchange.confirm');
            Route::get('settings', 'POSController@settings')->name('sales.pos.settings');
            Route::post('settings/store', 'POSController@settingsStore')->name('sales.pos.settings.store');
        });

        //Sale discount routes
        Route::group(['prefix' => 'discounts'], function () {

            Route::get('/', 'DiscountController@index')->name('sales.discounts.index');
            Route::post('store', 'DiscountController@store')->name('sales.discounts.store');
            Route::get('edit/{discountId}', 'DiscountController@edit')->name('sales.discounts.edit');
            Route::post('update/{discountId}', 'DiscountController@update')->name('sales.discounts.update');
            Route::get('change/status/{discountId}', 'DiscountController@changeStatus')->name('sales.discounts.change.status');
            Route::delete('delete/{discountId}', 'DiscountController@delete')->name('sales.discounts.delete');
        });

        Route::group(['prefix' => 'reports', 'namespace' => 'report'], function () {

            Route::group(['prefix' => 'sold/products'], function () {

                Route::get('/', 'ProductSaleReportController@index')->name('reports.product.sales.index');
                Route::get('print', 'ProductSaleReportController@print')->name('reports.product.sales.print');
            });

            Route::group(['prefix' => 'received/payments'], function () {

                Route::get('/', 'SalePaymentReportController@index')->name('reports.sale.payments.index');
                Route::get('print', 'SalePaymentReportController@print')->name('reports.sale.payments.print');
            });

            Route::group(['prefix' => 'cash/registers'], function () {

                Route::get('/', 'CashRegisterReportController@index')->name('reports.cash.registers.index');
                Route::get('get', 'CashRegisterReportController@getCashRegisterReport')->name('reports.get.cash.registers');
                Route::get('details/{cashRegisterId}', 'CashRegisterReportController@detailsCashRegister')->name('reports.get.cash.register.details');
                Route::get('report/print', 'CashRegisterReportController@reportPrint')->name('reports.get.cash.register.report.print');
            });

            Route::group(['prefix' => 'sale/representative'], function () {

                Route::get('/', 'SaleRepresentiveReportController@index')->name('reports.sale.representive.index');
                Route::get('expenses', 'SaleRepresentiveReportController@SaleRepresentiveExpenseReport')->name('reports.sale.representive.expenses');
            });

            Route::group(['prefix' => 'sale/statements'], function () {

                Route::get('/', 'SaleStatementController@index')->name('reports.sale.statement.index');
                Route::get('print', 'SaleStatementController@print')->name('reports.sale.statement.print');
            });

            Route::group(['prefix' => 'return/statements'], function () {

                Route::get('/', 'SaleReturnStatementController@index')->name('reports.sale.return.statement.index');
                Route::get('print', 'SaleReturnStatementController@print')->name('reports.sale.return.statement.print');
            });
        });
    });

    //Transfer stock to branch all route
    Route::group(['prefix' => 'transfer/stocks', 'namespace' => 'App\Http\Controllers'], function () {

        Route::get('/', 'TransferToBranchController@index')->name('transfer.stock.to.branch.index');
        Route::get('show/{transferId}', 'TransferToBranchController@show')->name('transfer.stock.to.branch.show');
        Route::get('transfer/products/{transferId}', 'TransferToBranchController@transferProduct');
        Route::get('all/transfer/', 'TransferToBranchController@allTransfer')->name('transfer.stock.to.branch.all.transfer');
        Route::get('create', 'TransferToBranchController@create')->name('transfer.stock.to.branch.create');
        Route::post('store', 'TransferToBranchController@store')->name('transfer.stock.to.branch.store');
        Route::get('get/all/warehouse', 'TransferToBranchController@getAllWarehouse')->name('transfer.stock.to.branch.all.warehouse');
        Route::get('edit/{transferId}', 'TransferToBranchController@edit')->name('transfer.stock.to.branch.edit');
        Route::get('get/editable/transfer/{transferId}', 'TransferToBranchController@editableTransfer')->name('transfer.stock.to.branch.editable.transfer');
        Route::post('update/{transferId}', 'TransferToBranchController@update')->name('transfer.stock.to.branch.update');
        Route::delete('delete/{transferId}', 'TransferToBranchController@delete')->name('transfer.stock.to.branch.delete');
        Route::get('sarach/product/{product_code}/{warehouse_id}', 'TransferToBranchController@productSearch');
        Route::get('check/warehouse/variant/qty/{product_id}/{variant_id}/{warehouse_id}', 'TransferToBranchController@checkWarehouseProductVariant');
        Route::get('check/warehouse/qty/{product_id}/{warehouse_id}', 'TransferToBranchController@checkWarehouseSingleProduct');

        // Receive stock from warehouse **route group**
        Route::group(['prefix' => 'receive'], function () {
            Route::get('/', 'WarehouseReceiveStockController@index')->name('transfer.stocks.to.branch.receive.stock.index');
            Route::get('show/{sendStockId}', 'WarehouseReceiveStockController@show')->name('transfer.stocks.to.branch.receive.stock.show');
            Route::get('process/{sendStockId}', 'WarehouseReceiveStockController@receiveProducessView')->name('transfer.stocks.to.branch.receive.stock.process.view');
            Route::get('receivable/stock/{sendStockId}', 'WarehouseReceiveStockController@receivableStock')->name('transfer.stocks.to.branch.receive.stock.get.receivable.stock');
            Route::post('process/save/{sendStockId}', 'WarehouseReceiveStockController@receiveProcessSave')->name('transfer.stocks.to.branch.receive.stock.process.save');
        });

        //Transfer Stock Branch To Branch
        Route::group(['prefix' => 'branch/to/branch'], function () {

            Route::get('transfer/list', 'TransferStockBranchToBranchController@transferList')->name('transfer.stock.branch.to.branch.transfer.list');

            Route::get('create', 'TransferStockBranchToBranchController@create')->name('transfer.stock.branch.to.branch.create');

            Route::get('show/{transferId}', 'TransferStockBranchToBranchController@show')->name('transfer.stock.branch.to.branch.show');

            Route::post('store', 'TransferStockBranchToBranchController@store')->name('transfer.stock.branch.to.branch.store');

            Route::get('edit/{transferId}', 'TransferStockBranchToBranchController@edit')->name('transfer.stock.branch.to.branch.edit');

            Route::post('update/{transferId}', 'TransferStockBranchToBranchController@update')->name('transfer.stock.branch.to.branch.update');

            Route::delete('delete/{transferId}', 'TransferStockBranchToBranchController@delete')->name('transfer.stock.branch.to.branch.delete');

            Route::get('search/product/{product_code}/{warehouse_id}/{receiver_branch_id?}', 'TransferStockBranchToBranchController@searchProduct');

            Route::get('check/single/product/stock/{product_id}/{warehouse_id}/{receiver_branch_id?}', 'TransferStockBranchToBranchController@checkSingleProductStock');

            Route::get('check/variant/product/stock/{product_id}/{variant_id}/{warehouse_id}/{receiver_branch_id?}', 'TransferStockBranchToBranchController@checkVariantProductStock');

            Route::group(['prefix' => 'receive'], function () {

                Route::get('receivable/list', 'ReceiveTransferBranchToBranchController@receivableList')->name('transfer.stock.branch.to.branch.receivable.list');

                Route::get('show/{transferId}', 'ReceiveTransferBranchToBranchController@show')->name('transfer.stock.branch.to.branch.receivable.show');

                Route::get('process/to/receive/{transferId}', 'ReceiveTransferBranchToBranchController@processToReceive')->name('transfer.stock.branch.to.branch.ProcessToReceive');

                Route::post('process/to/receive/save/{transferId}', 'ReceiveTransferBranchToBranchController@processToReceiveSave')->name('transfer.stock.branch.to.branch.ProcessToReceive.save');
            });
        });
    });

    //Stock adjustment to branch all route
    Route::group(['prefix' => 'stock/adjustments', 'namespace' => 'App\Http\Controllers'], function () {

        Route::get('/', 'StockAdjustmentController@index')->name('stock.adjustments.index');
        Route::get('show/{adjustmentId}', 'StockAdjustmentController@show')->name('stock.adjustments.show');
        Route::get('create', 'StockAdjustmentController@create')->name('stock.adjustments.create');
        Route::get('create/from/warehouse', 'StockAdjustmentController@createFromWarehouse')->name('stock.adjustments.create.from.warehouse');
        Route::post('store', 'StockAdjustmentController@store')->name('stock.adjustments.store');
        Route::get('search/product/in/warehouse/{keyword}/{warehouse_id}', 'StockAdjustmentController@searchProductInWarehouse');
        Route::get('search/product/{keyword}', 'StockAdjustmentController@searchProduct');

        Route::get('check/single/product/stock/{product_id}', 'StockAdjustmentController@checkSingleProductStock');
        Route::get('check/single/product/stock/in/warehouse/{product_id}/{warehouse_id}', 'StockAdjustmentController@checkSingleProductStockInWarehouse');

        Route::get('check/variant/product/stock/{product_id}/{variant_id}', 'StockAdjustmentController@checkVariantProductStock');
        Route::get('check/variant/product/stock/in/warehouse/{product_id}/{variant_id}/{warehouse_id}', 'StockAdjustmentController@checkVariantProductStockInWarehouse');
        Route::delete('delete/{adjustmentId}', 'StockAdjustmentController@delete')->name('stock.adjustments.delete');

        Route::group(['prefix' => 'reports/stock/adjustments', 'namespace' => 'report'], function () {

            Route::get('/', 'StockAdjustmentReportController@index')->name('reports.stock.adjustments.index');
            Route::get('all/adjustments', 'StockAdjustmentReportController@allAdjustments')->name('reports.stock.adjustments.all');
            Route::get('print', 'StockAdjustmentReportController@print')->name('reports.stock.adjustments.print');
        });
    });

    //Transfer stock to warehouse all route
    Route::group(['prefix' => 'transfer/stocks/to/warehouse', 'namespace' => 'App\Http\Controllers'], function () {

        Route::get('/', 'TransferToWarehouseController@index')->name('transfer.stock.to.warehouse.index');
        Route::get('show/{id}', 'TransferToWarehouseController@show')->name('transfer.stock.to.warehouse.show');
        Route::get('create', 'TransferToWarehouseController@create')->name('transfer.stock.to.warehouse.create');
        Route::post('store', 'TransferToWarehouseController@store')->name('transfer.stock.to.warehouse.store');
        Route::get('get/all/warehouse', 'TransferToWarehouseController@getAllWarehouse')->name('transfer.stock.to.warehouse.all.warehouse');
        Route::get('edit/{transferId}', 'TransferToWarehouseController@edit')->name('transfer.stock.to.warehouse.edit');
        Route::get('get/editable/transfer/{transferId}', 'TransferToWarehouseController@editableTransfer')->name('transfer.stock.to.warehouse.editable.transfer');
        Route::post('update/{transferId}', 'TransferToWarehouseController@update')->name('transfer.stock.to.warehouse.update');
        Route::delete('delete/{transferId}', 'TransferToWarehouseController@delete')->name('transfer.stock.to.warehouse.delete');
        Route::get('sarach/product/{product_code}', 'TransferToWarehouseController@productSearch');
        Route::get('check/single/product/stock/{product_id}', 'TransferToWarehouseController@checkBranchSingleProduct');
        Route::get('check/branch/variant/qty/{product_id}/{variant_id}', 'TransferToWarehouseController@checkBranchProductVariant');

        // Receive stock from branch **route group**
        Route::group(['prefix' => 'receive'], function () {

            Route::get('/', 'BranchReceiveStockController@index')->name('transfer.stocks.to.warehouse.receive.stock.index');
            Route::get('show/{sendStockId}', 'BranchReceiveStockController@show')->name('transfer.stocks.to.warehouse.receive.stock.show');
            Route::get('all/send/stocks', 'BranchReceiveStockController@allSendStock')->name('transfer.stocks.to.warehouse.receive.stock.all.send.stocks');
            Route::get('process/{sendStockId}', 'BranchReceiveStockController@receiveProducessView')->name('transfer.stocks.to.warehouse.receive.stock.process.view');
            Route::get('receivable/stock/{sendStockId}', 'BranchReceiveStockController@receivableStock')->name('transfer.stocks.to.warehouse.receive.stock.get.receivable.stock');
            Route::post('process/save/{sendStockId}', 'BranchReceiveStockController@receiveProcessSave')->name('transfer.stocks.to.warehouse.receive.stock.process.save');
            Route::post('mail/{sendStockId}', 'BranchReceiveStockController@receiveMail')->name('transfer.stocks.to.warehouse.receive.stock.mail');
        });
    });

    // Expense route group
    Route::group(['prefix' => 'expenses', 'namespace' => 'App\Http\Controllers'], function () {

        Route::get('/', 'ExpenseController@index')->name('expenses.index');
        Route::get('category/wise/expenses', 'ExpenseController@categoryWiseExpense')->name('expenses.category.wise.expense');
        Route::get('create', 'ExpenseController@create')->name('expenses.create');
        Route::post('store', 'ExpenseController@store')->name('expenses.store');
        Route::get('edit/{expenseId}', 'ExpenseController@edit')->name('expenses.edit');
        Route::post('update/{expenseId}', 'ExpenseController@update')->name('expenses.update');
        Route::delete('delete/{expenseId}', 'ExpenseController@delete')->name('expenses.delete');
        Route::get('all/categories', 'ExpenseController@allCategories')->name('expenses.all.categories');
        Route::get('payment/modal/{expenseId}', 'ExpenseController@paymentModal')->name('expenses.payment.modal');
        Route::post('payment/{expenseId}', 'ExpenseController@payment')->name('expenses.payment');
        Route::get('payment/view/{expenseId}', 'ExpenseController@paymentView')->name('expenses.payment.view');
        Route::get('payment/details/{paymentId}', 'ExpenseController@paymentDetails')->name('expenses.payment.details');
        Route::get('payment/edit/{paymentId}', 'ExpenseController@paymentEdit')->name('expenses.payment.edit');
        Route::post('payment/update/{paymentId}', 'ExpenseController@paymentUpdate')->name('expenses.payment.update');
        Route::delete('payment/delete/{paymentId}', 'ExpenseController@paymentDelete')->name('expenses.payment.delete');
        Route::post('add/quick/expense/category', 'ExpenseController@addQuickExpenseCategory')->name('expenses.add.quick.expense.category');

        // Expense category route group
        Route::group(['prefix' => 'categories'], function () {

            Route::get('/', 'ExpenseCategoryController@index')->name('expenses.categories.index');
            Route::get('all/categories', 'ExpenseCategoryController@allCategory')->name('expenses.categories.all.category');
            Route::post('store', 'ExpenseCategoryController@store')->name('expenses.categories.store');
            Route::post('update', 'ExpenseCategoryController@update')->name('expenses.categories.update');
            Route::delete('delete/{categoryId}', 'ExpenseCategoryController@delete')->name('expenses.categories.delete');
        });

        Route::group(['prefix' => 'report/expenses', 'namespace' => 'report'], function () {

            Route::get('/', 'ExpenseReportController@index')->name('reports.expenses.index');
            Route::get('print', 'ExpenseReportController@print')->name('reports.expenses.print');
        });
    });

    Route::group(['prefix' => 'accounting', 'namespace' => 'App\Http\Controllers'], function () {

        Route::group(['prefix' => 'banks'], function () {

            Route::get('/', 'BankController@index')->name('accounting.banks.index');
            Route::get('all/banks', 'BankController@allBanks')->name('accounting.banks.all.bank');
            Route::post('store', 'BankController@store')->name('accounting.banks.store');
            Route::post('update', 'BankController@update')->name('accounting.banks.update');
            Route::delete('delete/{bankId}', 'BankController@delete')->name('accounting.banks.delete');
        });

        Route::group(['prefix' => 'accounts'], function () {

            Route::get('/', 'AccountController@index')->name('accounting.accounts.index');
            Route::get('account/book/{accountId}', 'AccountController@accountBook')->name('accounting.accounts.book');
            Route::get('account/ledger/print/{accountId}', 'AccountController@ledgerPrint')->name('accounting.accounts.ledger.print');
            Route::post('store', 'AccountController@store')->name('accounting.accounts.store');
            Route::get('edit/{id}', 'AccountController@edit')->name('accounting.accounts.edit');
            Route::post('update/{id}', 'AccountController@update')->name('accounting.accounts.update');
            Route::delete('delete/{accountId}', 'AccountController@delete')->name('accounting.accounts.delete');
        });

        Route::group(['prefix' => 'contras'], function () {

            Route::get('/', 'ContraController@index')->name('accounting.contras.index');
            Route::get('create', 'ContraController@create')->name('accounting.contras.create');
            Route::get('show/{contraId}', 'ContraController@show')->name('accounting.contras.show');
            Route::get('account/book/{contraId}', 'ContraController@accountBook')->name('accounting.contras.book');
            Route::post('store', 'ContraController@store')->name('accounting.contras.store');
            Route::get('edit/{contraId}', 'ContraController@edit')->name('accounting.contras.edit');
            Route::post('update/{contraId}', 'ContraController@update')->name('accounting.contras.update');
            Route::delete('delete/{contraId}', 'ContraController@delete')->name('accounting.contras.delete');
        });

        Route::group(['prefix' => '/'], function () {

            Route::get('balance/sheet', 'AccountingRelatedSectionController@balanceSheet')->name('accounting.balance.sheet');
            Route::get('balance/sheet/amounts', 'AccountingRelatedSectionController@balanceSheetAmounts')->name('accounting.balance.sheet.amounts');
            Route::get('trial/balance', 'AccountingRelatedSectionController@trialBalance')->name('accounting.trial.balance');
            Route::get('trial/balance/amounts', 'AccountingRelatedSectionController@trialBalanceAmounts')->name('accounting.trial.balance.amounts');
            Route::get('cash/flow', 'AccountingRelatedSectionController@cashFow')->name('accounting.cash.flow');
            Route::get('cash/flow/amounts', 'AccountingRelatedSectionController@cashFlowAmounts')->name('accounting.cash.flow.amounts');
            Route::get('filter/cash/flow', 'AccountingRelatedSectionController@filterCashflows')->name('accounting.filter.cash.flow');
            Route::get('print/cash/flow', 'AccountingRelatedSectionController@printCashflow')->name('accounting.print.cash.flow');
            Route::get('profit/loss/account', 'AccountingRelatedSectionController@profitLossAccount')->name('accounting.profit.loss.account');
            Route::get('profit/loss/account/amounts', 'AccountingRelatedSectionController@profitLossAccountAmounts')->name('accounting.profit.loss.account.amounts');
        });

        Route::group(['prefix' => 'assets'], function () {

            Route::get('/', 'AssetController@index')->name('accounting.assets.index');
            Route::post('asset/type/store', 'AssetController@assetTypeStore')->name('accounting.assets.asset.type.store');
            Route::get('asset/type/edit/{typeId}', 'AssetController@assetTypeEdit')->name('accounting.assets.asset.type.edit');
            Route::post('asset/type/update/{typeId}', 'AssetController@assetTypeUpdate')->name('accounting.assets.asset.type.update');

            Route::delete('asset/type/delete/{typeId}', 'AssetController@assetTypeDelete')->name('accounting.assets.asset.type.delete');
            Route::get('form/asset/types', 'AssetController@formAssetTypes')->name('accounting.assets.form.asset.type');

            Route::get('all/asset', 'AssetController@allAsset')->name('accounting.assets.all');
            Route::post('asset/store', 'AssetController@assetStore')->name('accounting.assets.store');
            Route::get('asset/edit/{assetId}', 'AssetController@assetEdit')->name('accounting.assets.edit');
            Route::post('asset/update/{assetId}', 'AssetController@assetUpdate')->name('accounting.assets.update');
            Route::delete('asset/delete/{assetId}', 'AssetController@assetDelete')->name('accounting.assets.delete');
        });

        Route::group(['prefix' => 'loans'], function () {

            Route::group(['prefix' => '/'], function () {

                Route::get('/', 'LoanController@index')->name('accounting.loan.index');
                Route::post('store', 'LoanController@store')->name('accounting.loan.store');
                Route::get('show/{loanId}', 'LoanController@show')->name('accounting.loan.show');
                Route::get('edit/{loanId}', 'LoanController@edit')->name('accounting.loan.edit');
                Route::post('update/{loanId}', 'LoanController@update')->name('accounting.loan.update');
                Route::delete('delete/{loanId}', 'LoanController@delete')->name('accounting.loan.delete');
                Route::get('all/companies/for/form', 'LoanController@allCompaniesForForm')->name('accounting.loan.all.companies.for.form');
                Route::get('loan/print', 'LoanController@loanPrint')->name('accounting.loan.print');
            });

            Route::group(['prefix' => 'companies'], function () {

                Route::get('/', 'LoanCompanyController@index')->name('accounting.loan.companies.index');
                Route::post('store', 'LoanCompanyController@store')->name('accounting.loan.companies.store');
                Route::get('edit/{companyId}', 'LoanCompanyController@edit')->name('accounting.loan.companies.edit');
                Route::post('update/{companyId}', 'LoanCompanyController@update')->name('accounting.loan.companies.update');
                Route::delete('delete/{companyId}', 'LoanCompanyController@delete')->name('accounting.loan.companies.delete');
            });

            Route::group(['prefix' => 'payments'], function () {

                Route::get('due/receive/modal/{company_id}', 'LoanPaymentController@loanAdvanceReceiveModal')->name('accounting.loan.advance.receive.modal');
                Route::post('due/receive/store/{company_id}', 'LoanPaymentController@loanAdvanceReceiveStore')->name('accounting.loan.advance.receive.store');
                Route::get('due/pay/modal/{company_id}', 'LoanPaymentController@loaLiabilityPaymentModal')->name('accounting.loan.liability.payment.modal');
                Route::post('due/pay/store/{company_id}', 'LoanPaymentController@loanLiabilityPaymentStore')->name('accounting.loan.liability.payment.store');
                Route::get('payment/list/{company_id}', 'LoanPaymentController@paymentList')->name('accounting.loan.payment.list');
                Route::delete('delete/{payment_id}', 'LoanPaymentController@delete')->name('accounting.loan.payment.delete');
            });
        });

        Route::group(['prefix' => 'reports', 'namespace' => 'report'], function () {

            Route::group(['prefix' => 'daily/profit/loss'], function () {

                Route::get('/', 'ProfitLossReportController@index')->name('reports.profit.loss.index');
                Route::get('sale/purchase/profit', 'ProfitLossReportController@salePurchaseProfit')->name('reports.profit.sale.purchase.profit');
                Route::get('filter/sale/purchase/profit/filter', 'ProfitLossReportController@filterSalePurchaseProfit')->name('reports.profit.filter.sale.purchase.profit');
                Route::get('print', 'ProfitLossReportController@printProfitLoss')->name('reports.profit.loss.print');
            });

            Route::group(['prefix' => 'financial'], function () {

                Route::get('/', 'FinancialReportControllerReport@index')->name('reports.financial.index');
                Route::get('amounts', 'FinancialReportControllerReport@financialAmounts')->name('reports.financial.amounts');
                Route::get('filter/amounts', 'FinancialReportControllerReport@filterFinancialAmounts')->name('reports.financial.filter.amounts');
                Route::get('report/print', 'FinancialReportControllerReport@print')->name('reports.financial.report.print');
            });
        });
    });

    Route::group(['prefix' => 'settings', 'namespace' => 'App\Http\Controllers'], function () {

        Route::group(['prefix' => 'branches'], function () {

            Route::get('/', 'BranchController@index')->name('settings.branches.index');
            Route::get('get/all/branch', 'BranchController@getAllBranch')->name('settings.get.all.branch');
            Route::get('create', 'BranchController@create')->name('settings.branches.create');
            Route::post('store', 'BranchController@store')->name('settings.branches.store');
            Route::get('edit/{branchId}', 'BranchController@edit')->name('settings.branches.edit');
            Route::post('update/{branchId}', 'BranchController@update')->name('settings.branches.update');
            Route::delete('delete/{id}', 'BranchController@delete')->name('settings.branches.delete');
            Route::get('quick/invoice/schema/modal', 'BranchController@quickInvoiceSchemaModal')->name('settings.branches.quick.invoice.schema.modal');
            Route::post('quick/invoice/schema/store', 'BranchController@quickInvoiceSchemaStore')->name('settings.branches.quick.invoice.schema.store');
        });

        Route::group(['prefix' => 'warehouses'], function () {

            Route::get('/', 'WarehouseController@index')->name('settings.warehouses.index');
            Route::post('store', 'WarehouseController@store')->name('settings.warehouses.store');
            Route::get('edit/{id}', 'WarehouseController@edit')->name('settings.warehouses.edit');
            Route::post('update/{id}', 'WarehouseController@update')->name('settings.warehouses.update');
            Route::delete('delete/{warehouseId}', 'WarehouseController@delete')->name('settings.warehouses.delete');
        });

        Route::group(['prefix' => 'units'], function () {

            Route::get('/', 'UnitController@index')->name('settings.units.index');
            Route::get('get/all/unit', 'UnitController@getAllUnit')->name('settings.units.get.all.unit');
            Route::post('store', 'UnitController@store')->name('settings.units.store');
            Route::post('update', 'UnitController@update')->name('settings.units.update');
            Route::delete('delete/{unitId}', 'UnitController@delete')->name('settings.units.delete');
        });

        Route::group(['prefix' => 'taxes'], function () {

            Route::get('/', 'TaxController@index')->name('settings.taxes.index');
            Route::get('get/all/vat', 'TaxController@getAllVat')->name('settings.taxes.get.all.tax');
            Route::post('store', 'TaxController@store')->name('settings.taxes.store');
            Route::post('update', 'TaxController@update')->name('settings.taxes.update');
            Route::delete('delete/{taxId}', 'TaxController@delete')->name('settings.taxes.delete');
        });

        Route::group(['prefix' => 'general_settings'], function () {

            Route::get('/', 'GeneralSettingController@index')->name('settings.general.index');
            Route::post('business/settings', 'GeneralSettingController@businessSettings')->name('settings.business.settings');
            Route::post('tax/settings', 'GeneralSettingController@taxSettings')->name('settings.tax.settings');
            Route::post('product/settings', 'GeneralSettingController@productSettings')->name('settings.product.settings');
            Route::post('contact/settings', 'GeneralSettingController@contactSettings')->name('settings.contact.settings');
            Route::post('sale/settings', 'GeneralSettingController@saleSettings')->name('settings.sale.settings');
            Route::post('pos/settings', 'GeneralSettingController@posSettings')->name('settings.pos.settings');
            Route::post('purchase/settings', 'GeneralSettingController@purchaseSettings')->name('settings.purchase.settings');
            Route::post('dashboard/settings', 'GeneralSettingController@dashboardSettings')->name('settings.dashboard.settings');
            Route::post('prefix/settings', 'GeneralSettingController@prefixSettings')->name('settings.prefix.settings');
            Route::post('system/settings', 'GeneralSettingController@systemSettings')->name('settings.system.settings');
            Route::post('module/settings', 'GeneralSettingController@moduleSettings')->name('settings.module.settings');
            Route::post('send/email/sms/settings', 'GeneralSettingController@SendEmailSmsSettings')->name('settings.send.email.sms.settings');
            Route::post('sms/settings', 'GeneralSettingController@smsSettings')->name('settings.sms.settings');
            Route::post('rp/settings', 'GeneralSettingController@rewardPoingSettings')->name('settings.reward.point.settings');
        });

        Route::group(['prefix' => 'payment_methods'], function () {

            Route::get('/', 'PaymentMethodController@index')->name('settings.payment.method.index');
            Route::post('store', 'PaymentMethodController@store')->name('settings.payment.method.store');
            Route::get('edit/{id}', 'PaymentMethodController@edit')->name('settings.payment.method.edit');
            Route::post('update/{id}', 'PaymentMethodController@update')->name('settings.payment.method.update');
            Route::delete('delete/{id}', 'PaymentMethodController@delete')->name('settings.payment.method.delete');
        });

        Route::group(['prefix' => 'payment_method_settings'], function () {

            Route::get('/', 'PaymentMethodSettingsController@index')->name('settings.payment.method.settings.index');
            Route::post('update', 'PaymentMethodSettingsController@update')->name('settings.payment.method.settings.update');
        });

        Route::group(['prefix' => 'barcode_settings'], function () {

            Route::get('/', 'BarcodeSettingController@index')->name('settings.barcode.index');
            Route::get('create', 'BarcodeSettingController@create')->name('settings.barcode.create');
            Route::post('store', 'BarcodeSettingController@store')->name('settings.barcode.store');
            Route::get('edit/{id}', 'BarcodeSettingController@edit')->name('settings.barcode.edit');
            Route::post('update/{id}', 'BarcodeSettingController@update')->name('settings.barcode.update');
            Route::delete('delete/{id}', 'BarcodeSettingController@delete')->name('settings.barcode.delete');
            Route::get('set/default/{id}', 'BarcodeSettingController@setDefault')->name('settings.barcode.set.default');
        });

        Route::group(['prefix' => 'invoices'], function () {

            Route::group(['prefix' => 'schemas'], function () {

                Route::get('/', 'InvoiceSchemaController@index')->name('invoices.schemas.index');
                Route::post('store', 'InvoiceSchemaController@store')->name('invoices.schemas.store');
                Route::get('edit/{schemaId}', 'InvoiceSchemaController@edit')->name('invoices.schemas.edit');
                Route::post('update/{schemaId}', 'InvoiceSchemaController@update')->name('invoices.schemas.update');
                Route::delete('delete/{schemaId}', 'InvoiceSchemaController@delete')->name('invoices.schemas.delete');
                Route::get('set/default/{schemaId}', 'InvoiceSchemaController@setDefault')->name('invoices.schemas.set.default');
            });

            Route::group(['prefix' => 'layouts'], function () {

                Route::get('/', 'InvoiceLayoutController@index')->name('invoices.layouts.index');
                Route::get('create', 'InvoiceLayoutController@create')->name('invoices.layouts.create');
                Route::post('/', 'InvoiceLayoutController@store')->name('invoices.layouts.store');
                Route::get('edit/{layoutId}', 'InvoiceLayoutController@edit')->name('invoices.layouts.edit');
                Route::post('update/{layoutId}', 'InvoiceLayoutController@update')->name('invoices.layouts.update');
                Route::delete('delete/{layoutId}', 'InvoiceLayoutController@delete')->name('invoices.layouts.delete');
                Route::get('set/default/{schemaId}', 'InvoiceLayoutController@setDefault')->name('invoices.layouts.set.default');
            });
        });

        Route::group(['prefix' => 'cash_counter'], function () {

            Route::get('/', 'CashCounterController@index')->name('settings.cash.counter.index');
            Route::post('store', 'CashCounterController@store')->name('settings.payment.cash.counter.store');
            Route::get('edit/{id}', 'CashCounterController@edit')->name('settings.cash.counter.edit');
            Route::post('update/{id}', 'CashCounterController@update')->name('settings.cash.counter.update');
            Route::delete('delete/{id}', 'CashCounterController@delete')->name('settings.cash.counter.delete');
        });

        Route::group(['prefix' => 'release/note'], function () {

            Route::get('/', 'ReleaseNoteController@index')->name('settings.release.note.index');
        });
    });

    Route::group(['prefix' => 'users',  'namespace' => 'App\Http\Controllers'], function () {

        Route::get('/', 'UserController@index')->name('users.index');
        Route::get('all/users', 'UserController@allUsers')->name('users.all.Users');
        Route::get('create', 'UserController@create')->name('users.create');
        Route::get('all/roles', 'UserController@allRoles')->name('users.all.roles');
        Route::post('store', 'UserController@store')->name('users.store');
        Route::get('edit/{userId}', 'UserController@edit')->name('users.edit');
        Route::post('update/{userId}', 'UserController@update')->name('users.update');
        Route::delete('delete/{userId}', 'UserController@delete')->name('users.delete');
        Route::get('show/{userId}', 'UserController@show')->name('users.show');

        Route::group(['prefix' => 'roles'], function () {

            Route::get('/', 'RoleController@index')->name('users.role.index');
            Route::get('all/roles', 'RoleController@allRoles')->name('users.role.all.roles');
            Route::get('create', 'RoleController@create')->name('users.role.create');
            Route::post('store', 'RoleController@store')->name('users.role.store');
            Route::get('edit/{roleId}', 'RoleController@edit')->name('users.role.edit');
            Route::post('update/{roleId}', 'RoleController@update')->name('users.role.update');
            Route::delete('delete/{roleId}', 'RoleController@delete')->name('users.role.delete');
        });

        Route::group(['prefix' => 'profile'], function () {

            Route::get('/', 'UserProfileController@index')->name('users.profile.index');
            Route::post('update', 'UserProfileController@update')->name('users.profile.update');
            Route::get('view/{id}', 'UserProfileController@view')->name('users.profile.view');
        });
    });

    Route::group(['prefix' => 'reports', 'namespace' => 'App\Http\Controllers\report'], function () {

        Route::group(['prefix' => 'taxes'], function () {

            Route::get('/', 'TaxReportController@index')->name('reports.taxes.index');
            Route::get('get', 'TaxReportController@getTaxReport')->name('reports.taxes.get');
        });

        Route::group(['prefix' => 'user/activities/log'], function () {

            Route::get('/', 'UserActivityLogReportController@index')->name('reports.user.activities.log.index');
        });
    });

    Route::group(['prefix' => 'short-menus', 'namespace' => 'App\Http\Controllers'], function () {

        Route::get('modal/form', 'ShortMenuController@showModalForm')->name('short.menus.modal.form');
        Route::get('show', 'ShortMenuController@show')->name('short.menus.show');
        Route::post('store', 'ShortMenuController@store')->name('short.menus.store');
    });

    Route::group(['prefix' => 'pos-short-menus', 'namespace' => 'App\Http\Controllers'], function () {

        Route::get('modal/form', 'PosShortMenuController@showModalForm')->name('pos.short.menus.modal.form');
        Route::get('show', 'PosShortMenuController@show')->name('pos.short.menus.show');
        Route::get('edit/page/show', 'PosShortMenuController@editPageShow')->name('pos.short.menus.edit.page.show');
        Route::post('store', 'PosShortMenuController@store')->name('pos.short.menus.store');
    });

    Route::group(['prefix' => 'communication', 'namespace' => 'App\Http\Controllers'], function () {

        Route::group(['prefix' => 'email',], function () {

            Route::get('settings', 'EmailController@emailSettings')->name('communication.email.settings');
            Route::post('settings/store', 'EmailController@emailSettingsStore')->name('communication.email.settings.store');
        });

        Route::group(['prefix' => 'sms',], function () {

            Route::get('settings', 'SmsController@smsSettings')->name('communication.sms.settings');
            Route::post('settings/store', 'SmsController@smsSettingsStore')->name('communication.sms.settings.store');
        });
    });

    Route::get('change/lang/{lang}', 'App\Http\Controllers\DashboardController@changeLang')->name('change.lang');

    Route::get('maintenance/mode', function () {

        return view('maintenance/maintenance');
    })->name('maintenance.mode');

    Route::get('add-user', function () {

        $addAdmin = new AdminAndUser();
        $addAdmin->prefix = 'Mr.';
        $addAdmin->name = 'Super';
        $addAdmin->last_name = 'Admin';
        $addAdmin->email = 'superadmin@gmail.com';
        $addAdmin->username = 'superadmin';
        $addAdmin->password = Hash::make('12345');
        $addAdmin->role_type = 3;
        $addAdmin->role_permission_id = 1;
        $addAdmin->allow_login = 1;
        $addAdmin->save();
        //1=super_admin;2=admin;3=Other;

    });

    Route::get('pin_login', function () {

        return view('auth.pin_login');
    });

    Route::get('/test', function () {

        //return str_pad(10, 10, "0", STR_PAD_LEFT);
        // $purchases = Purchase::all();
        // foreach ($purchases as $p) {
        //     $p->is_last_created = 0;
        //     $p->save();
        // } 

        $customers = DB::table('customers')->get();

        foreach ($customers as $customer) {

            $customerOpeningBalance = new CustomerOpeningBalance();
            $customerOpeningBalance->customer_id = $customer->id;
            $customerOpeningBalance->amount = $customer->opening_balance;
            $customerOpeningBalance->created_by_id = auth()->user()->id;
            $customerOpeningBalance->save();

            $customerCreditLimit = new CustomerCreditLimit();
            $customerCreditLimit->customer_id = $customer->id;
            $customerCreditLimit->credit_limit = $customer->credit_limit ? $customer->credit_limit : 0;
            $customerCreditLimit->created_by_id = auth()->user()->id;
            $customerCreditLimit->save();
        }

        $suppliers = DB::table('suppliers')->get();

        foreach ($suppliers as $supplier) {

            $supplierOpeningBalance = new SupplierOpeningBalance();
            $supplierOpeningBalance->supplier_id = $supplier->id;
            $supplierOpeningBalance->amount = $supplier->opening_balance;
            $supplierOpeningBalance->created_by_id = auth()->user()->id;
            $supplierOpeningBalance->save();
        }
    });

    // All authenticated routes
    // Auth::routes();

    // Route::get('dbal', function() {
    //     dd(\Doctrine\DBAL\Types\Type::getTypesMap());
    // });

});
