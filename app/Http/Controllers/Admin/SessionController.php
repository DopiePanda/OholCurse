<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function hideMenu(Request $request)
    {
        $request->session()->put('showAdminMenu', false);
    }

    public function showMenu(Request $request)
    {
        $request->session()->put('showAdminMenu', true);
    }
}
