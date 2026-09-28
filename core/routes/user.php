<?php

use Illuminate\Support\Facades\Route;

Route::namespace('User\Auth')->name('user.')->middleware('guest')->group(function () {
    Route::controller('LoginController')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('logout', 'logout')->middleware('auth')->withoutMiddleware('guest')->name('logout');
    });

    Route::controller('RegisterController')->group(function () {
        Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('register', 'register');
        Route::post('check-user', 'checkUser')->name('checkUser')->withoutMiddleware('guest');
    });

    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('request');
        Route::post('email', 'sendResetCodeEmail')->name('email');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::post('password/reset', 'reset')->name('password.update');
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    });

    Route::controller('SocialiteController')->group(function () {
        Route::get('social-login/{provider}', 'socialLogin')->name('social.login');
        Route::get('social-login/callback/{provider}', 'callback')->name('social.login.callback');
    });
});

Route::middleware('auth')->name('user.')->group(function () {
    Route::get('user-data', 'User\UserController@userData')->name('data');
    Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');

    //authorization
    Route::middleware('registration.complete')->namespace('User')->controller('AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('2fa.verify');
    });

    Route::middleware(['check.status', 'registration.complete'])->group(function () {
        Route::namespace('User')->group(function () {
            Route::controller('UserController')->group(function () {
                Route::get('dashboard', 'home')->name('home');
                Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');

                //2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                //KYC
                Route::get('kyc-form', 'kycForm')->name('kyc.form');
                Route::get('kyc-data', 'kycData')->name('kyc.data');
                Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                Route::get('attachment-download/{fil_hash}', 'attachmentDownload')->name('attachment.download');
                Route::get('commission/logs', 'commissionLog')->name('report.commission.log');

                //Report
                Route::any('deposit/history', 'depositHistory')->name('deposit.history');
                Route::get('transactions', 'transactions')->name('transactions');
                Route::post('add-device-token', 'addDeviceToken')->name('add.device.token');
            });

            //Profile setting
            Route::controller('ProfileController')->group(function () {
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');
            });

            // Withdraw
            Route::controller('WithdrawController')->prefix('withdraw')->name('withdraw')->group(function () {
                Route::middleware('kyc')->group(function () {
                    Route::get('/', 'withdrawMoney')->name('.index');
                    Route::post('/', 'withdrawStore')->name('.money');
                    Route::get('/currency/user/data/{currencyId}', 'currencyUserData')->name('.currency.user.data');
                });
                Route::get('history', 'withdrawLog')->name('.history');
            });

            //affiliate route
            Route::prefix('affiliate')->name('affiliate.')->controller('AffiliateController')->group(function () {
                Route::get('/', 'affiliate')->name('index');
            });

            //user exchange route
            Route::controller('ExchangeController')->name('exchange.')->prefix('exchange')->group(function () {
                Route::get('preview', 'preview')->name('preview');
                Route::post('confirm', 'confirm')->name('confirm');
                Route::get('manual', 'manual')->name('manual');
                Route::get('complete/{id}', 'complete')->name('complete');
                Route::post('manual', 'manualConfirm');
                Route::get('invoice/{id}/{type}', 'invoice')->name('invoice');
                Route::get('details/{trx}', 'details')->name('details');
                Route::get('{scope?}', 'list')->name('list');
            });

            Route::controller('PaymentPopupController')->prefix('payment-popup')->name('payment.popup.')->group(function () {
                Route::get('gateways', 'gateways')->name('gateways');
                Route::post('unlock', 'storeUnlock')->name('unlock');
            });
        });

        // Payment
        Route::prefix('payment')->name('deposit.')->controller('Gateway\PaymentController')->group(function () {
            Route::get('confirm', 'depositConfirm')->name('confirm');
        });
    });
});
