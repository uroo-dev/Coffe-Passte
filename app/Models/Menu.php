<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = ['category_id', 'name', 'description', 'price', 'image', 'is_available'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function modifiers(): HasMany
    {
        return $this->hasMany(Modifier::class);
    }
}
