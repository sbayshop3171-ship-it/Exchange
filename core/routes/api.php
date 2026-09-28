<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AppController;

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

Route::namespace('Api')->name('api.')->group(function () {
    Route::controller(\App\Http\Controllers\Api\AppController::class)->group(function () {
        Route::get('general-setting', 'generalSetting');
        Route::get('get-countries', 'getCountries');
        Route::get('language/{key?}','getLanguage');
        Route::get('policies', 'policies');
        Route::get('policy/{slug}', 'policyContent');
        Route::get('faq', 'faq');
        Route::get('seo', 'seo');
        Route::get('get-extension/{act}','getExtension');
        Route::post('contact', 'submitContact');
        Route::get('cookie', [AppController::class, 'cookie']);
        Route::post('cookie/accept', 'cookieAccept');
        Route::get('custom-pages', 'customPages');
        Route::get('custom-page/{slug}', 'customPageData');
        Route::get('sections', 'allSections');
        Route::get('ticket/{ticket}', 'viewTicket');
        Route::post('ticket/ticket-reply/{id}', 'replyTicket');
        Route::get('rate/currencies', 'reteCurrency');
        Route::post('rate/currency/alert/request', 'retAlertCurrencyRequest');
        Route::post('best/currency/rate', 'bestCurrencyRate');
    });

    Route::namespace('Auth')->group(function () {
        Route::controller(\App\Http\Controllers\Api\Auth\LoginController::class)->group(function () {
            Route::post('login', 'login');
            Route::post('check-token', 'checkToken');
            Route::post('social-login', 'socialLogin');
        });
        Route::post('register', 'RegisterController@register');

        Route::controller(\App\Http\Controllers\Api\Auth\ForgotPasswordController::class)->group(function () {
            Route::post('password/email', 'sendResetCodeEmail');
            Route::post('password/verify-code', 'verifyCode');
            Route::post('password/reset', 'reset');
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('user-data-submit', 'UserController@userDataSubmit');

        //authorization
        Route::middleware('registration.complete')->controller('AuthorizationController')->group(function () {
            Route::get('authorization', 'authorization');
            Route::get('resend-verify/{type}', 'sendVerifyCode');
            Route::post('verify-email', 'emailVerification');
            Route::post('verify-mobile', 'mobileVerification');
            Route::post('verify-g2fa', 'g2faVerification');
        });

        Route::middleware(['check.status'])->group(function () {
            Route::middleware('registration.complete')->group(function () {
                Route::controller(\App\Http\Controllers\Api\UserController::class)->group(function () {
                    Route::get('dashboard', 'dashboard');
                    Route::post('profile-setting', 'submitProfile');
                    Route::post('change-password', 'submitPassword');

                    Route::get('user-info', 'userInfo');
                    //KYC
                    Route::get('kyc-form', 'kycForm');
                    Route::post('kyc-submit', 'kycSubmit');

                    //Report
                    Route::get('affiliate', 'affiliate');

                    Route::get('transactions','transactions');

                    Route::post('add-device-token', 'addDeviceToken');
                    Route::get('push-notifications', 'pushNotifications');
                    Route::post('push-notifications/read/{id}', 'pushNotificationsRead');

                    //2FA
                    Route::get('twofactor', 'show2faForm');
                    Route::post('twofactor/enable', 'create2fa');
                    Route::post('twofactor/disable', 'disable2fa');

                    Route::post('delete-account', 'deleteAccount');
                });

                // Withdraw
                Route::controller(\App\Http\Controllers\Api\WithdrawController::class)->group(function () {
                    Route::middleware('kyc')->group(function () {
                        Route::get('withdraw/currency', 'withdrawMethod');
                        Route::post('withdraw/save', 'withdrawStore');
                    });
                    Route::get('withdraw/history', 'withdrawLog');
                });

                Route::controller(\App\Http\Controllers\Api\TicketController::class)->prefix('ticket')->group(function () {
                    Route::get('/', 'supportTicket');
                    Route::post('create', 'storeSupportTicket');
                    Route::get('view/{ticket}', 'viewTicket');
                    Route::post('reply/{id}', 'replyTicket');
                    Route::post('close/{id}', 'closeTicket');
                    Route::get('download/{attachment_id}', 'ticketDownload');
                });

                Route::controller(\App\Http\Controllers\Api\CurrencyController::class)->prefix('currency/list')->group(function () {
                    Route::get('/', 'list');
                    Route::get('sell', 'sell');
                    Route::get('/buy', 'buy');
                });

                Route::controller(\App\Http\Controllers\Api\ExchangeController::class)->prefix('exchange')->group(function () {
                    Route::post('/create', 'create');
                    Route::post('/track', 'track');
                    Route::get('/preview/{id}', 'preview');
                    Route::post('/confirm/{id}', 'confirm');
                    Route::get('/manual/confirm/{id}', 'manual');
                    Route::post('/manual/confirm/{id}', 'manualConfirm');
                    Route::get('/details/{id}', 'details');
                    Route::get('/list/{scope?}', 'list')->name('exchange.list');
                    Route::get('/', 'all');
                });
            });
        });

        Route::get('logout', 'Auth\LoginController@logout');
    });
});
