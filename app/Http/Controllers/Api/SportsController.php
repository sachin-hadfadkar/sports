<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Team;
use App\Models\Player;

class SportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::all('name', 'logo_uri');
        //print_r($teams);exit();
        return json_encode(array("status"=>200, "message"=>"Team List", "response" => $teams));
    }

    public function teamPlayersList($param)
    {
        $team = Team::where('id', $param)->orWhere('name', $param)->first();
        $players = $team->players()->get();
        return json_encode(array("status"=>200, "message"=>"Player List", "response" => $players));
    }

    public function playerList($param)
    {
        $player = Player::where('id', $param)->orWhere('first_name', $param)->orWhere('last_name', $param)->first();
        $team = $player->team()->get('name');
        return json_encode(array("status"=>200, "message"=>"Players", "response" => array($player, $team)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createTeam(Request $request)
    {
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
        //$validatedData['password'] = bcrypt($request->password);

        $team = Team::create($request);

        return json_encode(array("status"=>200, "message"=>"Team Created Successfully", "response" => $team->id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createPlayer(Request $request)
    {
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
        //$validatedData['password'] = bcrypt($request->password);

        $team = Team::where('id', $request->team_id)->first();
        if(!$team) {
            return response()->json(["status"=>500, 'message' => 'Invalid Team']);
        }
        $player = Player::create($request);

        
        return json_encode(array("status"=>200, "message"=>"Player Created Successfully", "response" => $player->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateTeam(Request $request)
    {
        $team = Team::find($request->id)->first();
        if (!$team) {
            return response()->json(["status"=>500, 'message' => 'Invalid Team']);
        }
        $name = $request->input('name');
        $logo = $request->input('logoUri');
        if ($name) {
            $team->name = $name;
        }
        if($logo) {
            $team->logo_uri = $logo;
        }
        $team->save();
        
        return json_encode(array("status"=>200, "message"=>"Team Updated Successfully", "response" => $team->id));
    }

    public function updatePlayer(Request $request)
    {
        $player = Player::find($request->id);
        
        $teamId = $request->input('teamsId');
        $firstName = $request->input('firstName');
        $lastName = $request->input('lastName');
        $playerImageUri = $request->input('playerImageUri');
        if($teamId) {
            $team = Team::find($request->teamsId);
            if(!$team) {
                return response()->json(["status"=>500, 'message' => 'Invalid Team']);
            }
            $player->team_id = $teamId;
        }
        if($firstName) {
            $player->first_name = $firstName;
        }
        if($lastName) {
            $player->last_name = $lastName;
        }
        if($playerImageUri) {
            $player->player_image_uri = $playerImageUri;
        }
        
        
        $player->save();

        
        return json_encode(array("status"=>200, "message"=>"Player Details Updated Successfully", "response" => $player->id));
    }



    public function deleteTeam(Request $request)
    {
        $teamId = $request->id;
        $team = Team::withTrashed()->find($teamId);
        if($team) {
            $team->forceDelete();
            return json_encode(array("status"=>200, "message"=>"Team Deleted Successfully", "response" => $teamId));
        }
        return json_encode(array("status"=>500, "message"=>"Team Not Found", "response" => $teamId));
    }

    public function disableTeam(Request $request)
    {
        $teamId = $request->id;
        $team = Team::find($teamId);
        if($team) {
            $team->delete();
            return json_encode(array("status"=>200, "message"=>"Team Disabled Successfully", "response" => $teamId));
        }
        return json_encode(array("status"=>500, "message"=>"Team Not Found", "response" => $teamId));
    }

    public function disablePlayer(Request $request) {
        $playerId = $request->id;
        $player = Player::find($playerId);
        if($player) {
            $player->delete();
            return json_encode(array("status"=>200, "message"=>"Player Disabled", "response" => $playerId));
        }
        return json_encode(array("status"=>500, "message"=>"Player Not Found", "response" => $playerId));
        
    }

    public function deletePlayer(Request $request)
    {
        $playerId = $request->id;
        $player = Player::withTrashed()->find($playerId);
        if($player) {
            $player->forceDelete();
            return json_encode(array("status"=>200, "message"=>"Player Deleted Successfully", "response" => $playerId));
        }
        return json_encode(array("status"=>200, "message"=>"Player Not Found", "response" => $playerId));
    }
}
