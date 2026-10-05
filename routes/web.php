<?php

//auth routes for normal user
Auth::routes(['register' => false, 'verify' => false]);

Route::get('/',  function(){
    return redirect()->route('admin.home');
});
Route::get('/import','Import@csvToArray');
//pages route
Route::prefix('admin')->namespace('Administrator')->middleware('auth')->group(function () {

    Route::get('/', 'HomeController@index')->name('admin.home');

    //users
    Route::get('/users', 'UserController@index')->name('admin.users');
    Route::get('/users/add', 'UserController@add')->name('admin.users.add');
    Route::get('/users/edit/{id}', 'UserController@edit')->name('admin.users.edit');
    Route::get('/users/delete/{id}', 'UserController@delete')->name('admin.users.delete');
    Route::post('/users/save', 'UserController@save')->name('admin.users.save');
    Route::get('/users/change-status/{id}', 'UserController@change_status')->name('admin.users.change_status');
    Route::get('/your-profile', 'UserController@change_profile')->name('admin.change_password');
    Route::post('/update-password', 'UserController@update_password')->name('admin.update_password');
    Route::post('/update-profile', 'UserController@update_profile')->name('admin.update_profile');
    Route::post('/update-site','UserController@update_site_name')->name('admin.siteUpdate');

    //regions
    Route::get('/regions', 'RegionsController@index')->name('admin.regions');
    Route::get('/region/edit/{id}', 'RegionsController@edit')->name('admin.region.edit');
    Route::get('/region/delete/{id}', 'RegionsController@delete')->name('admin.region.delete');
    Route::post('/region/save', 'RegionsController@save')->name('admin.region.save');
    Route::get('/region/list', 'RegionsController@region_list')->name('admin.region.list');
    Route::post('/region/import', 'RegionsController@import_csv_regions')->name('admin.region.import');

    //town
    Route::get('/town', 'RegionsController@town')->name('admin.town');
    Route::get('/town/list', 'RegionsController@town_list')->name('admin.town.list');
    Route::post('/town/save', 'RegionsController@town_save')->name('admin.town.save');
    Route::get('/town/delete/{id}', 'RegionsController@town_delete')->name('admin.town.delete');
    Route::get('/town/edit/{id}', 'RegionsController@town_edit')->name('admin.town.edit');
    Route::post('/town/import', 'RegionsController@import_csv_town')->name('admin.town.import');

    //Customers
   Route::get('/customers', 'CustomersController@index')->name('admin.customers');
   Route::get('/customers/add', 'CustomersController@add')->name('admin.customers.add');
   Route::post('/customer/save', 'CustomersController@save')->name('admin.customer.save');
   Route::get('/customers/list', 'CustomersController@customers_list')->name('admin.customers.list');
   Route::get('/customer/edit/{id}', 'CustomersController@edit')->name('admin.customer.edit');
   Route::get('/customer/delete/{id}', 'CustomersController@delete')->name('admin.customer.delete');
   Route::post('/customer/filter_customers', 'CustomersController@filter_customers')->name('admin.filter_customers.list');
   Route::post('/customer/import', 'CustomersController@import_csv_customer')->name('admin.customers.import');
   Route::post('/get_town', 'CustomersController@get_town')->name('admin.get_town');
   Route::get('/customer/change_status/{id}', 'CustomersController@change_status')->name('admin.customer.change_status');
   
   //Groups
   Route::get('/groups', 'GroupsController@index')->name('admin.groups');
   Route::post('/group/save', 'GroupsController@save')->name('admin.group.save');
   Route::get('/group/list', 'GroupsController@group_list')->name('admin.group.list');
   Route::get('/group/edit/{id}', 'GroupsController@edit')->name('admin.group.edit');
   Route::get('/group/delete/{id}', 'GroupsController@delete')->name('admin.group.delete'); 

    // Products
   Route::get('/products', 'ProductsControler@index')->name('admin.products');
   Route::get('/products/add', 'ProductsControler@add')->name('admin.products.add');
   Route::post('/products/save', 'ProductsControler@save')->name('admin.products.save');
   Route::post('/products/list', 'ProductsControler@list')->name('admin.products.list');
   Route::get('/products/edit/{id}', 'ProductsControler@edit')->name('admin.products.edit');
   Route::get('/products/delete/{id}', 'ProductsControler@delete')->name('admin.products.delete');
   Route::post('/products/import', 'ProductsControler@import_csv_products')->name('admin.products.import');
   Route::post('/products/import/price', 'ProductsControler@import_csv_products_price')->name('admin.products.price.import');
   Route::post('/get_sub_company', 'ProductsControler@get_sub_company')->name('admin.get_sub_company');
   Route::get('/updating/product_code','ProductsControler@updatingCode')->name('admin.updating.code');
   //Employees
   Route::get('/employees', 'EmployeesController@index')->name('admin.employee');
   Route::get('/employees/filter_employee', 'EmployeesController@filter_employees')->name('admin.filter_employee.list');
   Route::post('/employees/model_data', 'EmployeesController@model_data')->name('admin.employee.model_data');
   Route::post('/employees/task_data', 'EmployeesController@task_data')->name('admin.employee.task_data');
   Route::get('/employees/add', 'EmployeesController@add')->name('admin.employee.add');
   Route::post('/employees/save', 'EmployeesController@save')->name('admin.employees.save');
   Route::get('/employees/edit/{id}', 'EmployeesController@edit')->name('admin.employees.edit');
   Route::post('/employees/import', 'EmployeesController@import_csv_customer')->name('admin.employee.import');
   Route::get('/employees/delete/{id}', 'EmployeesController@delete')->name('admin.employees.delete');
   Route::post('/employees/taget_data', 'EmployeesController@taget_data')->name('admin.employee.taget_data');
   Route::get('/target/add/{id}', 'EmployeesController@target_add')->name('admin.target.add');
   Route::post('/target/save', 'EmployeesController@target_save')->name('admin.target.save');
   Route::post('/target/edit/', 'EmployeesController@target_edit')->name('admin.target.edit');
   Route::get('/employee/change_status/{id}', 'EmployeesController@change_status')->name('admin.employee.change_status');
   Route::get('/employee/view_task/{id}','EmployeesController@view_task')->name('admin.employee.view_task');

   //Task
   Route::get('/task', 'TaskController@index')->name('admin.task');
   Route::post('/task/save', 'TaskController@save')->name('admin.task.save');
   Route::get('/task/edit/{id}', 'TaskController@edit')->name('admin.task.edit');
   Route::get('/task/delete/{id}', 'TaskController@delete')->name('admin.task.delete');
      Route::get('task-workflow-fetch/{id}', 'TaskController@fecthWorkflow')->name('admin.task.fecthWorkflow');
//   Route::get('/task/delete/{id}', 'TaskController@delete')->name('admin.task.delete');
  Route::get('grab-last-workflow/workflow', 'TaskController@getLastWorkflow')->name('admin.task.getLastWorkflow');
//   managing task workflow
 Route::get('task-workflow/index', 'TaskWorkflowController@index')->name('admin.task_workflow.index');
  Route::get('task-workflow/create', 'TaskWorkflowController@create')->name('admin.task_workflow.create');
  Route::post('task-workflow/save', 'TaskWorkflowController@save')->name('admin.task_workflow.save');
     
     Route::get('task-workflow/list', 'TaskWorkflowController@task_workflow_list')->name('admin.task_workflow.task_workflow_list');
        Route::get('task-workflow/edit/{id}', 'TaskWorkflowController@edit')->name('admin.task_workflow.edit');
        Route::get('task-workflow/delete/{id}', 'TaskWorkflowController@delete')->name('admin.task_workflow.delete');


   //Companies
   Route::get('/company', 'CompaniesController@index')->name('admin.company');
   Route::get('/company/add', 'CompaniesController@add')->name('admin.company.add');
   Route::post('/company/save', 'CompaniesController@save')->name('admin.company.save');
   Route::get('/company/list', 'CompaniesController@Companies_list')->name('admin.company.list');
   Route::get('/company/edit/{id}', 'CompaniesController@edit')->name('admin.company.edit');
   Route::get('/company/delete/{id}', 'CompaniesController@delete')->name('admin.company.delete');

   // Orders
   Route::get('/orders', 'OrderController@index')->name('admin.orders');
   Route::get('/view_order/{id}', 'OrderController@view_order')->name('admin.view_order');
   Route::get('/view/orders/{id}/{id2}/{id3}', 'OrderController@view_orders')->name('admin.view.orders');
   Route::post('/view/orders/{id}/{id2}/{id3}', 'OrderController@view_orders')->name('admin.view.order_post');
  Route::get('/orders/list/{status}/{user_id}/{user_type}', 'OrderController@list')->name('admin.orders.list'); //Old
 
   Route::post('/orders/get_user_card', 'OrderController@get_user_card')->name('admin.orders.get_user_card');
   Route::post('/orders/details', 'OrderController@order_details')->name('admin.order.details');
   Route::get('/orders/update/{id}', 'OrderController@order_update')->name('admin.order.update');
   Route::post('/orders/status_change', 'OrderController@status_change')->name('admin.order.status_change');
   
   
   //pending orders in order_return
    Route::get('/orders/pending_orders','OrderController@pending_orders')->name('admin.pending_orders');
    Route::get('/orders/customer/pending_orders','OrderController@customer_pending_orders')->name('customer.pending_orders.search');
    Route::get('/orders/approve','OrderController@approve_pending_orders')->name('checkbox');
    
    route::get('/orders/customer/search_pending_orders/{id}','OrderController@search_pending_orders')->name('customer.pending_orders');
    
    Route::get('/orders/customer/pending_orders/{id}','OrderController@pending_orders')->name('customer.pending_orders');
    //approved orders in order return
    route::get('/orders/approved_orders','OrderController@approved_orders')->name('admin.approved_orders');
    route::get('/orders/customer/approved_orders','OrderController@approved_orders')->name('admin.approved_orders.search');


    route::get('/orders/lifted_orders','OrderController@lifted_orders')->name('admin.lifted_orders');
    Route::get('/orders/customer/lifted_orders','OrderController@lifted_orders_search')->name('customer.lifted_orders.search');   
   
   
   //boooked orders
   
    Route::get('/orders/list/{status}/{user_id}/{user_type}', 'OrderController@list')->name('admin.orders.list');
    Route::post('/orders/list/{status}/{user_id}/{user_type}', 'OrderController@list')->name('admin.orders.list_post');
    Route::get('/view_orders/{id}','OrderController@all_completed_order')->name('admin.view_completed_order');
    Route::get('competed_orders/{id}','OrderController@view_completed_order')->name('admin.completed_order');
    Route::get('/complete-orders/list/{emp_id}', 'OrderController@complete_list')->name('admin.complete_orders.list');     
    Route::post('/complete-orders/details', 'OrderController@complete_order_details')->name('admin.complete_order.details');


    Route::get('/orders/return', 'OrderController@order_return')->name('admin.orders.return');
    Route::get('/orders/orders_return/list', 'OrderController@orders_return_list')->name('admin.orders_return.list');
    Route::post('/orderRetuen/details', 'OrderController@order_retuen_details')->name('admin.orderRetuen.details');
    Route::get('/order_return/update/{id}', 'OrderController@order_return_update')->name('admin.order_return.update');
    Route::post('/orderRetuen/status_change', 'OrderController@order_return_status_change')->name('admin.orderRetuen.status_change');


    Route::get('/files', 'OrderController@files')->name('admin.files');
    Route::post('/files/data','OrderController@get_file_data')->name('admin.files.data');
   // Route::post('/files/import','OrderController@file_import')->name('admin.files.import');
    Route::post('/files/export', 'OrderController@file_export')->name('admin.files.export');
    Route::post('/files/export2', 'OrderController@file_export2')->name('admin.files.export2');
    Route::post('/files/file_export_csv', 'OrderController@file_export_csv')->name('admin.files.file_export_csv');
    Route::post('/files/file_export_azm', 'OrderController@exportAzm')->name('admin.files.file_export_azm');
    Route::post('/files/file_export_txt', 'OrderController@file_export_txt')->name('admin.files.file_export_txt');
    Route::post('/files', 'Imports@csvToArray')->name('admin.files.import');
   
   
   // company products
   Route::get('/company/products', 'ProductsControler@company_products')->name('admin.company.products');  

   //notification
   Route::get('/notification', 'NotificationController@all_notification')->name('admin.notification.all_notifications');
   Route::get('/notification', 'NotificationController@all_notification')->name('admin.notification.all_notifications');

   //import stock
   Route::get('/import/stock/view','StockController@index')->name('admin.imports.show');
   Route::get('/import/stock','StockController@importStock')->name('admin.imports.stock');
   Route::get('/imports/stock/search','StockController@stockSearch')->name('admin.imports.search');
   Route::post('/import/stock/csv','StockController@import_csv_stock')->name('admin.imports.stock_csv');
   Route::get('/import/stock/edit','StockController@editImportStockField')->name('admin.imports.stock_fields.edit');
   Route::post('/import/stock/update','StockController@updateImportStockField')->name('admin.imports.stock_fields.update');
   Route::get('/stock/upload/bonus/pdf','StockController@uploadBonusPdf')->name('admin.uploads.bonus.pdf');
   Route::post('/stock/store/bonus/pdf','StockController@storeBonusPdf')->name('admin.stores.bonus.pdf');

   //rejected orders
   Route::get('/orders/rejected_orders','OrderController@rejected_orders')->name('admin.rejected_orders');
   Route::get('/orders/rejected','OrderController@reject_pending_orders')->name('checkbox.reject');
   Route::get('/orders/customer/rejected_orders','OrderController@customer_rejected_orders')->name('customer.rejected_orders.search');

   //vendor claimns
   Route::get('/orders/vendor-claims','OrderController@vendorClaim')->name('admin.vendor_claims');
   Route::get('/orders/vendor-claims/result','OrderController@vendor_claim_result')->name('admin.vendor_claims.result');
   Route::get('/orders/vendor-claims/export','OrderController@vendor_claim_export')->name('admin.vendor_claims.export');
   Route::get('/orders/vendor-claims-orders','OrderController@vendor_claim_lifted_orders')->name('admin.vendor_claim_orders');
   Route::get('/orders/vendor-claim-orders/view','OrderController@vendor_claim_orders')->name('admin.vendor_claim_orders.view');
   Route::get('/orders/vendor-claim-order/details/{id}','OrderController@vendor_claim_orders_details')->name('admin.vendor_claim_orders.detail');
   Route::get('/orders/vendor-claim-order/details/export/{id}','OrderController@vendor_claim_order_details_export')->name('admin.vendor_claim_order_details.export');

    //   Route::get('/orders/vendor-claim-update','OrderController@vendor_claim_update');
    //update date format
    Route::get('/stocks/update-date-format','StockController@updateDateFormat');
    
    //android version route
    Route::post('/android-version','HomeController@storeAndroidVersion')->name('admin.android_version.update');
  
});

