<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{

    /**
     * Boot the trait and hook into the model's creating event.
     */
    protected static function bootHasSlug()
    {
        static::creating(function ($model) {
            $model->slug = $model->generateUniqueSlug($model->getSlugSource());
        });
    }

    /**
     * Define which field to slug (can be overridden in the Model).
     */
    public function getSlugSource(): string
    {
        return $this->title ?? $this->name;
    }

    /**
     * Logic to ensure the slug is unique.
     */
    private function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        // Check if the slug exists in the current table
        while (static::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-" . $count++;
        }

        return $slug;
    }
}
