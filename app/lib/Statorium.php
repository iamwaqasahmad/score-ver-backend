<?php
namespace App\lib;

use Illuminate\Support\Facades\Http;

class Statorium{

  protected $apiKey = 'c97092dd0def75c7b6283d58de6cb77a';
  protected $baseUrl = 'https://api.statorium.com/api/v1/';
  
  protected function getResponse($url)
  {
    return json_decode(Http::get($this->baseUrl.$url.'apikey='.$this->apiKey)->body());
  }

  public function getLeagues()
  {
    $url = 'leagues/?';
    return $this->getResponse($url)->leagues;
  }

  public function getSeasons($league_id)
  {
    $url = 'leagues/'.$league_id.'/?';
    return $this->getResponse($url)->league->seasons;
  }

  public function getSeason($season_id)
  {
    $url = 'seasons/'.$season_id.'/?';
    return $this->getResponse($url)->season;
  }

  public function getParticipants($season_id)
  {
    $url = 'seasons/'.$season_id.'/?';
    return $this->getResponse($url)->season->participants;
  }

  public function getTeam($participant_id, $season_id)
  {
    $url = 'teams/'.$participant_id.'/?season_id='.$season_id.'&';
    return $this->getResponse($url)->team;
  }

  public function getMatches($season_id)
  {
    $url = 'matches/?season_id='.$season_id.'&';
    return $this->getResponse($url)->calendar->matchdays;
  }

  public function getMatch($match_id)
  {
    $url = 'matches/'.$match_id.'/?';
    return $this->getResponse($url)->match;
  }

  public function liveMatches()
  {
    $url = 'matches/live/?';
    return $this->getResponse($url)->matches;
  }
  
}
