<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Modifier extends Model
{
    protected $fillable = ['menu_id', 'name', 'extra_price'];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
