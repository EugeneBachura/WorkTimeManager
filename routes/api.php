<?php

use App\Http\Controllers\EmployeeController;

Route::post('/employees', [EmployeeController::class, 'store']);