<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = ['first_name', 'last_name'];

    protected static function boot()
    {
        parent::boot();

        // Automatic UUID generation when creating a new employee
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
}
