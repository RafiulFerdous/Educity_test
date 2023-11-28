<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'course_type'];

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_course');
    }
}
