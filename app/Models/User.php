<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable
{
    use  HasRoles , HasFactory, Notifiable; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'email',
        ];


    }
    
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            // Check if the user has 'admin' in their role column
            if ($user->systemrole === 'admin') {
                $adminRole = Role::firstOrCreate(['name' => 'admin']); // Ensure role exists
                
                $user->assignRole('admin'); // Assign the admin role
                
                // Give all permissions to admin
                $user->syncPermissions(Permission::all()); 
            }
        });
    }
    public function roles(){
        return $this->belongsToMany(Role::class);
    }

    public function hasPermission(string $permission) : bool
    {
        if($this->hasRole('admin'))
        {
            return true;
        }
        $permissionsArray = [];

        foreach ($this->roles as $role){
            foreach ($role->permissions as $singlePermission){
                $permissionsArray[] = $singlePermission->name;
            }
        }
        return collect($permissionsArray)->unique()->contains($permission);
    }
}
