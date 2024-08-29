<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationType extends Model
{
    protected $table = 'organization_types';
    protected $fillable = ['type_name', 'code', 'description', 'isDelete'];

    public function organizations()
    {
        return $this->hasMany(Organization::class, 'organization_type_id');
    }
}