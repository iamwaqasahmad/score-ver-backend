<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamePlayer extends Model
{
    use HasFactory;
    use \Awobaz\Compoships\Compoships;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }

    public function pointLog()
    {
        return $this->hasMany(PointLog::class, ['game_id', 'user_id'], ['game_id', 'user_id']);

    }

    public function getTotalPoints()
    {
        return $this->pointLog->sum('points');
    }

}
