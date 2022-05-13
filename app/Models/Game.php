<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function gameQuestions()
    {
        return $this->hasMany(GameQuestion::class);
    }

    public function tournament(){
        return $this->belongsTo(Tournament::class);
    }

    public function season(){
        return $this->belongsTo(Season::class, 'tournament_id', 'id');
    }

}
