<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    protected $fillable = [
        'section_key',
        'title',
        'title_ar',
        'content',
        'content_ar',
        'subtitle',
        'subtitle_ar',
        'images',
        'background_image',
        'button_text',
        'button_text_ar',
        'button_link',
        'is_active',
        'sort_order',
        'extra_data',
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'extra_data' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }
}
