<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'staff',
        'last_name',
        'middle_name',
        'employment_status',
        'designation',
        'unit',
        'ms_phd',
        'systemrole',
        'fulltime_partime',
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

    // 🚀 Ensure Role Syncing on User Creation & Update
    protected static function booted()
    {
        static::saving(function ($user) {
            $user->syncRoleFromSystemRole();
        });
    }

    private function syncRoleFromSystemRole()
    {
        if ($this->systemrole === 'admin') {
            $this->syncRoles(['admin']);
        } elseif ($this->systemrole === 'super-admin') {
            $this->syncRoles(['super-admin']);
        } else {
            $this->syncRoles([$this->systemrole]); // Assign other roles dynamically
        }
    }
}
