<?php
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AccountController;
    use App\Http\Controllers\BranchController;
    use App\Http\Controllers\LocationController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\UserCustomerController;
    use App\Http\Controllers\CustomLoginController;

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

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::controller(AccountController::class)->group(function () {
        Route::get('/accounts', 'index');
        Route::get('accounts/{id}', 'show');
        Route::post('/accounts', 'store');
        Route::put('accounts/{id}', 'update');
        Route::delete('accounts/{id}', 'destroy');
    });

    // branches
    Route::controller(BranchController::class)->group(function () {
        Route::get('/branches', 'index');
        Route::get('branches/{id}', 'show');
        Route::post('/branches', 'store');
        Route::put('branches/{id}', 'update');
        Route::delete('branches/{id}', 'destroy');
    });

    // locations
    Route::controller(LocationController::class)->group(function () {
        Route::get('/locations', 'index');
        Route::get('/regions', 'regions');
        Route::get('/districts', 'districts');
        Route::get('/wards', 'wards');
    });

    // user employees
    Route::controller(UserController::class)->group(function () {
        Route::get('/employees', 'employees');
        Route::get('/roles', 'roles');
        Route::get('employees/{id}', 'show');
        Route::post('/employees', 'store');
        Route::put('employees/{id}', 'update');
        Route::delete('employees/{id}', 'destroy');
    });

    // user customers
    Route::controller(UserCustomerController::class)->group(function () {
        Route::get('/customers', 'index');
        Route::get('customers/{id}', 'show');
        Route::post('/customers', 'store');
        Route::put('customers/{id}', 'update');
        Route::delete('customers/{id}', 'destroy');
    });

    Route::controller(CustomLoginController::class)->group(function () {
        Route::get('/users', 'users');
        Route::post('/users', 'login');
        Route::post('/logout', 'logout');
    });
