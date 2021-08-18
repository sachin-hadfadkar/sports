<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{

    use HasApiTokens, Notifiable;

    protected $table = 'users';

    const ROLE_MEMBER = 0;
    const ROLE_ADMIN = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role',
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
     * Create user with role for application
     *
     * @param \Illuminate\Http\Request  $request
     * @return $user
     */
    public static function create($request) {
        $role = (int) $request->role;
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = self::ROLE_MEMBER;
        if($role === 1) {
            $user->role = self::ROLE_ADMIN;
        }
        $user->save();
        return $user;
    }
}
