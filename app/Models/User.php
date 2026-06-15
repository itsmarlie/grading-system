<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'first_name', 'middle_name', 'last_name', 'title',
        'username', 'email', 'password', 'role',
        'student_id', 'teacher_id', 'department',
        'phone', 'address', 'birthdate',
        'avatar', 'force_password_change',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at'     => 'datetime',
            'password'              => 'hashed',
            'birthdate'             => 'date',
            'force_password_change' => 'boolean',
        ];
    }

    // ── Relationships ──
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    // ── Display name with title ──
    public function getDisplayNameAttribute(): string
    {
        $parts = array_filter([
            $this->title,
            $this->first_name,
            $this->middle_name ? substr($this->middle_name, 0, 1) . '.' : null,
            $this->last_name,
        ]);
        return implode(' ', $parts) ?: $this->name;
    }

    // ── Full name without title ──
    public function getFullNameAttribute(): string
    {
        $parts = array_filter([$this->first_name, $this->middle_name, $this->last_name]);
        return implode(' ', $parts) ?: $this->name;
    }

    // ── Role helpers ──
    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isTeacher(): bool { return $this->role === 'teacher'; }
    public function isStudent(): bool { return $this->role === 'student'; }
}