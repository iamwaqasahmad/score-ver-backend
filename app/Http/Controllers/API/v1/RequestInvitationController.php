<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\RequestInvitation;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;

class RequestInvitationController extends BaseController
{
    public function createRequest(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required',
        ]);

        //get reciver id from game id
        $game = Game::find($request->game_id);

        $request_invite = new RequestInvitation();

        $sender_id = Auth::id();
 
        $request_invite->sender_id = $sender_id;
        $request_invite->reciver_id = $game->user_id;
        $request_invite->game_id = $request->game_id;
        $request_invite->type = 'request';
        $request_invite->status = 'pending';
 
        $request_invite->save();

        return $this->sendResponse($request_invite);

    }

    public function createInvite(Request $request)
    {
        $validated = $request->validate([
            'reciver_id' => 'required',
            'game_id' => 'required',

            ]);
        $request_invite = new RequestInvitation();

        $sender_id = Auth::id();
 
        $request_invite->sender_id = $sender_id;
        $request_invite->reciver_id = $request->reciver_id;
        $request_invite->game_id = $request->game_id;
        $request_invite->type = 'invite_via_email';
        $request_invite->status = 'pending';
 
        $request_invite->save();

        return $this->sendResponse($request_invite);

    }

    public function getNotifications()
    {
        $user_id =  Auth::id();
        $notifications = RequestInvitation::where('reciver_id', '=' , $user_id)->where('status', '=', 'pending')->with('sender')->with('game')->get();
        return $this->sendResponse($notifications);
    }

    public function acceptNotifications(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
        ]);
        $notification = RequestInvitation::find($request->id);
        $notification->status = $request->status;
        $notification->save();
        return $this->sendResponse($notification);
    }

    public function getRequests(Request $request)
    {

    }

    public function getInvites(Request $request)
    {

    }
}
