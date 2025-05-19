<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Clear application cache:
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');

    Artisan::call('route:cache');

    Artisan::call('config:cache');

    Artisan::call('view:clear');

    Artisan::call('optimize:clear');

    return 'Cache cleard';
})->name('clear.cache');

// ------------------------------------- Login/Register routes -----------------------------------------------

Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard')->middleware('admin');

Route::get('/register', [HomeController::class, 'Register_view'])->name('register_view');
Route::post('/register', [HomeController::class, 'Register'])->name('register');

Route::get('/login', [HomeController::class, 'Login_view'])->name('login_view');
Route::post('/login', [HomeController::class, 'Login'])->name('login');

Route::get('/forget_password', [HomeController::class, 'forget_list'])->name('forget_password');
Route::post('/forget_password', [HomeController::class, 'forget_action'])->name('forget_password.action');
Route::get('/forget_password/{token}', [HomeController::class, 'showresetpasswordform'])->name('mailforget');
Route::post('/reset_password', [HomeController::class, 'updateresetpassword'])->name('new_password.action');


Route::get('/logout', [HomeController::class, 'Logout'])->name('logout');

Route::get('/change_password', [HomeController::class, 'Change_password'])->name('change_password');
Route::post('/change_password', [HomeController::class, 'Update_password'])->name('update_password');


// -------------------------------- Clients section routes ----------------------------------------------

Route::get('/clients', [HomeController::class, 'Clients'])->name('clients')->middleware('admin');
Route::post('/add_client', [HomeController::class, 'Add_client'])->name('add_client');
Route::post('/edit_client', [HomeController::class, 'Edit_client'])->name('edit_client');
Route::post('/delete_client', [HomeController::class, 'Delete_client'])->name('delete_client');

// ------------------------------------ User Section routes -------------------------------------------------

Route::get('/users', [HomeController::class, 'Users'])->name('users')->middleware('admin');
Route::post('/add_user', [HomeController::class, 'Add_User'])->name('add_user');
Route::post('/edit_user', [HomeController::class, 'Edit_User'])->name('edit_user');
Route::post('/delete_user', [HomeController::class, 'Delete_User'])->name('delete_user');


// -----------------------------------------Vendors Section routes-------------------------

Route::get('/vendors/{id}',[HomeController::class, 'Vendors'])->name('vendors')->middleware('admin');
Route::post('/add_vendor', [HomeController::class, 'Add_Vendor'])->name('add_vendor');

// ------------------------------------- Timesheet section routes --------------------------------------------

Route::get('/timesheet', [HomeController::class, 'Timesheet'])->name('timesheet')->middleware('admin');
Route::post('/add_timesheet', [HomeController::class, 'Add_Timesheet'])->name('add_timesheet');
Route::post('/update_timesheet', [HomeController::class, 'Update_Timesheet'])->name('timesheet.update');
Route::post('/delete_timesheet', [HomeController::class, 'Delete_Timesheet'])->name('delete_timesheet');
Route::post('copy_timesheet_data', [HomeController::class , 'copy_timesheet_data'])->name('copy_timesheet_data');


//----------------------------------------Project Section routes-------------------------------

Route::get('/project',[HomeController::class, 'project'])->name('project')->middleware('admin');
Route::post('project_add', [HomeController::class, 'project_add'])->name('project_add');
Route::post('project_edit', [HomeController::class, 'project_edit'])->name('project_edit');
Route::post('project_delete', [HomeController::class, 'project_delete'])->name('project_delete');
Route::post('import_project_excel', [HomeController::class, 'import_project_excel'])->name('import_project_excel');
Route::get('/project_download', [HomeController::class, 'project_sample_download'])->name('project_download');
Route::get('export_project_excel', [HomeController::class, 'export_project_excel'])->name('export_project_excel');
//----------------------------------------Project Asign User Section routes-------------------------------

Route::get('/project_asign/{id}',[HomeController::class, 'project_asign'])->name('asign_project')->middleware('admin');
Route::post('asign_add/{id}', [HomeController::class, 'asign_add'])->name('asign_add');
Route::post('asign_delete', [HomeController::class, 'asign_delete'])->name('asign_delete');
Route::post('asign_edit', [HomeController::class, 'asign_edit'])->name('asign_edit');
Route::get('/project_assign_download', [HomeController::class, 'project_assign_download'])->name('project_assign_download');
Route::post('import_project_assign_excel', [HomeController::class, 'import_project_assign_excel'])->name('import_project_assign_excel');

//----------------------------------------Project Asign Client Section routes-------------------------------

