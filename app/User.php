<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Str;

use App\Episode;

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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Enables us to hook into model event's
     *
     * @return void 
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function($user) {
            $user->{$user->getKeyName()} = (string) Str::uuid();
        });
    }

    /**
     * Tells Eloquent to not increment user's ID
     *
     * @return boolean 
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Tells Eloquent that ID is a string
     *
     * @return string 
     */
    public function getKeyType()
    {
        return 'string';
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }

}
