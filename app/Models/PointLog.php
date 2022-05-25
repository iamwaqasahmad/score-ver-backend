<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointLog extends Model
{
    use HasFactory;
    use \Awobaz\Compoships\Compoships;
    protected $guarded = [];

    public function prediction(){
        return $this->belongsTo(MatchPrediction::class, 'ref_id', 'id');
    }

    public function scopeMy($query, $user_id){
        return $query->where('user_id', '=', $user_id);
    }

    public function game(){
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function matchDay(){
        return $this->belongsTo(MatchDay::class, 'matchDay_id', 'id');
    }

    public function scopeDays($query){
        return  $query->groupBy('matchDay_id', 'user_id')->selectRaw('*,sum(points) as totalPoints')->with('matchDay');
    }

}
