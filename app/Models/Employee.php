<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['name', 'email', 'position', 'phone_number'];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
