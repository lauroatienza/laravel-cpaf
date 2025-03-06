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
        'systemrole', // ✅ Ensure systemrole is handled properly
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

    // 🚀 Sync Spatie Roles When User is Created or Updated
    protected static function booted()
    {
        static::saving(function ($user) {
            // Sync Spatie roles based on systemrole field before saving
            $user->syncRoleFromSystemRole();
        });
    }
    
    private function syncRoleFromSystemRole()
    {
        $validRoles = ['super-admin', 'admin', 'user'];
    
        // Ensure the systemrole is valid before proceeding
        if (in_array($this->systemrole, $validRoles)) {
            $this->syncRoles([$this->systemrole]); // Spatie syncRoles for single role
        } else {
            \Log::error('Invalid systemrole', ['systemrole' => $this->systemrole]);
        }
    }
    
    
    
}
