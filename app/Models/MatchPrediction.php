<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchPrediction extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function Matches(){
        return $this->belongsTo(Matches::class, 'match_id', 'id');
    }

}
