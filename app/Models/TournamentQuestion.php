<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentQuestion extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function season()
    {
        return $this->belongsTo(Season::class, 'tournament_id', 'id');
    }

}
