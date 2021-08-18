<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;

	protected $fillable = array('name', 'logo_uri');

    /**
     * Get players
     */
    public function players()
    {
        return $this->hasMany('App\Models\Player');
    }

    /**
     * Function to create Team
     * 
     * @param \Illuminate\Http\Request  $request
     * @return $team
     */
    public static function create($request)
    {
        $name = $request->name;
        $logoUri = $request->logo_uri;

        $team = new Team();
        $team->name = $name;
        $team->logo_uri = $logoUri;
        $team->save();
        return $team;

    }
}
