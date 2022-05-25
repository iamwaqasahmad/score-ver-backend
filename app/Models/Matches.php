<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    use HasFactory;
    protected $table = 'matches';

    protected $guarded = [];

    public function season(){
        return $this->belongsTo(Season::class);
    }


    public function homeParticipant(){
        return $this->belongsTo(Participant::class, 'home_participant_id', 'id');
    }

    public function awayParticipant(){
        return $this->belongsTo(Participant::class, 'away_participant_id', 'id');
    }

    public function scopeUpcoming($query){
        return $query->where('status', '=', 0);
    }

    public function matchDay()
    {
        return $this->belongsTo(MatchDay::class, 'matchDay_id', 'id');
    }

    public function score(){
        return $this->hasOne(Score::class, 'match_id', 'id');
    }


    public function scopeMatchPrediction($game_id){
        return $this->hasOne(MatchPrediction::class, 'match_id', 'id');
    }

}
