<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

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
    protected $hidden = [

      'password', 'remember_token',

    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'assigned_roles');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function hasRoles(array $roles)
    {
        return $this->roles->pluck('name')->intersect($roles)->count();
    }

    public function isAdmin()
    {
        return $this->hasRoles(['admin']);
    }

    public function note()
    {
        $this->morphOne(Note::class, 'notable');
    }
}
