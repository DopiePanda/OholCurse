<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

use App\Models\LifeLog;

class LifeDataController extends Controller
{
    public function find(Request $request)
    {
        $term = trim($request->q);

        if (empty($term)) {
            return \Response::json([]);
        }

        if(is_numeric($term))
        {
            $lives = LifeLog::with('name')
                        ->has('name')
                        ->where('character_id', 'like', $term.'%')
                        ->where('type', 'death')
                        ->limit(5)
                        ->get();

        }else
        {
            $lives = LifeLog::whereHas('name', function (Builder $query) use ($term){
                $query->where('name', 'like', $term.'%');
            })->where('type', 'death')->limit(5)->get();
        }

        

        $formatted_lives = [];

        foreach ($lives as $life) {
            $formatted_lives[] = ['id' => $life->character_id, 'text' => $life->name->name];
        }

        return \Response::json($formatted_lives);
    }
}
