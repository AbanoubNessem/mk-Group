<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'name_ar',
        'description',
        'description_ar',
        'thumbnail',
        'images',
        'area',
        'rooms_count',
        'project_type',
        'project_type_ar',
        'engineering_function',
        'engineering_function_ar',
        'location',
        'location_ar',
        'category',
        'category_ar',
        'country_id',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'rooms_count' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Scope to get only active projects
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    /**
     * Get the country that owns the project
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Scope to filter by country
     */
    public function scopeByCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }
}
