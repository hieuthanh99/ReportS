<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationTask extends Model
{
    use HasFactory;

    protected $table = 'organization_task';

    protected $fillable = [
        'tasks_document_id',
        'document_id',
        'organization_id',
        'creator',
        'users_id'
    ];
    // Thiết lập quan hệ nếu cần
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}