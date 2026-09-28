<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::get('cron', 'CronController@cron')->name('cron');

Route::controller('CronController')->prefix('cron')->name('cron.')->group(function () {
    Route::get('fiat-rate', 'fiatRate')->name('fiat.rate');
    Route::get('all', 'all')->name('all');
});

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{id}', 'replyTicket')->name('reply');
    Route::post('close/{id}', 'closeTicket')->name('close');
    Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
});

Route::controller('User\ExchangeController')->prefix('exchange')->name('exchange.')->group(function () {
    Route::post('/', 'exchange')->name('start');
    Route::post('get/rate', 'getExchangeRate')->name('get.alert');
    Route::get('best/rates', 'bestRates')->name('best.rates');
});

Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');

Route::controller('SiteController')->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');
    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');
    Route::get('blog', 'blog')->name('blog');
    Route::get('blog/{slug}', 'blogDetails')->name('blog.details');
    Route::get('faq', 'faq')->name('faq');
    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');
    Route::post('subscribe', 'subscribe')->name('subscribe');
    Route::get('exchange/tracking', 'trackExchange')->name('exchange.tracking');
    Route::get('download/exchange/pdf/{hash}/{id}', 'downloadPdf')->name('download.exchange.pdf');

    Route::get('placeholder-image/{size}', 'placeholderImage')->withoutMiddleware('maintenance')->name('placeholder.image');
    Route::get('maintenance-mode', 'maintenance')->withoutMiddleware('maintenance')->name('maintenance');
    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});
