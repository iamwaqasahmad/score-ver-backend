<?php
namespace App\lib;

class PointCalculator{

  const FULLY_WRONG = 0;
  const FULLY_CORRECT = 4;
  const DIFFERENCE_RIGHT = 3;
  const WIN_PREDICTED = 2;
  const DRAW_PREDICTED = 2;

  protected $real_score;
  protected $predicted_score;
  protected $is_result_draw;
  protected $is_predicted_draw;
  protected $result_winner;
  protected $predicted_winner;

  public $points = 0;


  public function __construct($real_score, $predicted_score){
    $this->real_score = $real_score;
    $this->predicted_score = $predicted_score;
    
    $this->is_result_draw = $this->is_result_draw();
    $this->result_winner = $this->get_result_winner();
    $this->is_predicted_draw = $this->is_predicted_draw();
    $this->predicted_winner = $this->get_predicted_winner();

    $this->calculate();
  }

  private function is_result_draw()
  {
    return $this->real_score['homeParticipant'] == $this->real_score['awayParticipant'];
  }

  private function get_result_winner()
  {
    return !$this->is_result_draw && $this->real_score['homeParticipant'] > $this->real_score['awayParticipant'] ? 'homeParticipant' : 'awayParticipant';
  }

  private function is_predicted_draw()
  {
    return $this->predicted_score['homeParticipant'] == $this->predicted_score['awayParticipant'];
  }

  private function get_predicted_winner()
  {
    return !$this->is_predicted_draw && $this->predicted_score['homeParticipant'] > $this->predicted_score['awayParticipant'] ? 'homeParticipant' : 'awayParticipant';
  }

  private function calculate()
  {
    if($this->is_fully_wrong_prediction()) $this->points = self::FULLY_WRONG;

    if($this->is_win_predicted()) $this->points = self::WIN_PREDICTED;

    if($this->is_draw_predicted()) $this->points = self::DRAW_PREDICTED;

    if($this->difference()) $this->points = self::DIFFERENCE_RIGHT;
    
    if($this->is_fully_right_prediction()) $this->points = self::FULLY_CORRECT;

  }

  private function is_fully_wrong_prediction()
  {
    return $this->real_score['homeParticipant'] != $this->predicted_score['homeParticipant'] && $this->real_score['awayParticipant'] != $this->predicted_score['awayParticipant'];
  }

  private function is_win_predicted()
  {
    return $this->result_winner == $this->predicted_winner && !$this->is_result_draw; 
  }

  private function is_draw_predicted()
  {
    return $this->is_result_draw && $this->is_predicted_draw; 
  }

  private function difference()
  {

    $real_score_diff = $this->real_score['homeParticipant'] - $this->real_score['awayParticipant'];

    $predicted_score_diff = $this->predicted_score['homeParticipant'] - $this->predicted_score['awayParticipant'];

    return $real_score_diff === $predicted_score_diff && !$this->is_draw_predicted();
  }

  private function is_fully_right_prediction()
  {
    return $this->real_score['homeParticipant'] == $this->predicted_score['homeParticipant'] && $this->real_score['awayParticipant'] == $this->predicted_score['awayParticipant'];
  }


  
}
