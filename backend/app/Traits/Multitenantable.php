<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait Multitenantable
{

    /**
     * The "boot" method of the trait.
     * Laravel automatically calls this when the Model starts.
     */
    public static function bootMultitenantable()
    {
        static::addGlobalScope('company_id', function (Builder $builder) {
            // This line is added to EVERY 'select' query automatically
            if (Auth::check()) {
                $builder->where('company_id', Auth::user()->current_company_id);
            }
        });

        // Automatic Assignment (Inserts)
        // When you save a Task, Laravel will now automatically set the company_id
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->company_id = Auth::user()->current_company_id;
            }
        });
    }
}
