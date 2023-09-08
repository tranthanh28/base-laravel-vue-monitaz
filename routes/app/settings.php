<?php

use App\Http\Controllers\App\PaymentMethod\PaymentMethodController;
use App\Http\Controllers\App\Settings\SmsSettingController;
use App\Http\Controllers\App\Settings\SettingsApiController;
use App\Http\Controllers\App\Settings\ReCaptchaSettingController;
use App\Http\Controllers\App\Settings\StorageSettingController;

//app-settings
Route::get('/app-setting', [SettingsApiController::class, 'settings'])
    ->name('app.settings');

// application settings value get from config
Route::get('general-settings', [SettingsApiController::class, 'index']);

//Sms setting
Route::post('/sms-settings', [SmsSettingController::class, 'update'])
    ->middleware('can:update_sms_settings')
    ->name('settings.update-sms');

Route::get('get-sms-setting-info', [SmsSettingController::class, 'getData']);

//storage configuration
Route::get('storage-configuration', [StorageSettingController::class, 'index']);
Route::post('storage-configuration', [StorageSettingController::class, 'update']);

//reCAPTCHA setting
Route::post('/re-captcha-setting', [ReCaptchaSettingController::class, 'store'])
    ->middleware('can:update_recaptcha_settings')
    ->name('settings.update-recaptcha');

//Payment method
Route::group([], function () {
    Route::get('payment-method-status', [PaymentMethodController::class, 'paymentMethodStatus'])
        ->name('payment_method.status');
    Route::apiResource('payment-method', PaymentMethodController::class);

});