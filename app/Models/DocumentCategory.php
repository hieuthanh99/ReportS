<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    protected $fillable = ['code', 'name', 'description'];

    public function documents()
    {
        return $this->hasMany(Document::class, 'category_id');
    }
}