Route::get('/project_asign_client/{id}',[HomeController::class, 'project_asign_client'])->name('asign_project_client')->middleware('admin');
Route::post('client_asign_add/{id}', [HomeController::class, 'client_asign_add'])->name('client_asign_add');
Route::post('client_asign_delete', [HomeController::class, 'client_asign_delete'])->name('client_asign_delete');
Route::post('client_asign_edit', [HomeController::class, 'client_asign_edit'])->name('client_asign_edit');
// ----------------------------------- Type of Employment--------------------------
Route::get('employment', [HomeController::class, 'employment'])->name('employment')->middleware('admin');
Route::post('employment_add', [HomeController::class, 'employment_add'])->name('employment_add');
Route::post('employment_delete', [HomeController::class, 'employment_delete'])->name('employment_delete');
// ----------------------------------- Type of System Access--------------------------
Route::get('system_access', [HomeController::class, 'system_access'])->name('system_access')->middleware('admin');
Route::post('system_access_add', [HomeController::class, 'system_access_add'])->name('system_access_add');
Route::post('system_access_delete', [HomeController::class, 'system_access_delete'])->name('system_access_delete');

// ----------------------------------- Type of Project Terms--------------------------
Route::get('terms', [HomeController::class, 'terms'])->name('terms')->middleware('admin');
Route::post('terms_add', [HomeController::class, 'terms_add'])->name('terms_add');
Route::post('terms_delete', [HomeController::class, 'terms_delete'])->name('terms_delete');
// -------------------------------------- Timesheet report routes ----------------------------------------------

Route::get('/report/{id}', [HomeController::class, 'Report'])->name('report');
Route::post('/report', [HomeController::class, 'Report_generate'])->name('generate_report');
Route::get('/show_report/{id}', [HomeController::class, 'ShowReport'])->name('show_report')->middleware('admin');
Route::get('/show_report_by_user/{user_id}/{id}', [HomeController::class, 'ShowUserReport'])->name('show_user_report')->middleware('admin');

Route::get('/show_report_by_user-client/{user_id}/{client_id}/{id}', [HomeController::class, 'ShowUserClientReport'])->name('show_userclient_report')->middleware('admin');

Route::get('/export/{id}/{timesheet_id}', [HomeController::class, 'export'])->name('export');
Route::get('/export_filter/{user}/{client}/{month}/{year}/{timesheet_id}', [HomeController::class, 'export_filter'])->name('export_filter');

Route::get('/timesheet_export', [HomeController::class, 'TimesheetExport'])->name('timesheet_export');
Route::get('/timesheet_export_filter/{user}/{client}/{month}/{year}', [HomeController::class, 'TimesheetExportFilter'])->name('timesheet_export_filter');


Route::get('/user_export/{id}', [HomeController::class, 'user_export'])->name('user_export');
Route::get('/export_complete/{id}', [HomeController::class, 'export_complete'])->name('export_complete');

Route::get('/user_export_admin/{id}/{timesheet_id}', [HomeController::class, 'user_export_admin'])->name('user_export_admin');

Route::get('/user_list_export', [HomeController::class, 'user_list_export'])->name('user_list_export');
Route::get('/client_list_export', [HomeController::class, 'client_list_export'])->name('client_list_export');
Route::get('/add-row', [HomeController::class, 'addRow'])->name('add.row');
Route::delete('/delete-row/{id}', [HomeController::class, 'deleteRow'])->name('delete.row');

Route::post('/add-client', [HomeController::class, 'Addclient'])->name('add.client');

Route::get('/download/{id}', [HomeController::class, 'sample_download'])->name('download');
Route::get('/user_download', [HomeController::class, 'user_download'])->name('user_download');
Route::get('/client_template', [HomeController::class, 'client_template'])->name('client_template');


Route::get('/test_mail', [HomeController::class, 'test_mail'])->name('test_mail');

Route::post('/upload-excel', [HomeController::class, 'upload'])->name('upload.excel');


Route::post('/import_excel', [HomeController::class, 'import_excel'])->name('import_excel');
Route::post('/import_user_excel', [HomeController::class, 'import_user_excel'])->name('import_user_excel');
Route::post('/import_client_excel', [HomeController::class, 'import_client_excel'])->name('import_client_excel');

Route::get('/user_report_delete/{id}/{timesheet_id}', [HomeController::class, 'Delete_User_Timesheet'])->name('user_report_delete');
Route::get('/get-client-details/{id}', [HomeController::class, 'getClientDetails'])->name('get-client-details');
Route::get('/get-project-details/{id}', [HomeController::class, 'getProjectDetails'])->name('get-project-details');
Route::post('/update-status', [HomeController::class, 'updateStatus'])->name('update.status');

