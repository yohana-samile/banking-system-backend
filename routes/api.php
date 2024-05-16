<?php
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\AccountController;
    use App\Http\Controllers\BranchController;
    use App\Http\Controllers\LocationController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\UserCustomerController;
    use App\Http\Controllers\AccountTypeController;
    // use App\Http\Controllers\CustomLoginController;

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

    // Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
        Route::controller(AuthController::class)->group(function(){
            Route::post('register','register');
            Route::post('login','login');
            Route::get('usetdetail','userDetails');
            Route::get('refresh','refresh');
        });
    // });

    Route::controller(AccountController::class)->group(function () {
        Route::get('/accounts', 'index');
        Route::get('accounts/{id}', 'show');
        Route::post('/accounts', 'store');
        Route::put('accounts/{id}', 'update');
        Route::delete('accounts/{id}', 'destroy');
    });

    // account types
    Route::controller(AccountTypeController::class)->group(function () {
        Route::get('/account_types', 'index');
        Route::get('account_types/{id}', 'show');
        Route::post('/account_types', 'store');
        Route::put('account_types/{id}', 'update');
        Route::delete('account_types/{id}', 'destroy');
    });

    // branches
    // Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
        Route::controller(BranchController::class)->group(function () {
            Route::get('/branches', 'index');
            Route::get('branches/{id}', 'show');
            Route::post('/branches', 'store');
            Route::put('branches/{id}', 'update');
            Route::delete('branches/{id}', 'destroy');
        });
    // });

    // locations
    Route::controller(LocationController::class)->group(function () {
        Route::get('/locations', 'index');
        Route::get('/regions', 'regions');
        Route::get('/districts', 'districts');
        Route::get('/wards', 'wards');
    });

    // employees
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
