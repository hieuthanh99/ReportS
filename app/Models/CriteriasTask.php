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
        'RequestResult'
    ];
}
