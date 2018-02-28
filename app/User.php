<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    const ROLE_ADMIN = 2;
    const ROLE_DEFAULT = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $visible = [
        'id', 'name', 'email', 'avatar_url', 'role_id'
    ];

    public function isAdmin(): bool {
        return $this->role_id == self::ROLE_ADMIN;
    }

    public function isDefault(): bool {
        return $this->role_id == self::ROLE_DEFAULT;
    }
}
