<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'creator_id','isDelete'
    ];

    // Định nghĩa quan hệ với User
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    // Nếu cần, định nghĩa quan hệ với Task (giả sử có bảng tasks)
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