Route::get('/user_project', [HomeController::class , 'user_project'])->name('user_project');
Route::post('project_user_add', [HomeController::class, 'user_project_add'])->name('user_project_add');
Route::post('project_user_edit', [HomeController::class, 'user_project_edit'])->name('user_project_edit');
Route::post('project_user_delete', [HomeController::class, 'user_project_delete'])->name('user_project_delete');
Route::post('choose_project',[HomeController::class, 'choose_project'])->name('choose_project');
// ---------------------------------- Invoice  section Routes-------------------------

Route::get('/invoice', [HomeController::class, 'Invoice'])->name('invoice')->middleware('admin');
Route::get('/invoice_export/{user}/{client}/{month}/{year}', [HomeController::class, 'invoice_export'])->name('invoice_export');
Route::post('/update-invoice-status', [HomeController::class, 'updateInvoiceStatus'])->name('update.invoice.status');

// ---------------------------------- bill  section Routes-------------------------

Route::get('/bill', [HomeController::class, 'Bill'])->name('bill')->middleware('admin');
Route::get('/bill_export/{user}/{client}/{month}/{year}', [HomeController::class, 'bill_export'])->name('bill_export');
Route::post('/update-bill-status', [HomeController::class, 'updateBillStatus'])->name('update.bill.status');

// ----------------------------user_edit------------------------
Route::get('/user_edit/{id}', [HomeController::class, 'user_edit'])->name('user_edit')->middleware('admin');
Route::post('/user_update/{id}', [HomeController::class, 'user_update'])->name('user_update');

// ----------------------------user_vendor_edit------------------------
Route::get('/vendor_edit', [HomeController::class, 'vendor_edit'])->name('vendor_edit')->middleware('admin');
Route::post('/vendor_update', [HomeController::class, 'vendor_update'])->name('vendor_update');

Route::get('/switch-to-user', [HomeController::class, 'switchToUser']);
Route::get('/impersonate', [HomeController::class, 'impersonateUser']);

Route::get('/stop-impersonating', [HomeController::class, 'stopImpersonating']);

// -------------------------------- timesheet email----------------------
Route::get('/timesheet_email/{id}', [HomeController::class, 'timesheet_email'])->name('timesheet_email');


// -------------------------------- Expenses section routes ----------------------------------------------

Route::get('/expenses', [HomeController::class, 'Expenses'])->name('expenses')->middleware('admin');
Route::post('/add_expenses', [HomeController::class, 'Add_expenses'])->name('add_expenses');
Route::post('/edit_expenses', [HomeController::class, 'Edit_expenses'])->name('edit_expenses');
Route::post('/delete_expenses', [HomeController::class, 'Delete_expenses'])->name('delete_expenses');

// --------------------------------Expenses Status routes--------------------------

Route::get('/expenses_status',[HomeController::class, 'Expenses_status'])->name('expenses_status')->middleware('admin');
Route::post('status_update/', [HomeController::class, 'status_update'])->name('status_update');


// ------------------------------------------timesheet Approval-----------------
Route::post('/timesheet_approval/{id}',[HomeController::class, 'timesheet_approval'])->name('timesheet_approval');

// -----------------------------------timesheet approval status---------------------------

Route::get('/timesheet_status',[HomeController::class, 'timesheet_status'])->name('timesheet_status')->middleware('admin');
Route::post('/timesheet_status_update/{timesheet_id}/{user_id}', [HomeController::class, 'timesheet_status_update'])->name('timesheet_status_update');
Route::get('/timesheet_status_view_report/{timesheet_id}/{user_id}',[HomeController::class, 'timesheet_status_view_report'])->name('timesheet_status_view_report');
Route::get('/timesheet/download-attachment/{timesheet_id}/{user_id}', [HomeController::class, 'downloadAttachment'])->name('timesheet.download_attachment');
Route::get('/timesheet_status_export/{month}/{year}/{status?}', [HomeController::class, 'timesheet_status_export'])->name('timesheet_status_export');




// --------------------------------------admin -------------------------------------
Route::get('/admin',[HomeController::class , 'admin'])->name('admin')->middleware('admin');
Route::post('add_admin',[HomeController::class , 'add_admin'])->name('add_admin');
Route::post('/edit_admin',[HomeController::class , 'edit_admin'])->name('edit_admin');
Route::post('/delete_admin',[HomeController::class , 'delete_admin'])->name('delete_admin');


// -----------------------------------project asign report-----------------
Route::get('/project_asign_report',[HomeController::class, 'project_asign_report'])->name('project_asign_report')->middleware('admin');

// -------------------------- update user status----------------------
Route::post('/user_status',[HomeController::class  , 'user_status'])->name('user_status');

// --------------------------------- copy data of timesheet-------------------
