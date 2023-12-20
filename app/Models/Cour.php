<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cour extends Model
{
    use HasFactory;

    function formation(): HasMany
    {
        return $this->hasMany(Formation::class, 'id', 'formation_id');
    }

    function user(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }

    function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'cours_users', 'cours_id', 'user_id');
    }

    function plannings(): HasMany
    {
        return $this->hasMany(Planning::class, 'cours_id', 'id');
    }
}
