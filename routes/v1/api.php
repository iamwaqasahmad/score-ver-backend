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
use App\Http\Controllers\API\V1\ReportController;

use App\Http\Controllers\API\V1\admin\UsersController;
use App\Http\Controllers\API\V1\admin\SeasonController;
use App\Http\Controllers\API\V1\admin\ScoreController;
use App\Http\Controllers\API\V1\admin\MatchesController as AdminMatchesController;
use App\Http\Controllers\API\V1\admin\QuestionController as AdminQuestionController;
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
    Route::post('/user/activation', [AuthController::class, 'userActivation']);
    Route::get('/user/resend-verification-code', [AuthController::class, 'resendVerificationCode']);

    Route::get('/tournaments', [TournamentController::class, 'index']);
    Route::post('/tournaments', [TournamentController::class, 'store']);
    Route::get('/tournaments/{tournamentId}', [TournamentController::class, 'show']);
    Route::get('/tournaments/{tournamentId}/questions',     [TournamentController::class, 'questions']);
    Route::post('/tournaments/{tournamentId}/questions',    [TournamentController::class, 'questionStore']);

    Route::get('/games', [GameController::class, 'index']);
    Route::post('/games', [GameController::class, 'store']);
    Route::get('/participants', [GameController::class, 'getParticipant']);

    Route::get('/games/{id}', [GameController::class, 'show']);
    Route::get('/games/{id}/players', [GameController::class, 'gamePlayers']);
    Route::get('/games/{id}/logs', [GameController::class, 'logs']);
    Route::get('/games/{id}/request-invitation', [GameController::class, 'getGameRequestInvitation']);
    Route::put('/games/{id}', [GameController::class, 'update']);
    Route::delete('/games/{id}', [GameController::class, 'destroy']);

    Route::post('/requests', [RequestInvitationController::class, 'createRequest']);
    Route::get('/requests', [RequestInvitationController::class, 'getRequests']);
    Route::post('/invites', [RequestInvitationController::class, 'createInvite']);
    Route::get('/invites', [RequestInvitationController::class, 'getInvites']);
    Route::get('/notifications', [RequestInvitationController::class,   'getNotifications']);
    Route::get('/notifications/count', [RequestInvitationController::class,   'getNotificationsCount']);
    Route::post('/notifications', [RequestInvitationController::class,  'acceptNotifications']);
    Route::get('/games/{id}/is-requested-or-invited', [RequestInvitationController::class,   'isRequestedOrInvited']);
    
    Route::get('/matches', [MatchesController::class, 'index']);
    Route::get('/matches/{game_id}', [MatchesController::class, 'matchesByGameId']);

    Route::get('/questions', [QuestionController::class, 'index']);
    Route::get('/questions/{game_id}', [QuestionController::class, 'matchesByGameId']);

    Route::post('/predicte/match', [PredictionController::class, 'matchPredicte']);
    Route::post('/predicte/question', [PredictionController::class, 'questionPredicte']);
    
    Route::get('/report', [ReportController::class, 'index']);
    Route::get('/report/my', [ReportController::class, 'my']);
    Route::get('/report/my/log', [ReportController::class, 'myLog']);

    /** Admin Routes */
    Route::group([
        'prefix' => 'admin',
        'middleware' => 'is_admin',
        'as' => 'admin.'
    ], function() {
        Route::get('/users', [UsersController::class, 'index']);
        Route::get('/users/{id}', [UsersController::class, 'getUserById']);
        Route::post('/users/{id}', [UsersController::class, 'updateUser']);
        Route::get('/seasons', [SeasonController::class, 'index']);
        Route::get('/matches', [AdminMatchesController::class, 'index']);
        Route::get('/seasons/{seasonId}/matches', [AdminMatchesController::class, 'index']);
        Route::get('/scores', [ScoreController::class, 'index']);
        Route::get('/questions', [AdminQuestionController::class, 'index']);
        Route::get('/questions/{questionId}', [AdminQuestionController::class, 'show']);
    });
    
    
    Route::post('/logout', [AuthController::class, 'logout']);
});
