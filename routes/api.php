<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\WorkTimeController;
use App\Http\Controllers\WorkSummaryController;

Route::post('/employees', [EmployeeController::class, 'store']);
Route::post('/work-times', [WorkTimeController::class, 'store']);

Route::post('/work-summary', [WorkSummaryController::class, 'getSummary']);