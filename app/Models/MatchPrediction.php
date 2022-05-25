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

    public function m(){
        return $this->belongsTo(Matches::class, 'match_id', 'id');
    }

    public function pointLog()
    {
        return $this->hasMany(PointLog::class, 'ref_id', 'id');
    }

}
