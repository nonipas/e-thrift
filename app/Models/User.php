<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role_id',
        'status',

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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    //check if user has permission to access a route
    public function hasPermission($permission)
    {
        if ($this->role_id == 0) {
            return true;
        }

        $role = Role::find($this->role_id);
        //if not found, return false
        if (!$role) {
            return false;
        }
        $permissions = explode(',', $role->permissions);

        if (in_array($permission, $permissions)) {
            return true;
        } else {
            return false;
        }
    }

    //check if user has permission to access a route
    public function hasRole($role)
    {
        //get role by slug if role is not an integer
        if (!is_int($role)) {
            $role = Role::where('slug', $role)->first()->id;
        }

        if ($this->role_id == $role) {
            return true;
        } else {
            return false;
        }
    }

}
