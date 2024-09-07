<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'address',               // Địa chỉ
        'website',               // Địa chỉ Website
        'email',
        'phone',
        'parent_id',
        'creator',
        'organization_type_id',
        'isDelete'
    ];
    public function taskTargets()
    {
        return $this->hasMany(TaskTarget::class);
    }
    public function organizationType()
    {
        return $this->belongsTo(OrganizationType::class, 'organization_type_id');
    }
    public function taskDocument()
    {
        return $this->hasOne(TaskDocument::class, 'organization_id');
    }
    
    public function children()
    {
        return $this->hasMany(Organization::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Organization::class, 'parent_id');
    }

    public function isAncestor(Organization $organizationParent, Organization $organizationChild)
    {
        $currentOrganization = $organizationChild->parent;

        while ($currentOrganization) {
            if ($currentOrganization->id === $organizationParent->id) {
                return true;
            }
            $currentOrganization = $currentOrganization->parent;
        }

        return false;
    }
    
    public function users()
    {
        return $this->hasMany(User::class, 'organization_id');
    }
}
