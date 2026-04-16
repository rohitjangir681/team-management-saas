<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use Multitenantable; // Automatically filters by company_id

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'project_id',
        'company_id',
        'user_id'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
