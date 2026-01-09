<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Services\EnumTranslationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'profile_path',
        'role',
        'active',
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
            'role' => UserRole::class,
        ];
    }

    public function getRoleLabelAttribute(): string
    {
        return $this->role ? EnumTranslationService::translate(UserRole::from($this->role->value)) : '';
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function isInactive(): bool
    {
        return !$this->active;
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }

    public function deactivate(): bool
    {
        return $this->update(['active' => false]);
    }

    public function activate(): bool
    {
        return $this->update(['active' => true]);
    }

    public function bearerToken(): string
    {
        return $this->token()->token ?? session('api_token') ?? '';
    }

    public function routes(): HasMany
    {
        return $this->hasMany(Route::class, 'driver_id');
    }

    public function pickups(): HasMany
    {
        return $this->hasMany(Pickup::class, 'driver_id');
    }

    public function emailLogsSent(): HasMany
    {
        return $this->hasMany(EmailLog::class, 'sent_by_user_id');
    }

    public function printLogs(): HasMany
    {
        return $this->hasMany(PrintLog::class, 'printed_by_user_id');
    }

    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isEmployee(): bool
    {
        return $this->role === UserRole::EMPLOYEE;
    }

    public function isDriver(): bool
    {
        return $this->role === UserRole::DRIVER;
    }

    public function canManage(): bool
    {
        return \in_array($this->role, [UserRole::ADMIN, UserRole::EMPLOYEE]);
    }

    public function canDelete(): bool
    {
        return $this->role === UserRole::ADMIN;
    }
}
