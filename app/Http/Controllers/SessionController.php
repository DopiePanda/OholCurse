<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{

    public function getSession($key, $default = null)
    {
        return session($key, $default);
    }

    public function setSession(Request $request)
    {
        session([$request->key => $request->value]);
        return session($request->key);
    }

    public function increaseValue($key, $num = 1)
    {
        $times = session($key) + $num;
        session([$key => $times]);
    }

    public function decreaseValue($key, $num = 1)
    {
        $times = session($key) - $num;
        session([$key => $times]);
    }
}
