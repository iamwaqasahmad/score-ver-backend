<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function m()
    {
        return $this->hasMany(Matches::class)->Upcoming();
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }

    public function league()
    {
        return $this->belongsTo(League::class, 'league_id', 'id');
    }

    public function tournamentQuestions()
    {
        return $this->hasMany(TournamentQuestion::class, 'tournament_id', 'id');
    }
}
