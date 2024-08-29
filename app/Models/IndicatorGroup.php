<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicatorGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'creator_id',
        'isDelete'
    ];

    // Quan hệ với bảng 'users' (người tạo)
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    // Quan hệ với bảng 'indicators' (nếu có)
    public function indicators()
    {
        return $this->hasMany(Indicator::class);
    }
}
