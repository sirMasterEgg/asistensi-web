<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'type',
        'week',
        'is_closed'
    ];


    public function students()
    {
        return $this->belongsToMany(Student::class, 'students_tasks')->withTimestamps();
        // return $this->belongsToMany(Student::class, 'students_tasks', 'student_nrp', 'task_id');
    }
}
