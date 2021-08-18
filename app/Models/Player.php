<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{

    use SoftDeletes;
    
	protected $fillable = array('team_id', 'first_name', 'last_name', 'player_image_uri');

    /**
     * Get team
     */
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    public static function create($request)
    {
        $firstName = $request->first_name;
        $lastName = $request->last_name;
        $playerImageUri = $request->player_image_uri;
        $teamId = $request->team_id;

        $player = new Player();
        $player->first_name = $firstName;
        $player->last_name = $lastName;
        $player->player_image_uri = $playerImageUri;
        $player->team_id = $teamId;
        $player->save();
        return $player;

    }
}
