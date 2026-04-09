<?php

namespace App\Models;

use App\Traits\HasSlug;
use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use Multitenantable, HasSlug;

    protected $fillable = ['name', 'description', 'company_id', 'slug', 'status'];
}
