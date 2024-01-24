<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    
    public function friends(): HasMany
    {
        return $this->hasMany(UserContact::class, 'character_id', 'character_id');
    }

    public function leaderboard(): HasOne
    {
        return $this->hasOne(Leaderboard::class, 'player_hash', 'player_hash');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if($this->can('access admin panel'))
        {
            return true;
        }

        if($this->hasRole('system'))
        {
            return true;
        }

        if($this->hasRole('mod'))
        {
            return true;
        }

        return false;
    }

    public function getNameAttribute()
    {
        return $this->username;
    }
}
