<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Question
 * @package App\Models
 *
 * @mixin Builder
 */
class Question extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function choices()
    {
        return $this->hasMany(Choice::class, 'question_id', 'id');
    }
}
