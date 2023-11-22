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

//vrify email
Route::get('/verify/{code}', [App\Http\Controllers\AuthController::class, 'verifyEmail'])->name('verify-email');

Route::get('/', function () {
    $pageTitle = "Dashboard";
    return view('dashboard', compact('pageTitle'));
})->name('dashboard');

// Admin user route
Route::prefix('users')->name('user.')->group(function (){
    Route::get('/', function () {
        $pageTitle = "All Admin Users";
        return view('user.table', compact('pageTitle'));
    })->name('index');

    Route::get('/add', function () {
        $pageTitle = "Add new User";
        $route = route('user.store');
        return view('user.form', compact('pageTitle'));
    })->name('add');

    Route::get('/give-permission', function () {
        $pageTitle = "Admin Users Role and Permission";
        return view('user.role_form', compact('pageTitle'));
    })->name('give_permission');
});

// Members route
Route::prefix('members')->name('member.')->group(function (){
    Route::get('/', function () {
        $pageTitle = "All Members";
        return view('member.table', compact('pageTitle'));
    })->name('index');

    Route::get('/add', function () {
        $pageTitle = "Add new member";
        return view('member.form', compact('pageTitle'));
    })->name('add');

});

// Contributions route
Route::prefix('contributions')->name('contribution.')->group(function (){
    Route::get('/', function () {
        $pageTitle = "List of Contributions";
        return view('contribution.table', compact('pageTitle'));
    })->name('index');

    Route::get('/add', function () {
        $pageTitle = "Add new contribution";
        return view('contribution.form', compact('pageTitle'));
    })->name('add');

    Route::get('/monthly', function () {
        $pageTitle = "Monthly contributions";
        return view('contribution.monthly_table', compact('pageTitle'));
    })->name('monthly');

    Route::get('/generate-monthly', function () {
        $pageTitle = "Generate monthly contribution";
        return view('contribution.monthly_form', compact('pageTitle'));
    })->name('generate');

    Route::get('/approve-monthly', function () {
        $pageTitle = "Approve monthly contribution";
        return view('contribution.approve', compact('pageTitle'));
    })->name('approve');

});

// Dividend route
Route::prefix('dividend')->name('dividend.')->group(function (){
    Route::get('/', function () {
        $pageTitle = "Dividend list";
        return view('dividend.table', compact('pageTitle'));
    })->name('index');

    Route::get('/generate', function () {
        $pageTitle = "Generate Annual Dividend";
        return view('dividend.form', compact('pageTitle'));
    })->name('generate');

    Route::get('/approve', function () {
        $pageTitle = "Approve dividend";
        return view('dividend.approve', compact('pageTitle'));
    })->name('approve');

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

