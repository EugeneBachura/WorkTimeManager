<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WorkTimeTest extends TestCase
{
    use RefreshDatabase;

     /**
     * Test for creating a work time entry for an employee.
     * 
     * @test
     * @return void
     */
    public function it_can_create_work_time_for_an_employee()
    {
        // Arrange
        $employee = Employee::factory()->create();
        $data = [
            'employee_id' => $employee->id,
            'start_time' => now(),
            'end_time' => now()->addHours(8),
            'work_day' => today(),
        ];

        // Act
        $workTime = WorkTime::create($data);

        // Assert
        $this->assertDatabaseHas('work_times', [
            'employee_id' => $employee->id,
            'work_day' => today()->toDateString(),
        ]);
    }
}