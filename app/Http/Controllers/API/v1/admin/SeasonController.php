<?php

namespace App\Http\Controllers\API\v1\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Season;
use Illuminate\Support\Facades\Auth;


class SeasonController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seasons = [];

        foreach(Season::with('league')->get() as $season){
            $season_obj['id'] = $season->id;
            $season_obj['full_name'] = $season->full_name;
            $season_obj['short_name'] = $season->name;
            $season_obj['league'] = $season->league->name;
            array_push($seasons, $season_obj);
        }
        return $this->sendResponse($seasons);
    }

    
    
}
