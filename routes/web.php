<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\PaymentCategoryController;
use App\Http\Controllers\PaymentBatchController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\DividendController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Models\PaymentBatch;
use App\Models\Payment;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Bank;
use App\Models\Contribution;
use App\Models\Activity;
use App\Models\MonthlyContribution;
use App\Models\MonthlyRepayment;
use App\Models\MonthlyContributionDetail;
use App\Models\MonthlyRepaymentDetail;
use App\Models\AnnualDividend;
use App\Models\AnnualDividendDetail;
use App\Models\PaymentCategory;
use App\Models\Loan;
use App\Models\Member;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helpers;
use App\Models\Month;

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

Route::get('/login', function () {
    $pageTitle = "Login";
    return view('login', compact('pageTitle'));
})->name('login');

//auth routes
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login_submit');
Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::get('/recover-password', function () {
    $pageTitle = "Recover Password";
    return view('recover_password', compact('pageTitle'));
})->name('recover-password');

//send reset password link
Route::post('/send-reset-password-link', [App\Http\Controllers\AuthController::class, 'sendResetPasswordLink'])->name('send-reset-password-link');

//reset password
Route::get('/reset-password/{code}', [App\Http\Controllers\AuthController::class, 'resetPassword'])->name('reset-password');
Route::post('/password-reset', [App\Http\Controllers\AuthController::class, 'storeResetPassword'])->name('store-password-reset');

//verify email
Route::get('/verify/{code}', [App\Http\Controllers\AuthController::class, 'verifyEmail'])->name('verify-email');

//group route with auth middleware

