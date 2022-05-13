<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\GameController;
use App\Http\Controllers\API\V1\TournamentController;
use App\Http\Controllers\API\V1\RequestInvitationController;

use App\Http\Controllers\API\V1\MatchesController;
use App\Http\Controllers\API\V1\PredictionController;
use App\Http\Controllers\API\V1\QuestionController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group( function () {
    
    Route::get('/tournaments', [TournamentController::class, 'index']);
    Route::post('/tournaments', [TournamentController::class, 'store']);
    Route::get('/tournaments/{tournamentId}', [TournamentController::class, 'show']);
    Route::get('/tournaments/{tournamentId}/questions',     [TournamentController::class, 'questions']);
    Route::post('/tournaments/{tournamentId}/questions',    [TournamentController::class, 'questionStore']);

    Route::get('/games', [GameController::class, 'index']);
    Route::post('/games', [GameController::class, 'store']);
    Route::get('/participants', [GameController::class, 'getParticipant']);

    Route::get('/games/{id}', [GameController::class, 'show']);
    Route::put('/games/{id}', [GameController::class, 'update']);
    Route::delete('/games/{id}', [GameController::class, 'destroy']);

    Route::post('/requests', [RequestInvitationController::class, 'createRequest']);
    Route::get('/requests', [RequestInvitationController::class, 'getRequests']);
    Route::post('/invites', [RequestInvitationController::class, 'createInvite']);
    Route::get('/invites', [RequestInvitationController::class, 'getInvites']);
    Route::get('/notifications', [RequestInvitationController::class,   'getNotifications']);
    Route::post('/notifications', [RequestInvitationController::class,  'acceptNotifications']);

    Route::get('/matches', [MatchesController::class, 'index']);
    Route::get('/matches/{game_id}', [MatchesController::class, 'matchesByGameId']);

    Route::get('/questions', [QuestionController::class, 'index']);
    Route::get('/questions/{game_id}', [QuestionController::class, 'matchesByGameId']);

    Route::post('/predicte/match', [PredictionController::class, 'matchPredicte']);
    Route::post('/predicte/question', [PredictionController::class, 'questionPredicte']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
