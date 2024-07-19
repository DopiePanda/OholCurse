<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;

use App\Models\PlayerMessage;

class EllabotController extends Controller
{
    private $data;
    private $token;

    public function __construct(Request $request)
    {
 
    }

    public function tokenAuth($token)
    {
        if(isset($token) && $token == env('ELLABOT_TOKEN'))
        {
            $this->token = $token;
            return true;
        }
        else
        {
            abort(401);
        }
    }

    public function message(Request $request)
    {
        $this->data = $request->input();
        $this->tokenAuth($request->header('token'));

        $validator = Validator::make($this->data, [
            'server_ip' => 'required|string|max:50',
            'life_id' => 'required|numeric|max_digits:10',
            'life_name' => 'nullable|string|max:50',
            'message' => 'required|string',
            'position' => 'required|string|max:50',
            'bot_id' => 'required|numeric|max_digits:10',
            'items' => 'sometimes',
            'items.hat' => 'nullable|numeric|max_digits:4',
            'items.tunic' => 'nullable|numeric|max_digits:4',
            'items.bottom' => 'nullable|numeric|max_digits:4',
            'items.shoe' => 'sometimes',
            'items.shoe.1' => 'nullable|numeric|max_digits:4',
            'items.shoe.2' => 'nullable|numeric|max_digits:4',
            'items.backpack' => 'sometimes',
            'items.backpack.1' => 'nullable|numeric|max_digits:4',
            'items.backpack.2' => 'nullable|numeric|max_digits:4',
            'items.backpack.3' => 'nullable|numeric|max_digits:4',
            'items.backpack.4' => 'nullable|numeric|max_digits:4',
            'timestamp' => 'required|numeric|max_digits:10'
        ]);
    
        if ($validator->fails()) 
        {
            $errorMessage = $validator->errors()->first();

            $response = [
                'status'  => false,
                'message' => $errorMessage,
            ];

            Log::debug("Failed request from IP: ".$_SERVER["REMOTE_ADDR"]);

            return response()->json($response, 401);
        }
    
        // Only use the properties that were validated.
        $input = $validator->validated();
        $this->storeMessage($input);

        print "Success";

        Log::debug("Successful request from IP: ".$_SERVER["REMOTE_ADDR"]);
        Log::debug($input);
    }

    public function storeMessage($payload)
    {
        $pos = explode(', ', $payload['position']);

        PlayerMessage::updateOrCreate([
            'server_ip' => $payload['server_ip'],
            'bot_id' => $payload['bot_id'],
            'life_id' => $payload['life_id'],
            'timestamp' => $payload['timestamp'],
        ],[
            'life_name' => $payload['life_name'] ?? 'UNNAMED',
            'message' => $payload['message'],
            'pos_x' => $pos[0],
            'pos_y' => $pos[1],
            'items' => $payload['items'],
        ]);
    }
}
