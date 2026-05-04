<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, HasUuid, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'social_id',
        'social_type',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const ROLE_ADMIN = 'ADMIN';
    const ROLE_SUBSCRIPTION_MANAGER = 'SUBSCRIPTION_MANAGER';
    const ROLE_COURSE_CREATOR = 'COURSE_CREATOR';
    const ROLE_CONTENT_CREATOR = 'CONTENT_CREATOR';
    const ROLE_USER_ADMIN = 'USER_ADMIN';
    const ROLE_USER_EMPLOYEE = 'USER_EMPLOYEE';

    const ROLE_LIST = [
        self::ROLE_SUBSCRIPTION_MANAGER,
        self::ROLE_COURSE_CREATOR,
        self::ROLE_CONTENT_CREATOR,
        self::ROLE_USER_ADMIN,
    ];

    const SUBSCRIPTION_STATUS_ACTIVE = 'ACTIVE';
    const SUBSCRIPTION_STATUS_NONE = 'NONE';
    const SUBSCRIPTION_STATUS_INACTIVE = 'INACTIVE';

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';

    public static function roleString($role): string
    {
        return ucwords(strtolower(str_replace('_', ' ', $role)));
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getSingleRoleNameAttribute()
    {
        return implode(', ', $this->getRoleNames()->toArray());
    }

    public function getRoleBadgeAttribute()
    {
        $roleName = implode(', ', $this->getRoleNames()->toArray());
        $roleString = ucwords(strtolower(str_replace('_', ' ', implode(', ', $this->getRoleNames()->toArray()))));
        $badgeClasses = [
            self::ROLE_ADMIN => 'bg-primary',
            self::ROLE_SUBSCRIPTION_MANAGER => 'bg-danger',
            self::ROLE_COURSE_CREATOR => 'bg-warning',
            self::ROLE_CONTENT_CREATOR => 'bg-success',
            self::ROLE_USER_ADMIN => 'bg-primary',
            self::ROLE_USER_EMPLOYEE => 'bg-info'
        ];

        $badgeClass = $badgeClasses[$roleName] ?? 'bg-secondary';
        return '<span class="badge ' . $badgeClass . ' rounded-pill f-12">' . $roleString . '</span>';
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function quizes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function courseEnrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function getProgressPercentageAttribute()
    {
        $done = $this->courseEnrollments()->where('status', \App\Models\CourseEnrollment::STATUS_COMPLETED)->count();
        $total = $this->courseEnrollments()->count();

        return $total === 0 ? 0 : number_format(($done / $total * 100), 2, '.', '');
    }

    protected static function booted()
    {
        static::deleting(function (User $user) {
            if ($user->isForceDeleting()) {
                DB::table('model_has_roles')
                    ->where('model_id', $user->getKey())
                    ->where('model_type', static::class)
                    ->delete();
                DB::table('model_has_permissions')
                    ->where('model_id', $user->getKey())
                    ->where('model_type', static::class)
                    ->delete();
            }
        });
    }
}
