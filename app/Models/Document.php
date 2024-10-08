<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Document extends Model
{
    protected $fillable = [
        'document_code',
        'document_name',
        'issuing_department',
        'creator',
        'release_date',
        'status',
        'category_id',
        'isDelete'
    ];
    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'category_id');
    }

    public function getFile()
    {
        return File::where('document_id', $this->id)->where('type', 3)->get();
    }
    // Nếu bạn sử dụng timestamps (created_at, updated_at)
    public $timestamps = true;

    public function files()
    {
        return $this->hasMany(File::class)->type(3);;
    }
    // Định nghĩa mối quan hệ với TaskDocument nếu cần
    public function taskTarget()
    {
        return $this->hasMany(TaskTarget::class);
    }


    public function taskResult()
    {
        return $this->hasMany(TaskResult::class);
    }

    public function taskDocuments()
    {
        return $this->hasMany(TaskDocument::class);
    }

    public function getReleaseDateFormattedAttribute()
    {
        return $this->release_date ? Carbon::parse($this->release_date)->format('d/m/Y') : 'N/A';
    }
    public function issuingDepartment()
    {
        return $this->belongsTo(Organization::class, 'issuing_department');
    }
}
