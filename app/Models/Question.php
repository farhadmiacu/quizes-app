<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = ['question_text', 'status', 'sort_order'];

    public function options(): HasMany
    {
        return $this->hasMany(Option::class)->orderBy('sort_order');
    }

    public function correctOption(): ?Option
    {
        return $this->options()->where('is_correct', true)->first();
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
