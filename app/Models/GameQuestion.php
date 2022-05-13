<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameQuestion extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function tournamentQuestion()
    {
        return $this->belongsTo(TournamentQuestion::class, 'question_id', 'id');
    }

    public function scopeQuestionPrediction($game_id){
        return $this->hasOne(QuestionPrediction::class, 'question_id', 'id');
    }

}
