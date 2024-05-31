<?php

use App\Http\Controllers\AttachmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmploymentController;
use App\Http\Controllers\BankInformationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\RoleController;
use App\Myob\Services\Employee\EmployeeServices;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::group(['middleware' => ['web']], function () {
    Route::post('auth/login', [UserController::class, 'login'])->middleware('throttle:5,1');
    Route::post('auth/logout', [UserController::class, 'logout'])->middleware('throttle:5,1');
});



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('leave', LeaveController::class);
    Route::resource('leave_type', LeaveTypeController::class);
    Route::resource('leave_balance', LeaveBalanceController::class);
    Route::resource('division', DivisionController::class);
    Route::resource('employee', EmployeeController::class);
    Route::resource('location', LocationController::class);
    Route::resource('employment', EmploymentController::class);
    Route::resource('bank_info', BankInformationController::class);
    Route::resource('role', RoleController::class);


    Route::group(['prefix' => 'leave_balance'], function () {
        Route::post('fetch_leave_balance', [LeaveBalanceController::class, 'fetchLeaveBalance']);
    });
    Route::group(['prefix' => 'leave'], function () {
        Route::get('fetch_leave_by_approver/{employee_id}', [LeaveController::class, 'fetchLeaveByApprover']);
        Route::post('update_row', [LeaveController::class, 'update_row']);
        Route::post('multiple_filter', [LeaveController::class, 'multipleFilter']);
    });
    Route::group(['prefix' => 'employee'], function () {
        Route::post('update_row', [EmployeeController::class, 'update_row']);
        Route::post('update_emergency_contact', [EmployeeController::class, 'update_emergency_contact']);
        Route::post('update_medical', [EmployeeController::class, 'update_medical']);
        Route::post('update_address', [EmployeeController::class, 'update_address']);
        Route::post('fetch_approvers', [EmployeeController::class, 'fetch_approvers']);
        Route::post('fetch_line_managers', [EmployeeController::class, 'fetch_line_managers']);
        Route::post('fetch_employee_by_name', [EmployeeController::class, 'fetch_employee_by_name']);
        Route::post('fetch_widget_data', [EmployeeController::class, 'fetch_widget_data']);
        Route::post('multiple_filter', [EmployeeController::class, 'multipleFilter']);
        Route::post('fetch_employees', [EmployeeController::class, 'fetch_employees']);
        Route::post('update_attachment', [EmployeeController::class, 'update_attachment']);
    });

    Route::group(['prefix' => 'myob'], function () {
        Route::get('contact/employee', [EmployeeServices::class, 'list']);
        Route::get('fetch_employee_by_display_id/{displayID}', [EmployeeServices::class, 'fetchEmployeeByDisplayID']);
    });
});
