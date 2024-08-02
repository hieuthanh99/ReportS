<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'type', 'email', 'phone', 'parent_id', 'creator'];

    public function children()
    {
        return $this->hasMany(Organization::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Organization::class, 'parent_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'organization_id');
    }
}
