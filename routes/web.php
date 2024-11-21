<?php

use App\Http\Controllers\DeviceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('devices.index');
});

Route::get('devices', [DeviceController::class, 'index'])->name('devices.index');
Route::get('devices/getdata', [DeviceController::class, 'getData']);
Route::post('devices/search', [DeviceController::class, 'search']);
Route::post('devices', [DeviceController::class, 'store']);
Route::put('devices/{device}', [DeviceController::class, 'update']);
Route::delete('devices/{device}', [DeviceController::class, 'destroy']);

Route::get('person/{dni}/getbydni', [DeviceController::class, 'getDataPerson']);

