<?php

namespace App\Models;

use App\Traits\HasSlug;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class Project extends Model
{
    use Multitenantable, HasSlug;

    protected $fillable = ['name', 'description', 'company_id', 'slug', 'status'];

    protected static function booted()
    {
        /**
         * Event: Project Created
         * Logic: Increment the Redis atomic counter
         */
        static::created(function ($project) {
            Redis::incr("company:{$project->company_id}:project_count");
        });

        /**
         * Event: Project Deleted
         * Logic: Decrement the Redis atomic counter
         */
        static::deleted(function ($project) {
            Redis::decr("company:{$project->company_id}:project_count");
        });
    }
}
