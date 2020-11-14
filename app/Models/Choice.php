<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Choice
 * @package App\Models
 *
 * @mixin Builder
 */
class Choice extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['question_id'];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
}
