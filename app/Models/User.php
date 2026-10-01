<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'department_id',
        'language_preference',
        'xp_points',
        'security_rank',
        'streak_days',
        'last_login_at',
        'dark_mode',
        'avatar_url',
        'job_title',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'dark_mode' => 'boolean',
        'is_active' => 'boolean',
        'xp_points' => 'integer',
        'streak_days' => 'integer',
    ];

    // العلاقات
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function progress()
    {
        return $this->hasMany(UserProgress::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
                    ->withPivot('earned_at', 'shared_linkedin')
                    ->withTimestamps();
    }

    public function completedScenarios()
    {
        return $this->belongsToMany(Scenario::class, 'user_progress')
                    ->wherePivot('status', 'completed')
                    ->withPivot('score', 'completed_at');
    }

    public function conversations()
    {
        return $this->hasMany(AiConversation::class);
    }

    public function adaptiveLearningPath()
    {
        return $this->hasOne(AdaptiveLearningPath::class);
    }

    // الدوال المساعدة
    public function getSecurityLevelAttribute(): string
    {
        $levels = [
            0 => 'Security Novice',
            100 => 'Cyber Aware',
            250 => 'Data Defender',
            500 => 'Security Expert',
            1000 => 'Cyber Guardian',
        ];

        $currentLevel = 'Security Novice';
        foreach ($levels as $xp => $level) {
            if ($this->xp_points >= $xp) {
                $currentLevel = $level;
            }
        }

        return $currentLevel;
    }

    public function addPoints(int $points): void
    {
        $this->increment('xp_points', $points);
        $this->updateSecurityRank();
    }

    public function updateSecurityRank(): void
    {
        $this->update(['security_rank' => $this->getSecurityLevelAttribute()]);
    }

    public function getProgressPercentageAttribute(): float
    {
        $totalScenarios = Scenario::where('is_active', true)->count();
        if ($totalScenarios === 0) {
            return 0;
        }

        $completedScenarios = $this->progress()
            ->where('status', 'completed')
            ->count();

        return round(($completedScenarios / $totalScenarios) * 100, 2);
    }

    public function updateStreak(): void
    {
        $lastLogin = $this->last_login_at;
        
        if ($lastLogin && $lastLogin->isYesterday()) {
            $this->increment('streak_days');
        } elseif (!$lastLogin || !$lastLogin->isToday()) {
            $this->update(['streak_days' => 1]);
        }
        
        $this->update(['last_login_at' => now()]);
    }
}