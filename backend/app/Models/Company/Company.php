<?php

namespace App\Models\Company;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasSlug;
    
    protected $fillable = ['name', 'slug'];
}
