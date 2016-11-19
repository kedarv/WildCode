<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Validator;
use App\Challenge;
use Auth;

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
    public function create() {
        return view('create');
    }
    public function submitCreate(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }
        $tests = "";
        for($i = 0; $i < count($request->test); $i+=2) {
            $tests .= $request->test[$i] . "^" . $request->test[$i+1] . "#";
        }
        echo $tests;
        $challenge = new Challenge;
        $challenge->user_id = Auth::user()->id;
        $challenge->title = $request->title;
        $challenge->description = $request->description;
        $challenge->tests = $tests;
        $challenge->save();
        return ["message" => "success"];
    }
}