Route::middleware(['auth'])->group(function () {

    //dashboard route
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    //get account name route
    Route::post('/get-account-name', [App\Http\Controllers\GeneralController::class, 'getAccountName'])->name('get-account-name');
    Route::get('/search-member', [App\Http\Controllers\GeneralController::class, 'getMembers'])->name('search-member');
    Route::post('/get-member', [App\Http\Controllers\GeneralController::class, 'getMemberDetails'])->name('get-member');
    //save months of the year to database
    Route::get('/save-months', [App\Http\Controllers\GeneralController::class, 'saveMonths'])->name('save-months');

    // Members route
    Route::prefix('members')->name('member.')->group(function (){
        Route::get('/', function () {
            $pageTitle = "All Members";
            $members = Member::all();
            if (request()->has('status')){
                $status = request()->get('status');
                if ($status == 'active'){
                    $status = 1;
                }elseif ($status == 'inactive'){
                    $status = 0;
                }
                $members = Member::where('status', $status)->get();
            }
            Helpers::getBanks();
            Helpers::storeActivity("Viewed member list");
            return view('member.table', compact('pageTitle', 'members'));
        })->name('index');

        Route::get('/add', function () {
            $pageTitle = "Add new member";
            $banks = Bank::all();
            Helpers::storeActivity("Viewed add member form");
            return view('member.form', compact('pageTitle', 'banks'));
        })->name('add');

        Route::get('/edit/{id}', function ($id) {
            $pageTitle = "Edit member";
            $member = Member::find($id);
            $banks = Bank::all();
            Helpers::storeActivity("Viewed edit member form");
            return view('member.form', compact('pageTitle', 'member', 'banks'));
        })->name('edit');

        Route::post('/store', [MemberController::class, 'store'])->name('store');
        Route::post('/update/{id}', [MemberController::class, 'update'])->name('update');
        Route::get('/change-status/{id}', [MemberController::class, 'updateStatus'])->name('change_status');

    });

    // Contributions route
    Route::prefix('contributions')->name('contribution.')->group(function (){
        Route::get('/', function () {
            $pageTitle = "List of Contributions";
            $contributions = Contribution::all();
            if (request()->has('status')){
                $status = request()->get('status');
                if ($status == 'active'){
                    $status = 1;
                }elseif ($status == 'inactive'){
                    $status = 0;
                }
                $contributions = Contribution::where('status', $status)->get();
            }
            return view('contribution.table', compact('pageTitle', 'contributions'));
        })->name('index');

        Route::get('/add', function () {
            $pageTitle = "Add new contribution";
            $members = Member::all();
            return view('contribution.form', compact('pageTitle', 'members'));
        })->name('add');

        Route::get('/edit/{id}', function ($id) {
            $pageTitle = "Edit contribution";
            $contribution = Contribution::find($id);
            $members = Member::all();
            return view('contribution.form', compact('pageTitle', 'contribution', 'members'));
        })->name('edit');

        Route::get('/approve-monthly/{id}', function ($id) {
            $monthly_contributions = MonthlyContribution::find($id);
            $pageTitle = "Approve monthly contribution for " . $monthly_contributions->month . " " . $monthly_contributions->year;
            // $monthly_contribution_details = [];
            
            $monthly_contribution_details = MonthlyContributionDetail::where('monthly_contribution_id', $id)->get();
            
            $months = \App\Models\Month::all();
            return view('contribution.monthly_table', compact('pageTitle', 'monthly_contributions', 'months', 'monthly_contribution_details'));
        })->name('monthly');

        Route::get('/monthly/list', function () {
            $pageTitle = "Monthly contribution list";
            $monthly_contribution_details = MonthlyContributionDetail::all();
            $months = \App\Models\Month::all();
            $data = array(
                'year' => '',
                'month' => '',
            );
            $list = true;
            
            return view('contribution.monthly_table', compact('pageTitle', 'monthly_contribution_details', 'months', 'list', 'data'));
        })->name('monthly_list');

        Route::get('/monthly/approve/detail/{id}', function ($id) {
            $months = \App\Models\Month::all();
            
            $monthly_contribution = MonthlyContribution::find($id);
            $pageTitle = "Approve monthly contribution for " . $monthly_contribution->month . " " . $monthly_contribution->year;
            $data = array(
                'year' => $monthly_contribution->year,
                'month' => $monthly_contribution->month,
            );

            $monthly_contribution_details = MonthlyContributionDetail::where('monthly_contribution_id', $id)->get();
            $list = false;
            return view('contribution.monthly_table', compact('pageTitle', 'monthly_contribution_details', 'months', 'list', 'data'));
        })->name('monthly_detail');

        Route::get('/generate-monthly', function () {
            $pageTitle = "Generate monthly contribution";
            $months = \App\Models\Month::all();
            return view('contribution.monthly_form', compact('pageTitle', 'months'));
        })->name('generate');

        Route::get('/approve-monthly', function () {
            $pageTitle = "Approve monthly contribution";
            $monthly_contributions = MonthlyContribution::where('is_approved', 0)->get();
            return view('contribution.approve', compact('pageTitle', 'monthly_contributions'));
        })->name('approve');

        Route::post('/store', [ContributionController::class, 'store'])->name('store');
        Route::post('/update/{id}', [ContributionController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [ContributionController::class, 'delete'])->name('delete');
        Route::get('/change-status/{id}', [ContributionController::class, 'updateStatus'])->name('change_status');
        Route::post('/generate-monthly', [ContributionController::class, 'generateMonthlyContribution'])->name('generate_monthly');
        Route::post('/approve-monthly', [ContributionController::class, 'approveMonthlyContribution'])->name('approve_monthly');
        Route::get('/approve-monthly/{id}', [ContributionController::class, 'approveMonthlyContributionById'])->name('approve_monthly_id');
        Route::get('/search-monthly', [ContributionController::class, 'searchMonthlyContribution'])->name('search_monthly');

        Route::post('/approve-monthly-member/{id}', [ContributionController::class, 'approveMonthlyContributionForMember'])->name('approve_monthly_member');
        Route::get('/delete-monthly/{id}', [ContributionController::class, 'deleteMonthlyContribution'])->name('delete_monthly');

        Route::get('/delete-monthly-detail/{id}', [ContributionController::class, 'deleteMonthlyContributionDetail'])->name('delete_monthly_detail');


    });

    // Dividend route
    Route::prefix('dividend')->name('dividend.')->group(function (){
        Route::get('/', function () {
            $pageTitle = "Dividend list";
            //get all dividends
            $dividends = AnnualDividend::all();
            
            return view('dividend.table', compact('pageTitle', 'dividends'));
        })->name('index');

        Route::get('/generate', function () {
            $pageTitle = "Generate Annual Dividend";
            return view('dividend.form', compact('pageTitle'));
        })->name('generate');

        Route::get('/approve', function () {
            $pageTitle = "Approve dividend";
            $dividends = AnnualDividend::where('is_approved', 0)->get();
            return view('dividend.table', compact('pageTitle', 'dividends'));
        })->name('approve');

        //view dividend details
        Route::get('view/details/{id}', function ($id) {
            
            $dividend = AnnualDividend::find($id);
            $total_amount = $dividend->total_amount;
            $dividend_details = AnnualDividendDetail::where('annual_dividend_id', $id)->get();
            $pageTitle = "Dividend details for " . $dividend->year;
            $isListForApproval = false;
            if ($dividend->is_approved == 0){
                $isListForApproval = true;
            }
            return view('dividend.details_table', compact('pageTitle', 'dividend', 'dividend_details', 'isListForApproval', 'total_amount'));
        })->name('details');

        //view all dividend details
        Route::get('view/details', function () {
            
            $dividend_details = AnnualDividendDetail::all();
            // limit to only 500 records
            $dividend_details = $dividend_details->take(500);
            $pageTitle = "Dividend details";
            $isListForApproval = false;
            return view('dividend.details_table', compact('pageTitle', 'dividend_details', 'isListForApproval'));
        })->name('all_details');

        //store generated dividend
        Route::post('/store', [DividendController::class, 'store'])->name('store');
        Route::post('/approve', [DividendController::class, 'approve'])->name('approve');
        Route::get('/approve/{id}', [DividendController::class, 'approveById'])->name('approve_id');
        Route::get('/approve/detail/{id}', [DividendController::class, 'approveDetail'])->name('approve_detail');
        Route::get('/delete/detail/{id}', [DividendController::class, 'deleteDetail'])->name('delete_detail');
        Route::get('/search', [DividendController::class, 'search'])->name('search');
        Route::get('/delete/{id}', [DividendController::class, 'delete'])->name('delete');

    });

    // Loan route
    Route::prefix('loans')->name('loan.')->group(function (){
        Route::get('/', function () {
            $pageTitle = "List of loans";
            $loans = Loan::all();
            $months = Month::all();
            return view('loan.table', compact('pageTitle', 'loans', 'months'));
        })->name('index');

        Route::get('/add', function () {
            $pageTitle = "Add new loan";
            $months = Month::all();
            $banks = Bank::all();
            $members = Member::where('status',1)->get();
            return view('loan.form', compact('pageTitle', 'months', 'banks', 'members'));
        })->name('add');

        Route::get('/edit/{id}', function ($id) {
            $pageTitle = "Edit Loan";
            $loan = Loan::find($id);
            $months = Month::all();
            $banks = Bank::all();
            $members = Member::where('status',1)->get();
            return view('loan.form', compact('pageTitle', 'loan', 'members'));
        })->name('edit');

        Route::get('/repayment-list', function () {
            $pageTitle = "Monthly Repayment List";
            $repayments = MonthlyRepaymentDetail::all();
            $repayments = $repayments->take(500);
            $months = \App\Models\Month::all();
            $data = array(
                'year' => '',
                'month' => '',
            );
            $list = true;
            return view('loan.repayment_table', compact('pageTitle', 'repayments', 'months', 'list', 'data'));
        })->name('repayment');

        Route::get('/monthly_repayment/detail/{id}/approve', function ($id) {
            $months = \App\Models\Month::all();
            
            $monthly_repayment = MonthlyRepayment::find($id);
            $pageTitle = "Approve monthly Repayment for " . $monthly_repayment->month . " " . $monthly_repayment->year;
            $data = array(
                'year' => $monthly_repayment->year,
                'month' => $monthly_repayment->month,
            );

            $repayments = MonthlyRepaymentDetail::where('monthly_repayment_id', $id)->get();
            $list = false;
            return view('loan.repayment_table', compact('pageTitle', 'repayments', 'months', 'list', 'data'));
        })->name('monthly_detail');

        Route::get('/generate_repayment', function () {
            $pageTitle = "Generate monthly repayment";
            $months = Month::all();
            return view('loan.repayment_form', compact('pageTitle', 'months'));
        })->name('generate');

        Route::get('/approve-repayment', function () {
            $pageTitle = "Approve monthly repayment";
            $repayments = MonthlyRepayment::where('is_approved', 0)->get();
            return view('loan.approve', compact('pageTitle', 'repayments'));
        })->name('approve');

        //view repayment details for a loan
        Route::get('view/repayment/{id}', function ($id) {
            $loan = Loan::find($id);
            $pageTitle = "Repayment details for " . $loan->member->name;
            $repayments = MonthlyRepaymentDetail::where('loan_id', $id)->get();
            $list = true;
            $single = true;
            return view('loan.repayment_table', compact('pageTitle', 'loan', 'repayments', 'list', 'single'));
        })->name('view_repayments');

        Route::post('/store', [LoanController::class, 'store'])->name('store');
        Route::post('/update/{id}', [LoanController::class, 'update'])->name('update');
        Route::post('top-up', [LoanController::class, 'storeTopUp'])->name('top_up');
        Route::post('update-repayment-amount', [LoanController::class, 'updateRepaymentAmount'])->name('update_repayment_amount');
        Route::get('/deactivate/{id}', [LoanController::class, 'deactivate'])->name('deactivate');
        Route::get('/activate/{id}', [LoanController::class, 'activate'])->name('activate');
        Route::post('/generate-monthly', [LoanController::class, 'generateMonthlyRepayment'])->name('generate_monthly');
        Route::post('/approve-monthly', [LoanController::class, 'approveMonthlyRepayment'])->name('approve_monthly');
        Route::get('/approve-monthly/{id}', [LoanController::class, 'approveMonthlyRepaymentById'])->name('approve_monthly_id');
        Route::get('/search-monthly', [LoanController::class, 'searchMonthlyRepayment'])->name('search_monthly');

        Route::get('/approve-monthly/member/{id}', [LoanController::class, 'approveMonthlyRepaymentDetailById'])->name('approve_monthly_member');
        Route::get('/reject-monthly/{id}', [LoanController::class, 'rejectMonthlyRepaymentById'])->name('reject_monthly');

        Route::get('/reject-monthly/member/{id}', [LoanController::class, 'rejectMonthlyRepaymentDetail'])->name('reject_monthly_member');

    });

    // Payment route
    Route::prefix('payments')->name('payment.')->group(function (){
        Route::get('/', function () {
            $pageTitle = "Search Payment History";
            $batches = PaymentBatch::where('is_approved', 1)->where('is_processed',1)->get();
            $payment_categories = PaymentCategory::all();
            $banks = Bank::all();
            $isListForApproval = false;
            return view('payment.index', compact('pageTitle', 'batches', 'isListForApproval', 'payment_categories', 'banks'));
        })->name('index');

        Route::get('/view/batch/{id}', function ($id) {
            $batch_name = PaymentBatch::find($id)->name;
            $pageTitle = "Payment list for ".$batch_name;
            $payments = Payment::where('payment_batch_id', $id)->get();
            $isListForApproval = false;
            return view('payment.table', compact('pageTitle', 'payments', 'isListForApproval'));
        })->name('view_batch');

        Route::get('/add/batch/{id}', function ($id) {
            $batch_name = '';
            $batch_id = 0;
            if ($id){
                $batch_id = $id;
                $batch_name = PaymentBatch::find($id)->name;
            }
            $banks = Bank::all();
            $payment_categories = PaymentCategory::all();
            
            $pageTitle = "Add payment to batch";

            return view('payment.form', compact('pageTitle', 'batch_id', 'banks', 'payment_categories', 'batch_name'));
        })->name('add_to_batch');

        Route::get('/add', function () {
            $pageTitle = "Add new payment";
            $batch_id = 0;
            $batches = PaymentBatch::where('is_approved', 0)->where('is_processed',0)->get();
            return view('payment.form', compact('pageTitle', 'batches', 'batch_id'));
        })->name('add');

        Route::get('/batch-list', function () {
            $pageTitle = "Batch List";
            $batches = PaymentBatch::where('is_approved', 0)->where('is_processed',0)->get();
            $isListForApproval = false;
            return view('payment.batch_table', compact('pageTitle', 'batches', 'isListForApproval'));
        })->name('batch');

        Route::get('/create-batch', function () {
            $pageTitle = "Create Batch ";
            return view('payment.batch_form', compact('pageTitle'));
        })->name('create-batch');

        Route::get('/batch/approve', function () {
            $pageTitle = "Approve Payment batches";
            $batches = PaymentBatch::where('is_approved', 0)->where('is_processed',0)->get();
            //batches where size greater than 0
            $batches = $batches->filter(function ($batch){
                return $batch->payments->count() > 0;
            });

            $isListForApproval = true;
            return view('payment.batch_table', compact('pageTitle', 'batches', 'isListForApproval'));
        })->name('approve_batches');

        //view batch payments for approval
        Route::get('/batch/view/approve/{id}', function ($id) {
            $batch = PaymentBatch::find($id);
            $pageTitle = "Approve Payment batches";
            $payments = Payment::where('payment_batch_id', $id)->get();
            $isListForApproval = true;
            return view('payment.approve', compact('pageTitle', 'payments', 'isListForApproval', 'batch'));
        })->name('view_batch_approve');

        Route::get('/batch/process', function () {
            $pageTitle = "Process Batches and  Make payment";
            $batches = PaymentBatch::where('is_approved', 1)->where('is_processed',0)->get();
            $isListForApproval = true;
            $process = true;
            return view('payment.batch_table', compact('pageTitle', 'batches', 'isListForApproval', 'process'));
        })->name('process_batches');

        //view batch payments for processing
        Route::get('/batch/view/process/{id}', function ($id) {
            $batch = PaymentBatch::find($id);
            $pageTitle = "Process Batches and  Make payment";
            $payments = Payment::where('payment_batch_id', $id)->where('is_approved',1)->get();
            $isListForApproval = true;
            return view('payment.process', compact('pageTitle', 'payments', 'isListForApproval', 'batch'));
        })->name('process');

        Route::get('/batch/view/processed/{id}', function ($id) {
            $batch = PaymentBatch::find($id);
            $pageTitle = "Processed Payments for ".$batch->name."";
            $payments = Payment::where('payment_batch_id', $id)->where('is_approved',1)->where('is_processed',1)->get();
            return view('payment.processed.payments', compact('pageTitle', 'payments', 'batch'));
        })->name('processed');

        Route::get('/processed_batches', function () {
            $pageTitle = "Search Processed Payment Batches";
            return view('payment.processed.form', compact('pageTitle'));
        })->name('search_processed');

        Route::post('search/processed_batches', [PaymentController::class, 'searchProcessedBatches'])->name('search_processed_batches');
        Route::post('/create/batch', [PaymentController::class, 'createPaymentBatch'])->name('store_batch');
        Route::post('/store', [PaymentController::class, 'storePayment'])->name('store');
        Route::get('/batch/approve/{id}', [PaymentController::class, 'approvePaymentBatch'])->name('approve_batch');
        Route::get('/batch/process/{id}', [PaymentController::class, 'processPaymentBatch'])->name('process_batch');
        Route::post('/search', [PaymentController::class, 'searchPaymentsByFilter'])->name('search');
        Route::post('/approve', [PaymentController::class, 'approvePayment'])->name('approve');
        Route::get('/approve/{id}', [PaymentController::class, 'approvePayment'])->name('approve_id');
        Route::get('/process/{id}', [PaymentController::class, 'processPayment'])->name('process_id');
        Route::get('/delete/batch/{id}', [PaymentController::class, 'deletePaymentBatch'])->name('delete_batch');
        Route::get('/delete/{id}', [PaymentController::class, 'deletePayment'])->name('delete');
        Route::post('/delete-selected', [PaymentController::class, 'deleteUnapprovedPayments'])->name('delete_selected');
        Route::post('/approve-selected', [PaymentController::class, 'approveSelectedPayments'])->name('approve_selected');
        Route::post('/process-selected', [PaymentController::class, 'processSelectedPayments'])->name('process_selected');
        Route::post('/batch/approve-selected', [PaymentController::class, 'approveSelectedPaymentBatch'])->name('approve_selected_batch');
        Route::post('/batch/process-selected', [PaymentController::class, 'processSelectedPaymentBatch'])->name('process_selected_batch');
        Route::post('/reject-selected', [PaymentController::class, 'rejectSelectedPayments'])->name('reject_selected');
        Route::post('/batch/reject-selected', [PaymentController::class, 'rejectSelectedPaymentBatch'])->name('reject_selected_batch');
        Route::post('/batch/reject/{id}', [PaymentController::class, 'rejectPaymentBatch'])->name('reject_batch');
        Route::post('/reject/{id}', [PaymentController::class, 'rejectPayment'])->name('reject');
        //add system payment to batch
        Route::post('/add-system-payment', [PaymentController::class, 'addSystemPaymentsToBatch'])->name('add_system_payment');

    });

    // Role route
    Route::prefix('roles')->name('role.')->group(function (){
        Route::get('/', function () {
            $pageTitle = "Roles and  Permission";
            $roles = Role::all();
            return view('role.table', compact('pageTitle', 'roles'));
        })->name('index');

        Route::get('/add', function () {
            $pageTitle = "Add New Role";
            $permissions = Permission::all();
            return view('role.form', compact('pageTitle', 'permissions'));
        })->name('add');

        Route::get('/edit/{id}', function ($id) {
            $pageTitle = "Edit Role";
            $role = Role::find($id);
            $permissions = Permission::all();
            //check if role has permission
            $role_permissions = [];
            if ($role->permissions){
                $role_permissions = explode(',', $role->permissions);
            }

            return view('role.form', compact('pageTitle', 'role', 'permissions', 'role_permissions'));
        })->name('edit');

        // store role
        Route::post('/store', [RoleController::class, 'store'])->name('store');
        Route::post('/update/{id}', [RoleController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [RoleController::class, 'delete'])->name('delete');
        Route::get('/change-status/{id}', [RoleController::class, 'changeStatus'])->name('change_status');


    });

    // Setting route
    Route::prefix('settings')->name('setting.')->group(function (){
        Route::get('/', function () {
            $pageTitle = "General Settings";
            $settings = Setting::all();
            //key value pair
            $settings_array = [];
            foreach ($settings as $setting){
                $settings_array[$setting->key] = $setting->value;
            }
            return view('settings.form', compact('pageTitle', 'settings_array'));
        })->name('index');

        Route::get('/payment/category', function () {
            $pageTitle = "Payment Category";
            $payment_categories = PaymentCategory::all();
            $payment_category = null;
            if (request()->has('id')){
                $id = request()->get('id');
                $payment_category = PaymentCategory::find($id);
            }
            return view('settings.payment_category', compact('pageTitle', 'payment_categories', 'payment_category'));
        })->name('payment_category');

        Route::get('/permissions', function () {
            $pageTitle = "Manage Permmisions";
            $permissions = Permission::all();
            $permission = null;
            if (request()->has('id')){
                $id = request()->get('id');
                $permission = Permission::find($id);
            }
            return view('settings.permissions', compact('pageTitle', 'permissions', 'permission'));
        })->name('permission');

        Route::post('/update', [SettingController::class, 'update'])->name('update');
        Route::post('/payment/category/store', [SettingController::class, 'storePaymentCategory'])->name('payment_category_store');
        Route::post('/payment/category/update/{id}', [SettingController::class, 'updatePaymentCategory'])->name('payment_category_update');
        Route::get('/payment/category/delete/{id}', [SettingController::class, 'deletePaymentCategory'])->name('payment_category_delete');
        Route::post('/permission/store', [SettingController::class, 'storePermission'])->name('permission_store');
        Route::post('/permission/update/{id}', [SettingController::class, 'updatePermission'])->name('permission_update');
        Route::get('/permission/delete/{id}', [SettingController::class, 'deletePermission'])->name('permission_delete');
        Route::get('/permission/change-status/{id}', [SettingController::class, 'changePermissionStatus'])->name('permission_change_status');

    });

    // User route
    Route::prefix('user')->group(function (){

        //user management route
        Route::name('user.')->group(function(){

            Route::get('/', [UserController::class, 'index'])->name('index');

            Route::get('/add', function () {
                $pageTitle = "Add new User";
                $roles = Role::all();
                return view('user.form', compact('pageTitle', 'roles'));
            })->name('add');
        
            Route::get('/edit/{id}', function ($id) {
                $pageTitle = "Edit User";
                $user = User::find($id);
                $roles = Role::all();
                return view('user.form', compact('pageTitle', 'user', 'roles'));
            })->name('edit');

            Route::post('/store', [UserController::class, 'store'])->name('store');
            Route::post('/update/{id}', [UserController::class, 'update'])->name('update');
            Route::get('/delete/{id}', [UserController::class, 'delete'])->name('delete');
            Route::get('/change-status/{id}', [UserController::class, 'changeStatus'])->name('change_status');
            Route::get('/reset-password/{id}', [UserController::class, 'resetPassword'])->name('reset_password');

        });
        

        //user profile route
        Route::name('profile.')->group(function (){
            Route::get('/profile', function () {
                $pageTitle = "User Profile";
                $user = Auth::user();
                return view('user.profile', compact('pageTitle', 'user'));
            })->name('index');

            Route::get('/change-password', function () {
                $pageTitle = "Change Password";
                return view('user.change_password', compact('pageTitle'));
            })->name('change_password');

            Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('update');
            Route::post('/update-password', [UserController::class, 'updatePassword'])->name('update_password');

        });

    });

});

