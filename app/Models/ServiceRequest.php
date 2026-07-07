<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $fillable = ['table_id', 'type', 'label', 'status'];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
