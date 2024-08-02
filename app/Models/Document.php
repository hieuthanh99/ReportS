<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Document extends Model
{
    // Các thuộc tính có thể được gán đại trà
    protected $fillable = [
        'document_code',
        'document_name',
        'issuing_department',
        'creator',
        'release_date',
        'status'
    ];

    // Nếu bạn sử dụng timestamps (created_at, updated_at)
    public $timestamps = true;
    public function files()
    {
        return $this->hasMany(File::class);
    }
    // Định nghĩa mối quan hệ với TaskDocument nếu cần
    public function taskDocuments()
    {
        return $this->hasMany(TaskDocument::class);
    }

    public function getReleaseDateFormattedAttribute()
    {
        return $this->release_date ? Carbon::parse($this->release_date)->format('Y-m-d') : 'N/A';
    }
    public function issuingDepartment()
    {
        return $this->belongsTo(Organization::class, 'issuing_department');
    }
}
