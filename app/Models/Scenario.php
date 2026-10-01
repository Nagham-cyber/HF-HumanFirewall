<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Scenario extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'title',
        'description',
        'category',
        'difficulty',
        'estimated_minutes',
        'thumbnail_path',
        'content',
        'metadata',
        'is_template',
        'is_active',
        'version',
        'created_by',
    ];

    protected $casts = [
        'content' => 'array',
        'metadata' => 'array',
        'is_template' => 'boolean',
        'is_active' => 'boolean',
        'version' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($scenario) {
            $scenario->uuid = (string) Str::uuid();
        });
    }

    // العلاقات
    public function steps()
    {
        return $this->hasMany(ScenarioStep::class)->orderBy('order');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function userProgress()
    {
        return $this->hasMany(UserProgress::class);
    }

    public function versions()
    {
        return $this->hasMany(ContentVersion::class);
    }

    // الدوال المساعدة
    public function getLocalizedContent(string $locale = 'en'): array
    {
        return $this->content[$locale] ?? $this->content['en'] ?? [];
    }

    public function getAverageScoreAttribute(): float
    {
        return $this->userProgress()
            ->where('status', 'completed')
            ->avg('score') ?? 0;
    }

    public function getCompletionRateAttribute(): float
    {
        $total = $this->userProgress()->count();
        if ($total === 0) {
            return 0;
        }

        $completed = $this->userProgress()
            ->where('status', 'completed')
            ->count();

        return round(($completed / $total) * 100, 2);
    }

    public function duplicate(): Scenario
    {
        $newScenario = $this->replicate();
        $newScenario->title = $this->title . ' (Copy)';
        $newScenario->uuid = (string) Str::uuid();
        $newScenario->is_template = false;
        $newScenario->version = 1;
        $newScenario->save();

        // نسخ الخطوات
        foreach ($this->steps as $step) {
            $newStep = $step->replicate();
            $newStep->scenario_id = $newScenario->id;
            $newStep->save();
        }

        // نسخ الأسئلة
        foreach ($this->questions as $question) {
            $newQuestion = $question->replicate();
            $newQuestion->scenario_id = $newScenario->id;
            $newQuestion->save();

            // نسخ الخيارات
            foreach ($question->options as $option) {
                $newOption = $option->replicate();
                $newOption->question_id = $newQuestion->id;
                $newOption->save();
            }
        }

        return $newScenario;
    }
}