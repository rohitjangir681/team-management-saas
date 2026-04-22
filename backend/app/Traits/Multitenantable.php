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
        if (Auth::check()) {
            static::addGlobalScope('company_id', function (Builder $builder) {
                // This line is added to EVERY 'select' query automatically
                $builder->where('company_id', Auth::user()->current_company_id);
            });
        }
    }
}
