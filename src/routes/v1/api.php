<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    Auth\LogoutController,
    UserController,
    Auth\LoginController,
    Auth\ResetPasswordController
};

use App\Http\Controllers\{
    ChildrenController,
    DonorController,
    DonorMediaController,
    PackageController,
    ProductsController,
    RevelationController,
    ValidateImageController,
    ZipcodeController};

use Illuminate\Support\Facades\Mail;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function() {
    return response()->json(['message' => 'API is ON!']);
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LogoutController::class, 'logout']);
    Route::post('forgot-password', [ResetPasswordController::class, 'forgotPassword']);
    Route::post('reset-password', [ResetPasswordController::class, 'resetPassword']);
});

Route::group(['prefix' => 'checkout'], function () {
    Route::post('/store', [DonorController::class, 'store']);
    Route::post('/upload-photo/{donor}', [DonorMediaController::class, 'store']);
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('user', UserController::class);
    Route::resource('donor', DonorController::class);

    Route::group(['prefix' => 'donors'], function () {
        Route::get('/profile/appointments/{donor}', [DonorController::class, 'showProfileWithAppointments']);
        Route::get('/list', [DonorController::class, 'listDonorsByLastDonation']);
        Route::get('/list-photos', [DonorMediaController::class, 'index']);
        Route::put('/validate-image', [ValidateImageController::class, 'validateImage']);
        Route::get('/download-images', [DonorMediaController::class, 'generateZipWithDonorImages']);
        Route::patch('/remove/from-event/{event}', [DonorController::class, 'removeDonorFromPackage']);
    });

    Route::group(['prefix' => 'revelations'], function () {
        Route::get('/child/{child}', [RevelationController::class, 'showChildren']);
        Route::get('/send-email/donor/{donor}/child/{child}', [RevelationController::class, 'sendRevelationMail']);
    });

    Route::group(['prefix' => 'children'], function () {
        Route::get('/download-images', [ChildrenController::class, 'generateZipWithChildrenImages']);
//        Route::get('/', [ChildrenController::class, 'getDataFromSimma']);
    });

    Route::resource('package', PackageController::class);
    Route::resource('revelation', RevelationController::class);
});

Route::get('/search-zipcode', [ZipcodeController::class, 'search']);

Route::post('payment/{donor}', [\App\Http\Controllers\PaymentController::class, 'create']);

// WEBHOOK
Route::post('tracking-email-sns', [\App\Http\Controllers\SNSController::class, 'snsNotifications']);

Route::group(['prefix' => 'children'], function () {
    Route::get('/{project_id}/{child_id}', [ChildrenController::class, 'getDataFromSimma']);
});

Route::group(['prefix' => 'products'], function () {
    Route::get('/', [ProductsController::class, 'index']);
});

Route::get('/show-mural-children/{token}', [RevelationController::class, 'showChildrenMuralRevelation']);

Route::put('/change/donor-image/{donor}/{token}', [DonorMediaController::class, 'changeDonorPhoto']);

Route::get('/get-revelations', [RevelationController::class, 'getRevelationsOccurred']);

