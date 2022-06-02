<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestInvitation extends Model
{
    protected $table = 'requests_invitation';
    protected $guarded = [];


    use HasFactory;

    public function sender(){
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function game(){
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }

    public function reciver(){
        return $this->belongsTo(User::class, 'reciver_id', 'id');
    }

    public function scopeIsRequestedOrInvited($query, $user_id){
        return $query->where('reciver_id', '=', $user_id)->orWhere('sender_id', '=', $user_id);;
    }


}
