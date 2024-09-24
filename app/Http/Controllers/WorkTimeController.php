<?php

namespace App\Http\Controllers;

use App\Models\WorkTime;
use App\Models\Employee;
use Illuminate\Http\Request;

class WorkTimeController extends Controller
{
    /**
     * Register work time for an employee.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'work_day' => 'required|date',
        ]);

        // Check if employee has already logged work time for the same day
        $existingWorkTime = WorkTime::where('employee_id', $validatedData['employee_id'])
            ->where('work_day', $validatedData['work_day'])
            ->first();

        if ($existingWorkTime) {
            return response()->json([
                'error' => 'Work time already exists for this day',
            ], 422);
        }

        // Check if the work duration exceeds 12 hours
        $startTime = new \DateTime($validatedData['start_time']);
        $endTime = new \DateTime($validatedData['end_time']);
        $duration = $startTime->diff($endTime);

        if ($duration->h > 12 || ($duration->h == 12 && $duration->i > 0)) {
            return response()->json([
                'error' => 'Work time cannot exceed 12 hours',
            ], 422);
        }

        // Create work time entry
        $workTime = WorkTime::create($validatedData);

        return response()->json([
            'response' => [
                'Czas pracy został dodany!'
            ]
        ], 201);
    }
}

