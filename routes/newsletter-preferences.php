<?php

use App\Http\Controllers\NewsletterPreferencesController;
use App\Http\Middleware\LogActivity;
use App\Http\Middleware\PromotionPrivacyHeaders;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], '/newsletter/bestaetigen/{subscription}/{address}', [NewsletterPreferencesController::class, 'confirm'])
    ->withoutMiddleware(LogActivity::class)->middleware([PromotionPrivacyHeaders::class, 'signed:relative', 'throttle:30,1'])
    ->whereNumber('subscription')->whereUuid('address')->name('newsletter.confirm');

Route::withoutMiddleware(LogActivity::class)->middleware([PromotionPrivacyHeaders::class, 'signed', 'throttle:60,1'])->prefix('newsletter')->group(function () {
    Route::match(['get', 'post'], 'einstellungen/{subscription}/{address}', [NewsletterPreferencesController::class, 'handle'])
        ->whereNumber('subscription')->whereUuid('address')->name('newsletter.preferences');
    Route::match(['get', 'post'], 'abmelden/{subscription}/{address}', [NewsletterPreferencesController::class, 'handle'])
        ->whereNumber('subscription')->whereUuid('address')->name('newsletter.unsubscribe');
});
