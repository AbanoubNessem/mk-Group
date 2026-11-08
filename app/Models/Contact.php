<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'project_type',
        'message',
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}

