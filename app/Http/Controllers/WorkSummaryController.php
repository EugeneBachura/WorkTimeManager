<?php

namespace App\Http\Controllers;

use App\Models\WorkTime;
use Illuminate\Http\Request;
use DateTime;

/**
 * Class WorkSummaryController
 *
 * Controller responsible for generating work time summaries for employees.
 */
class WorkSummaryController extends Controller
{
    /**
     * Get work time summary for a given day or month.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getSummary(Request $request)
    {
        // Validate and transform the date
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Check if date is in the format 'dd.mm.YYYY' or 'mm.YYYY'
                    if (!preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $value) &&
                        !preg_match('/^\d{2}\.\d{4}$/', $value)) {
                        $fail('The ' . $attribute . ' must be in format dd.mm.YYYY for day or mm.YYYY for month.');
                    }

                    // Try to parse the date to ensure it's valid
                    try {
                        $format = strlen($value) === 7 ? 'm.Y' : 'd.m.Y';
                        $parsedDate = DateTime::createFromFormat($format, $value);
                        
                        // If parsing fails, the date doesn't exist (e.g., 31.02.1970)
                        if (!$parsedDate || $parsedDate->format($format) !== $value) {
                            $fail('The ' . $attribute . ' is not a valid date.');
                        }
                    } catch (\Exception $e) {
                        $fail('The ' . $attribute . ' is not a valid date.');
                    }
                },
            ],
        ]);

        // Check if the date is for a month (mm.YYYY) or a specific day (dd.mm.YYYY)
        $isMonth = strlen($validatedData['date']) === 7;

        // Create a DateTime object from the input date
        $format = $isMonth ? 'm.Y' : 'd.m.Y';
        $date = DateTime::createFromFormat($format, $validatedData['date']);

        $query = WorkTime::where('employee_id', $validatedData['employee_id']);

        if ($isMonth) {
            // Extract month and year for monthly summary
            $month = $date->format('m');
            $year = $date->format('Y');

            // Summary for a month
            $query->whereMonth('work_day', $month)
                  ->whereYear('work_day', $year);
        } else {
            // Full date for daily summary
            $query->whereDate('work_day', $date->format('Y-m-d'));
        }

        // Fetch work times
        $workTimes = $query->get();

        // Calculate total hours worked
        $totalHours = 0;
        foreach ($workTimes as $workTime) {
            $hoursWorked = $workTime->end_time->diffInMinutes($workTime->start_time) / 60;
            $totalHours += round($hoursWorked * 2) / 2; // Round to 0.5 (30 minutes)
        }

        $hourlyRate = config('worktime.hourly_rate');
        $overtimeRate = config('worktime.overtime_rate');

        if ($isMonth) {
            // Monthly norm for hours
            $monthlyNorm = config('worktime.work_hours_monthly');

            // Calculate normal and overtime hours
            $normalHours = min($totalHours, $monthlyNorm); // Normal hours
            $overtimeHours = max(0, $totalHours - $monthlyNorm); // Overtime hours

            // Calculate total wages
            $normalWages = $normalHours * $hourlyRate; // Wages for normal hours
            $overtimeWages = $overtimeHours * $overtimeRate; // Wages for overtime hours
            $totalWages = $normalWages + $overtimeWages; // Total wages

            return response()->json([
                'response' => [
                    'ilość normalnych godzin z danego miesiąca' => $normalHours,
                    'stawka' => $hourlyRate . ' PLN',
                    'ilość nadgodzin z danego miesiąca' => $overtimeHours,
                    'stawka nadgodzinowa' => $overtimeRate . ' PLN',
                    'suma po przeliczeniu' => $totalWages . ' PLN',
                ]
            ], 200);
        } else {
            // Calculate wages for the day
            $totalWages = $totalHours * $hourlyRate;

            return response()->json([
                'response' => [
                    'suma po przeliczeniu' => $totalWages . ' PLN',
                    'ilość godzin z danego dnia' => $totalHours,
                    'stawka' => $hourlyRate . ' PLN',
                ]
            ], 200);
        }
    }
}
