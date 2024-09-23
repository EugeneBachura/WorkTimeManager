<?php

/**
 * Configuration for work time settings.
 *
 * @return array
 */
return [
    'work_hours_monthly' => env('WORK_HOURS_MONTHLY', 40),
    'hourly_rate' => env('HOURLY_RATE', 20),
    'overtime_rate' => env('OVERTIME_RATE', 40),
];