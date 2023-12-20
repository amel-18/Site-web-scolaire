<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Formation extends Model
{
    use HasFactory;

    function users(): HasMany
    {
        return $this->hasMany(User::class, 'formation_id', 'id');
    }

    function cours(): BelongsTo
    {
        return $this->belongsTo(Cour::class, 'formation_id', 'id');
    }


}
