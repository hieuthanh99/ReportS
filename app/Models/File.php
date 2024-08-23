<?php

// app/Models/File.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'document_id',
        'file_name',
        'file_path',
        'type',
        'cycle_type',
        'number_type'
    ];
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
    // Định nghĩa mối quan hệ với Document
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
