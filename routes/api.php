<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoldierController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\MissionController;


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

//Rutas cuyas funciones estan en SoldierController
Route::post('/soldiers/create', [SoldierController::class, 'createSoldier']);
Route::post('/soldiers/update/{id}', [SoldierController::class, 'updateSoldier']);
Route::post('/soldiers/update/estado/{id}', [SoldierController::class, 'updateEstado']);
Route::get('/soldiers/list', [SoldierController::class, 'viewAll']);
Route::get('/soldiers/show/{id}', [SoldierController::class, 'details']);
Route::get('/soldiers/show/missions/{id}', [SoldierController::class, 'missionHistoryList']);


//Rutas cuyas funciones estan en TeamldierController
Route::post('/teams/create', [TeamController::class, 'createTeam']);
Route::post('/teams/update/{id}', [TeamController::class, 'updateTeam']);
Route::post('/teams/delete/{id}', [TeamController::class, 'deleteTeam']);
Route::post('/teams/leader/{id}', [TeamController::class, 'addLeader']);
Route::post('/teams/add/soldier', [TeamController::class, 'addSoldier']);
Route::post('/teams/add/mission', [TeamController::class, 'asignarMision']);
Route::get('/teams/showMembers/{id}', [TeamController::class, 'miembrosEquipos']);
Route::post('/teams/quit/soldier', [TeamController::class, 'eliminarSoldado']);
Route::post('/teams/new/leader', [TeamController::class, 'newLeader']);

//Rutas cuyas funciones estan en MissionController
Route::post('/missions/create', [MissionController::class, 'createMission']);
Route::post('/missions/update/{id}', [MissionController::class, 'updateMission']);
Route::get('/missions/list', [MissionController::class, 'viewAllMissions']);
Route::get('/missions/details/{id}', [MissionController::class, 'viewMissionDetails']);

