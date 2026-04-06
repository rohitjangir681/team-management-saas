<?php

namespace App\Models;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use Multitenantable;

    protected $fillable = ['name', 'description', 'company_id'];
}
