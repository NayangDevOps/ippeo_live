<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'script_text', 'button_text', 'button_link',
        'image', 'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
