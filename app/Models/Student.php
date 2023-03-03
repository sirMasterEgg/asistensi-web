<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'nrp',
        'nama',
    ];

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'students_tasks')->withTimestamps();
        // return $this->belongsToMany(Task::class, 'students_tasks', 'task_id', 'student_nrp');
    }
}
