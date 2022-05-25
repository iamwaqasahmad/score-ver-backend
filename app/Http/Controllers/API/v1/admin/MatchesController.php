<?php

namespace App\Http\Controllers\API\V1\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Matches;
use Illuminate\Support\Facades\Auth;


class MatchesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $raw_matches = Matches::with('season')->with('matchDay')->with('homeParticipant')->with('awayParticipant')->get();

        return $this->sendResponse($raw_matches);
    }

    
    
}
