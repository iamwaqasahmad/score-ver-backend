<?php

namespace App\Http\Controllers\API\v1;


use App\Http\Controllers\API\v1\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\RequestInvitation;
use App\Models\Game;
use App\Models\GamePlayer;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Mail\Invite;
use App\Mail\RequestGame;
use App\Mail\AcceptInvitation;
use App\Mail\AcceptRequest;

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
        $options = array(
            'unsubscribe_url'   => 'http://mysite.com/unsub',
            'play_url'          => 'http://google-play.com/myapp',
            'ios_url'           => 'http://apple-store.com/myapp',
            'sendfriend_url'    => 'http://mysite.com/send_friend',
            'webview_url'       => 'http://mysite.com/webview_url',
        );
        $userObj = User::find($request_invite->reciver_id);
        Mail::to($userObj)->queue(new RequestGame($userObj, $options));

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

        $options = array(
            'unsubscribe_url'   => 'http://mysite.com/unsub',
            'play_url'          => 'http://google-play.com/myapp',
            'ios_url'           => 'http://apple-store.com/myapp',
            'sendfriend_url'    => 'http://mysite.com/send_friend',
            'webview_url'       => 'http://mysite.com/webview_url',
        );
                
        $userObj = User::find($request_invite->reciver_id);
        Mail::to($userObj)->queue(new InviteGame($userObj, $options));

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
        $user_id =  Auth::id();
        $validated = $request->validate([
            'id' => 'required',
        ]);
        $notification = RequestInvitation::find($request->id);
        $notification->status = $request->status;
        $notification->save();
        
        if($notification->status == 'accepted'){
            $gp = new GamePlayer();
            $gp->user_id =  Auth::id();
            $gp->game_id = $notification->game_id;
            $gp->save();
        
            $options = array(
                'unsubscribe_url'   => 'http://mysite.com/unsub',
                'play_url'          => 'http://google-play.com/myapp',
                'ios_url'           => 'http://apple-store.com/myapp',
                'sendfriend_url'    => 'http://mysite.com/send_friend',
                'webview_url'       => 'http://mysite.com/webview_url',
            );
            $userObj = User::find($notification->sender_id);
            $mail = $notification->type == 'request' ? new AcceptRequest($userObj, $options) : new AcceptInvitation($userObj, $options);

            Mail::to($userObj)->queue($mail);
        }


        return $this->sendResponse($notification);
    }

    public function isRequestedOrInvited($game_id)
    {
        $user_id =  Auth::id();
        $request_or_invitation = RequestInvitation::where('game_id', '=' , $game_id)->where('status', '!=', 'rejected')->IsRequestedOrInvited($user_id)->count();
        return $this->sendResponse($request_or_invitation);
    }

    public function getNotificationsCount()
    {
        $user_id =  Auth::id();
        $notifications = RequestInvitation::where('reciver_id', '=' , $user_id)->where('status', '=', 'pending')->count();
        return $this->sendResponse($notifications);
    }
}
