<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Builder;

use App\Models\LifeLog;
use App\Models\LifeNameLog;

use Log;

class Select2Controller extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $start = microtime(true);

        $data = [];
    
        if($request->filled('q'))
        {
            $search = $request->get('q');

            /* $data = LifeNameLog::select("name", "character_id")
                        ->where('name', 'LIKE', $request->get('q'). '%')
                        ->limit(25)
                        ->get(); */

            $data = LifeLog::with('name:name,character_id')
                ->whereHas('name', function (Builder $query) use ($search){
                    $query->whereNotNull('name');
                    $query->where('name', 'LIKE', $search. '%');
                })
                ->where('type', 'birth')
                ->select('character_id', 'timestamp')
                ->orderBy('character_id', 'desc')
                ->limit(10)
                ->get();
        }

        $elapsed = microtime(true) - $start;
        
        Log::debug("Finished after $elapsed seconds");
     
        return response()->json($data);
    }
}
