<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'question_en',
        'question_ar',
        'answer_en',
        'answer_ar',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the question based on current locale.
     */
    public function getQuestionAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->question_ar : $this->question_en;
    }

    /**
     * Get the answer based on current locale.
     */
    public function getAnswerAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->answer_ar : $this->answer_en;
    }
}
