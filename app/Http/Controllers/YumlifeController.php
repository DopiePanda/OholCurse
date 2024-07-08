<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leaderboard;

class YumlifeController extends Controller
{
    public function findPlayerProfile(Request $request, $name)
    {
        if($name == null)
        {
            return abort(404);
        }

        $name = hex2bin($name);

        $profile = Leaderboard::where('leaderboard_name', $name)->first();

        if($profile)
        {
            return redirect(route('player.interactions', ['hash' => $profile->player_hash]));
        }
        else
        {
            return view('player.account-missing');
        }
    }
}
