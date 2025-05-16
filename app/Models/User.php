<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


/**
 * @method bool hasRole(string|array $roles)
 * @method \Illuminate\Support\Collection getRoleNames()
 * @method \Spatie\Permission\Models\Role assignRole(...$roles)
 */


class User extends Authenticatable implements HasAvatar
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
        'avatar_url',
        'research_interests',
        'fields_of_specialization',

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function getFilamentAvatarUrl(): ?string
    {
        $avatarColumn = config('filament-edit-profile.avatar_column', 'avatar_url');
        return $this->$avatarColumn ? Storage::url($this->$avatarColumn) : null;
    }



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
        $validRoles = ['super-admin', 'admin', 'user', 'secretary'];

        // Ensure the systemrole is valid before proceeding
        if (in_array($this->systemrole, $validRoles)) {
            $this->syncRoles([$this->systemrole]); // Spatie syncRoles for single role
        } else {
            Log::error('Invalid systemrole', ['systemrole' => $this->systemrole]);
        }
    }



}
