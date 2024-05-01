<?php
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AccountController;
    use App\Http\Controllers\BranchController;

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

    Route::controller(BranchController::class)->group(function () {
        Route::get('/branches', 'index');
        Route::get('branches/{id}', 'show');
        Route::post('/branches', 'store');
        Route::put('branches/{id}', 'update');
        Route::delete('branches/{id}', 'destroy');
    });
