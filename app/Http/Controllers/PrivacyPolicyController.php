<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PrivacyPolicy;

class PrivacyPolicyController extends Controller
{
    public function read()
    {
        $policy = PrivacyPolicy::orderBy('id', 'desc')->first();
        return view('privacy-policy', ['policy' => $policy]);
    }
}
