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
Route::get('/forgot-password', function () {
    $pageTitle = "Forgot Password";
    return view('forgot_password', compact('pageTitle'));
})->name('forgot-password');

//send reset password link
Route::post('/send-reset-password-link', [App\Http\Controllers\AuthController::class, 'sendResetPasswordLink'])->name('send-reset-password-link');

//reset password
Route::get('/reset-password/{code}', [App\Http\Controllers\AuthController::class, 'resetPassword'])->name('reset-password');
Route::post('/reset-password', [App\Http\Controllers\AuthController::class, 'storeResetPassword'])->name('store-reset-password');

//verify email
Route::get('/verify/{code}', [App\Http\Controllers\AuthController::class, 'verifyEmail'])->name('verify-email');

Route::get('/', function () {
    $pageTitle = "Dashboard";
    return view('dashboard', compact('pageTitle'));
})->name('dashboard');

//get account name route
Route::post('/get-account-name', [App\Http\Controllers\GeneralController::class, 'getAccountName'])->name('get-account-name');
Route::get('/search-member', [App\Http\Controllers\GeneralController::class, 'getMembers'])->name('search-member');
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
    Route::delete('/delete/{id}', [ContributionController::class, 'delete'])->name('delete');
    Route::get('/change-status/{id}', [ContributionController::class, 'updateStatus'])->name('change_status');
    Route::post('/generate-monthly', [ContributionController::class, 'generateMonthlyContribution'])->name('generate_monthly');
    Route::post('/approve-monthly', [ContributionController::class, 'approveMonthlyContribution'])->name('approve_monthly');
    Route::get('/approve-monthly/{id}', [ContributionController::class, 'approveMonthlyContributionById'])->name('approve_monthly_id');
    Route::get('/search-monthly', [ContributionController::class, 'searchMonthlyContribution'])->name('search_monthly');

    Route::post('/approve-monthly-member/{id}', [ContributionController::class, 'approveMonthlyContributionForMember'])->name('approve_monthly_member');
    Route::delete('/delete-monthly/{id}', [ContributionController::class, 'deleteMonthlyContribution'])->name('delete_monthly');

    Route::delete('/delete-monthly-detail/{id}', [ContributionController::class, 'deleteMonthlyContributionDetail'])->name('delete_monthly_detail');


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
        $dividend_details = AnnualDividendDetail::where('annual_dividend_id', $id)->get();
        $pageTitle = "Dividend details for " . $dividend->year;
        $isListForApproval = false;
        if ($dividend->is_approved == 0){
            $isListForApproval = true;
        }
        return view('dividend.details_table', compact('pageTitle', 'dividend', 'dividend_details', 'isListForApproval'));
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
    Route::post('/delete/{id}', [DividendController::class, 'delete'])->name('delete');

});

// Loan route
Route::prefix('loans')->name('loan.')->group(function (){
    Route::get('/', function () {
        $pageTitle = "List of loans";
        return view('loan.table', compact('pageTitle'));
    })->name('index');

    Route::get('/add', function () {
        $pageTitle = "Add new loan";
        return view('loan.form', compact('pageTitle'));
    })->name('add');

    Route::get('/repayment-list', function () {
        $pageTitle = "Monthly Repayment List";
        return view('loan.repayment_table', compact('pageTitle'));
    })->name('repayment');

    Route::get('/generate_repayment', function () {
        $pageTitle = "Generate monthly repayment";
        return view('loan.repayment_form', compact('pageTitle'));
    })->name('generate');

    Route::get('/approve-repayment', function () {
        $pageTitle = "Approve monthly repayment";
        return view('loan.approve', compact('pageTitle'));
    })->name('approve');

});

// Payment route
Route::prefix('payments')->name('payment.')->group(function (){
    Route::get('/', function () {
        $pageTitle = "Payment list";
        return view('payment.table', compact('pageTitle'));
    })->name('index');

    Route::get('/add', function () {
        $batch_id = 0;
        if (request()->has('batch_id')){
            $batch_id = request()->get('batch_id');
        }
        $pageTitle = "Add payment to batch";

        return view('payment.form', compact('pageTitle', 'batch_id'));
    })->name('add');

    Route::get('/batch-list', function () {
        $pageTitle = "Batch List";
        return view('payment.batch_table', compact('pageTitle'));
    })->name('batch');

    Route::get('/create-batch', function () {
        $pageTitle = "Create Batch ";
        return view('payment.batch_form', compact('pageTitle'));
    })->name('create-batch');

    Route::get('/batch/approve', function () {
        $id = request()->get('id');
        $pageTitle = "Approve payment";
        return view('payment.approve', compact('pageTitle', 'id'));
    })->name('approve');

    Route::get('/batch/process', function () {
        $pageTitle = "Process and  Make payment";
        return view('payment.process', compact('pageTitle'));
    })->name('approve');

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

