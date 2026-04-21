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

    public function scopePriority($query, $level)
    {
        if (!$level) return $query;
        return $query->where('priority', $level);
    }

    public function scopeStatus($query, $status)
    {
        if (!$status) return $query;
        return $query->where('status', $status);
    }

    public function scopeSearch($query, $term)
    {
        if (!$term) return $query;
        return $query->where('title', 'LIKE', "%{$term}%");
    }
}
