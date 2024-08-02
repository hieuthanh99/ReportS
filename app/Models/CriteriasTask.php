<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CriteriasTask extends Model
{
    use HasFactory;

    protected $table = 'criterias_task';

    protected $fillable = [
        'TaskID',
        'CriteriaID',
        'CriteriaCode',
        'CriteriaName',
        'CreatedBy',
        'UpdatedBy',
        'DocumentID',
        'TaskCode',
        'RequestResult',
        'progress',               // Added field
        'progress_evaluation',    // Added field
    ];

    // Trong model Criteria
    public function taskDocument()
    {
        return $this->belongsTo(TaskDocument::class);
    }
}
