<?php

namespace App\Http\Controllers;

use App\Models\WorkTime;
use Illuminate\Http\Request;

/**
 * Manages work time registrations for employees.
 */
class WorkTimeController extends Controller
{
    /**
     * Registers work time for an employee.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'work_day' => 'required|date',
        ]);

        $existingWorkTime = WorkTime::where('employee_id', $validatedData['employee_id'])
            ->where('work_day', $validatedData['work_day'])
            ->first();

        if ($existingWorkTime) {
            return response()->json([
                'error' => 'Czas pracy dla tego dnia już istnieje.',
            ], 422);
        }

        $startTime = new \DateTime($validatedData['start_time']);
        $endTime = new \DateTime($validatedData['end_time']);
        $duration = $startTime->diff($endTime);

        if ($duration->h > 12 || ($duration->h == 12 && $duration->i > 0)) {
            return response()->json([
                'error' => 'Czas pracy nie może przekraczać 12 godzin.',
            ], 422);
        }

        $workTime = WorkTime::create($validatedData);

        return response()->json([
            'response' => [
                'Czas pracy został dodany!',
            ]
        ], 201);
    }
}
