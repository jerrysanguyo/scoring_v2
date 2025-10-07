<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function getAllUsers()
    {
        return self::select('id', 'first_name', 'middle_name', 'last_name', 'email', 'contact_number')
            ->with('roles:id,name')
            ->get()
            ->map(function ($user) {
                $user->role_name = $user->roles->pluck('name')->first();
                return $user;
            });
    }

    public function score()
    {
        return $this->hasMany(Score::class, 'scored_by');
    }
}
