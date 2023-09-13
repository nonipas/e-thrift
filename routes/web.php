<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    $pageTitle = "Dashboard";
    return view('dashboard', compact('pageTitle'));
});

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

    Route::get('/approve-payment', function () {
        $pageTitle = "Approve payment";
        return view('payment.approve', compact('pageTitle'));
    })->name('approve');

    Route::get('/process-payment', function () {
        $pageTitle = "Process and  Make payment";
        return view('payment.process', compact('pageTitle'));
    })->name('approve');

});

// Role route
Route::prefix('roles')->name('role.')->group(function (){
    Route::get('/', function () {
        $pageTitle = "Roles and  Permission";
        return view('role.table', compact('pageTitle'));
    })->name('index');

    Route::get('/add', function () {
        $pageTitle = "Add New Role";
        return view('role.form', compact('pageTitle'));
    })->name('add');

    Route::get('/permissions', function () {
        $pageTitle = "Role permissions";
        return view('role.permission', compact('pageTitle'));
    })->name('permission');

});

// Setting route
Route::prefix('sttings')->name('setting.')->group(function (){
    Route::get('/', function () {
        $pageTitle = "General Settings";
        return view('setting.form', compact('pageTitle'));
    })->name('index');

});
