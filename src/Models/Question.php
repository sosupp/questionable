<?php

namespace Sosupp\Questionable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sosupp\SlimDashboard\Concerns\Filters\CommonScopes;

class Question extends Model
{
    use HasFactory, SoftDeletes, CommonScopes;

    protected $fillable = [
        'question_bank_id',
        'question_type_id',
        'subject_id',
        'academic_level_id',
        'year_id',
        'dificult_level',
        'topic',
        'question_text',
        'metadata',
        'points',
        'is_active',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean'
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicLevel(): BelongsTo
    {
        return $this->belongsTo(AcademicLevel::class);
    }

    public function year(): BelongsTo
    {
        return $this->belongsTo(Year::class);
    }

    public function meta(): HasMany
    {
        return $this->hasMany(QuestionMetadata::class);
    }

    public function questionBank()
    {
        return $this->belongsTo(QuestionBank::class);
    }

    public function questionType()
    {
        return $this->belongsTo(QuestionType::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function quizzes()
    {
        return $this->morphedByMany(Quiz::class, 'questionable');
    }

    public function polls()
    {
        return $this->morphedByMany(Poll::class, 'questionable');
    }


    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeGeneral($query)
    {
        return $query->whereHas('questionBank', function ($q) {
            $q->where('slug', 'general-knowledge-bank');
        });
    }

    public function scopeWithSubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeWithAcademicLevel($query, $levelId)
    {
        return $query->where('academic_level_id', $levelId);
    }

    public function scopeWithYear($query, $yearId)
    {
        return $query->where('year_id', $yearId);
    }

    public function scopeWithDifficulty($query, $level)
    {
        return $query->where('difficulty_level', $level);
    }

    // Mutators and Accessors
    public function getMetadataAttribute($value)
    {
        $defaults = [
            'blooms_taxonomy_level' => null,
            'learning_objective' => null,
            'curriculum_reference' => null,
        ];

        return array_merge($defaults, json_decode($value, true) ?? []);
    }
}
