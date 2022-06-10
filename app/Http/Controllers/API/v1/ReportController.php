<?php

namespace App\Http\Controllers\API\v1;


use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\PointLog;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;


class ReportController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(PointLog::with('prediction')->get());
    }

    public function myLog()
    {
        $user_id = Auth::id();
        $user_id = 2;
        return $this->sendResponse(PointLog::with('prediction')->My($user_id)->get()); 
    }

    public function my()
    {
        $user_id = Auth::id();
        $games = Game::all();

        $user_id = 2;
        return $this->sendResponse($games); 
    }
    
}
