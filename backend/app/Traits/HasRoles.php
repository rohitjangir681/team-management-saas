<?php

namespace App\Traits;

use App\Models\Role;
use Illuminate\Support\Facades\Cache;

trait HasRoles
{

    public function getCurrentRole()
    {
        $cacheKey = $this->getRoleCacheKey();

        return Cache::remember($cacheKey, now()->addDay(), function () {
            $role = $this->companies()->where('company_id', $this->current_company_id)
                ->first();

            if ($role && $role->pivot->role_id) {
                return Role::find($role->pivot->role_id)->slug;
            }
            return null;
        });
    }

    private function getRoleCacheKey()
    {
        return "user_{$this->id}_company_{$this->current_company_id}_role";
    }

    // The "Reset Button"
    public function forgetCachedRole()
    {
        Cache::forget($this->getRoleCacheKey());
    }

    public function hasRole($roleSlug)
    {
        return $this->getCurrentRole() === $roleSlug;
    }
}
