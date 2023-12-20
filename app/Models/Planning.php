<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Planning extends Model
{
    use HasFactory;
    public $timestamps = false;

    function cour(): BelongsTo
    {
        return $this->belongsTo(Cour::class, 'cours_id', 'id');
    }
}
