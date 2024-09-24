<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Employee;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

     /**
     * Test for creating an employee.
     * 
     * @test
     * @return void
     */
    public function it_can_create_an_employee()
    {
        // Arrange
        $data = [
            'first_name' => 'Eugene',
            'last_name' => 'Bachura',
        ];

        // Act
        $employee = Employee::create($data);

        // Assert
        $this->assertDatabaseHas('employees', [
            'first_name' => 'Eugene',
            'last_name' => 'Bachura'
        ]);
    }
}
