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
    use App\Http\Controllers\CustomerBalanceController;
    use App\Http\Controllers\BankBalanceController;
    use App\Http\Controllers\TransactionController;
    use App\Http\Controllers\LoanTransactionController;
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
        Route::get('/totalEmployees', 'totalEmployees');
    });


    // user customers
    Route::controller(UserCustomerController::class)->group(function () {
        Route::get('/customers', 'index');
        Route::get('customers/{id}', 'show');
        Route::post('/customers', 'store');
        Route::put('customers/{id}', 'update');
        Route::delete('customers/{id}', 'destroy');
        Route::get('/totalCustomers', 'totalCustomers');
    });

    // balance
    Route::controller(CustomerBalanceController::class)->group(function () {
        Route::get('/actual_balance', 'index');
        Route::get('actual_balance/{id}', 'show');
        Route::post('/actual_balance', 'store');
        Route::put('actual_balance/{id}', 'update');
        // Route::delete('actual_balance/{id}', 'destroy'); // you cnt delete balance
        Route::get('show_accounts/{id}', 'show_accounts');
    });

    // bankBalance
    Route::controller(BankBalanceController::class)->group(function () {
        Route::get('/bankBalance', 'index');
        Route::get('bankBalance/{id}', 'show');
        // Route::post('bankBalance', 'storebankBalance');
        Route::put('bankBalance/{id}', 'update');
        Route::get('/total_balance', 'total_balance');
    });
    Route::post('bankBalance', [BankBalanceController::class, 'storebankBalance']);

    // customer transactions
    Route::post('customers/{id}/deposit', [TransactionController::class, 'deposit']);
    Route::post('customers/{id}/withdraw', [TransactionController::class, 'withdraw']);
    Route::get('customers/{id}/transactions', [TransactionController::class, 'getTransactions']);
    Route::get('customersTtransactions', [TransactionController::class, 'index']);

    // loans transactions
    Route::controller(LoanTransactionController::class)->group(function () {
        Route::get('/loans', 'index');
        Route::get('loans/{id}', 'show');
        Route::post('loans', 'storeLoan');
        Route::post('/loans', 'updateLoan');
    });
