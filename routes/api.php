<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\WorkTimeController;

Route::post('/employees', [EmployeeController::class, 'store']);
Route::post('/work-times', [WorkTimeController::class, 'store']);