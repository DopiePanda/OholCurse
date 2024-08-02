<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DonationController extends Controller
{
    private $endpoint;
    private $token;

    public function __construct()
    {
        if(!env('BUY_ME_A_COFFEE_ENDPOINT') || env('BUY_ME_A_COFFEE_ENDPOINT') == null)
        {
            return abort(404);
        }

        $this->endpoint = env('BUY_ME_A_COFFEE_ENDPOINT');

        if(!env('BUY_ME_A_COFFEE_TOKEN') || env('BUY_ME_A_COFFEE_TOKEN') == null)
        {
            return abort(404);
        }

        $this->token = env('BUY_ME_A_COFFEE_TOKEN');
    }

    public function get()
    {
        $page = 1;
        $uri = "/supporters?page=".$page;
        $endpoint = $this->endpoint.$uri;
        $response = Http::withToken($this->token)->get($endpoint);
        $donations = $response->json();

        // TODO: Check if username/email matches with username/email in DB
        // and automatically assign donator status, if not send notify yourself about new donation
    }
}
