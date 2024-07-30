<?php

// app/Models/Task.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = [
        'task_code',
        'task_name',
        'reporting_cycle',
        'category',
        'required_result',
        'start_date',
        'end_date'
    ];
}
