<?php

namespace App\Http\Controllers;

use App\Models\WorkTime;
use Illuminate\Http\Request;
use DateTime;

/**
 * Generates work time summaries for employees.
 */
class WorkSummaryController extends Controller
{
    /**
     * Returns work time summary for a given day or month.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getSummary(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (
                        !preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $value) &&
                        !preg_match('/^\d{2}\.\d{4}$/', $value)
                    ) {
                        $fail('Pole ' . $attribute . ' musi być w formacie dd.mm.YYYY dla dnia lub mm.YYYY dla miesiąca.');
                    }

                    try {
                        $format = strlen($value) === 7 ? 'm.Y' : 'd.m.Y';
                        $parsedDate = DateTime::createFromFormat($format, $value);
                        if (!$parsedDate || $parsedDate->format($format) !== $value) {
                            $fail('Pole ' . $attribute . ' zawiera nieprawidłową datę.');
                        }
                    } catch (\Exception $e) {
                        $fail('Pole ' . $attribute . ' zawiera nieprawidłową datę.');
                    }
                },
            ],
        ]);

        $isMonth = strlen($validatedData['date']) === 7;
        $format = $isMonth ? 'm.Y' : 'd.m.Y';
        $date = DateTime::createFromFormat($format, $validatedData['date']);

        $query = WorkTime::where('employee_id', $validatedData['employee_id']);

        if ($isMonth) {
            $month = $date->format('m');
            $year = $date->format('Y');
            $query->whereMonth('work_day', $month)
                ->whereYear('work_day', $year);
        } else {
            $query->whereDate('work_day', $date->format('Y-m-d'));
        }

        $workTimes = $query->get();
        $totalHours = 0;

        foreach ($workTimes as $workTime) {
            $hoursWorked = $workTime->end_time->diffInMinutes($workTime->start_time) / 60;
            $totalHours += round($hoursWorked * 2) / 2;
        }

        $hourlyRate = config('worktime.hourly_rate');
        $overtimeRate = config('worktime.overtime_rate');

        if ($isMonth) {
            $monthlyNorm = config('worktime.work_hours_monthly');
            $normalHours = min($totalHours, $monthlyNorm);
            $overtimeHours = max(0, $totalHours - $monthlyNorm);
            $normalWages = $normalHours * $hourlyRate;
            $overtimeWages = $overtimeHours * $overtimeRate;
            $totalWages = $normalWages + $overtimeWages;

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
