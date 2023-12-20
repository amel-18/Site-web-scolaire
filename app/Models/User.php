<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $timestamps = false;

    protected $hidden = ['mdp'];
    protected $fillable = ['nom', 'prenom', 'login', 'mdp'];

    protected $attributes = ['type' => 'user',];

    public function getAuthPassword() {
        return $this->mdp;
    }

    public function isAdmin(): bool
    {
        return $this->type == 'admin';
    }

    public function isProf(): bool
    {
        return $this->type == 'enseignant';
    }

    public function isntLocked(): bool
    {
        return $this->type != null;
    }


    function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class, 'id', 'fomation_id');
    }

    function cour(): BelongsTo
    {
        return $this->belongsTo(Cour::class, 'user_id', 'id');
    }

    function cours(): BelongsToMany
    {
        return $this->belongsToMany(Cour::class, 'cours_users', 'user_id', 'cours_id');
    }
}
