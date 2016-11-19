<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    public function submit(Request $request) {
        $client = new Client;
        // echo $request->code;
        $r = $client->request('POST', 'http://127.0.0.1:3000', [
            'form_params' => [
                'code' => $request->code
            ]
        ]);
        // echo $r->getBody();
        return ['message' => 'success', 'output' => (String) $r->getBody()];
    }
}
