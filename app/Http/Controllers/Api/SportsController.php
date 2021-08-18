<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Team;
use App\Models\Player;
use Throwable;

class SportsController extends Controller
{
    /**
     * Display list of all teams
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $teams = Team::all('name', 'logo_uri');
            if ($teams) {
                return json_encode(array("status" => 200, "message" => "Team List", "response" => $teams));
            }
            return json_encode(array("status" => 500, "message" => "No teams to display"));
        } catch (Throwable $e) {
            report($e);
            return json_encode(array("status" => 500, "message" => "Something went wrong"));
        }
    }

    /**
     * Get specific Team Players List
     *
     * @param $param
     * @return $players
     */
    public function teamPlayersList($param)
    {
        try {
            $team = Team::where('id', $param)->orWhere('name', $param)->first();
            if ($team) {
                $players = $team->players()->get();
                if ($players) {
                    return json_encode(array("status" => 200, "message" => "Player List", "response" => $players));
                }
                return json_encode(array("status" => 500, "message" => "No players associated with this team"));
            }
            return json_encode(array("status" => 500, "message" => "Undefined team"));
        } catch (Throwable $e) {
            report($e);
            return json_encode(array("status" => 500, "message" => "Something went wrong"));
        }
    }

    /**
     * Get Player info with team details
     *
     * @param $param
     * @return $players, $team
     */
    public function playerList($param)
    {
        try {
            $player = Player::where('id', $param)->orWhere('first_name', $param)->orWhere('last_name', $param)->first();
            if ($player) {
                $team = $player->team()->get('name');
                if ($team) {
                    return json_encode(array("status" => 200, "message" => "Players", "response" => array($player, $team)));
                }
                return json_encode(array("status" => 200, "message" => "Players info without team details", "response" => array($player)));
            }
            return json_encode(array("status" => 500, "message" => "Player not found"));
        } catch (Throwable $e) {
            report($e);
            return json_encode(array("status" => 500, "message" => "Something went wrong"));
        }
    }

    /**
     * Create a new Team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createTeam(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'logo_uri' => 'required|url'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->all(),
                ], 401);
            }

            $team = Team::create($request);

            return json_encode(array("status" => 200, "message" => "Team Created Successfully", "response" => $team->id));
        } catch (Throwable $e) {
            report($e);
            return json_encode(array("status" => 500, "message" => "Something went wrong"));
        }
    }

    /**
     * Create a new player
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createPlayer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'team_id' => 'required|integer',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'player_image_uri' => 'required|url'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->all(),
                ], 401);
            }

            $team = Team::where('id', $request->team_id)->first();
            if (!$team) {
                return response()->json(["status" => 500, 'message' => 'Invalid Team']);
            }
            $player = Player::create($request);


            return json_encode(array("status" => 200, "message" => "Player Created Successfully", "response" => $player->id));
        } catch (Throwable $e) {
            report($e);
            return json_encode(array("status" => 500, "message" => "Something went wrong"));
        }
    }

    /**
     * Update team
     *
     * @param \Illuminate\Http\Request  $request
     * @return 
     */
    public function updateTeam(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'name' => 'string',
                'logo_uri' => 'url'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->all(),
                ], 401);
            }
            $team = Team::find($request->id)->first();
            if (!$team) {
                return response()->json(["status" => 500, 'message' => 'Invalid Team']);
            }
            $name = $request->input('name');
            $logo = $request->input('logoUri');
            if ($name) {
                $team->name = $name;
            }
            if ($logo) {
                $team->logo_uri = $logo;
            }
            $team->save();

            return json_encode(array("status" => 200, "message" => "Team Updated Successfully", "response" => $team->id));
        } catch (Throwable $e) {
            report($e);
            return json_encode(array("status" => 500, "message" => "Something went wrong"));
        }
    }

    /**
     * Update player
     *
     * @param \Illuminate\Http\Request  $request
     * @return 
     */
    public function updatePlayer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'team_id' => 'integer',
                'first_name' => 'string',
                'last_name' => 'string',
                'player_image_uri' => 'url'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->all(),
                ], 401);
            }
            $player = Player::find($request->id);
            if (!$player) {
                return response()->json(["status" => 500, 'message' => 'Player not found']);
            }
            $teamId = $request->input('teamsId');
            $firstName = $request->input('firstName');
            $lastName = $request->input('lastName');
            $playerImageUri = $request->input('playerImageUri');
            if ($teamId) {
                $team = Team::find($request->teamsId);
                if (!$team) {
                    return response()->json(["status" => 500, 'message' => 'Invalid Team']);
                }
                $player->team_id = $teamId;
            }
            if ($firstName) {
                $player->first_name = $firstName;
            }
            if ($lastName) {
                $player->last_name = $lastName;
            }
            if ($playerImageUri) {
                $player->player_image_uri = $playerImageUri;
            }


            $player->save();


            return json_encode(array("status" => 200, "message" => "Player Details Updated Successfully", "response" => $player->id));
        } catch (Throwable $e) {
            report($e);
            return json_encode(array("status" => 500, "message" => "Something went wrong"));
        }
    }


    /**
     * Delete team
     *
     * @param \Illuminate\Http\Request  $request
     * @return $teamId
     */
    public function deleteTeam(Request $request)
    {
        try {
            $teamId = $request->id;
            $team = Team::withTrashed()->find($teamId);
            if ($team) {
                $team->forceDelete();
                return json_encode(array("status" => 200, "message" => "Team Deleted Successfully", "response" => $teamId));
            }
            return json_encode(array("status" => 500, "message" => "Team Not Found", "response" => $teamId));
        } catch (Throwable $e) {
            report($e);
            return json_encode(array("status" => 500, "message" => "Something went wrong"));
        }
    }

    /**
     * Soft delete team
     *
     * @param \Illuminate\Http\Request  $request
     * @return $teamId
     */
    public function disableTeam(Request $request)
    {
        try {
            $teamId = $request->id;
            $team = Team::find($teamId);
            if ($team) {
                $team->delete();
                return json_encode(array("status" => 200, "message" => "Team Disabled Successfully", "response" => $teamId));
            }
            return json_encode(array("status" => 500, "message" => "Team Not Found", "response" => $teamId));
        } catch (Throwable $e) {
            report($e);
            return json_encode(array("status" => 500, "message" => "Something went wrong"));
        }
    }

    /**
     * Soft delete player
     *
     * @param \Illuminate\Http\Request  $request
     * @return $teamId
     */
    public function disablePlayer(Request $request)
    {
        try {
            $playerId = $request->id;
            $player = Player::find($playerId);
            if ($player) {
                $player->delete();
                return json_encode(array("status" => 200, "message" => "Player Disabled", "response" => $playerId));
            }
            return json_encode(array("status" => 500, "message" => "Player Not Found", "response" => $playerId));
        } catch (Throwable $e) {
            report($e);
            return json_encode(array("status" => 500, "message" => "Something went wrong"));
        }
    }

    /**
     * Delete player
     *
     * @param \Illuminate\Http\Request  $request
     * @return $teamId
     */
    public function deletePlayer(Request $request)
    {
        try {
            $playerId = $request->id;
            $player = Player::withTrashed()->find($playerId);
            if ($player) {
                $player->forceDelete();
                return json_encode(array("status" => 200, "message" => "Player Deleted Successfully", "response" => $playerId));
            }
            return json_encode(array("status" => 200, "message" => "Player Not Found", "response" => $playerId));
        } catch (Throwable $e) {
            report($e);
            return json_encode(array("status" => 500, "message" => "Something went wrong"));
        }
    }
}
