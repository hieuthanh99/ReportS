<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskResult extends Model
{
    use HasFactory;
    protected $table = 'task_result';
    protected $fillable = [
        'tasks_document_id',
        'document_id',
        'result',
        'description',
        'number_type',
        'type',
        'type_save'
    ];

    public function tasksDocument()
    {
        return $this->belongsTo(TasksDocument::class, 'tasks_document_id');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}
