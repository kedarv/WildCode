<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Validator;
use App\Challenge;
use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
    public function challenge(Request $request) {
        $challenge = Challenge::find($request->id);
        return view('challenge', compact('challenge'));
    }
    public function submit(Request $request) {
        $client = new Client;
        $challenge = Challenge::where('id', $request->challenge)->first();
      
        $r = $client->request('POST', 'http://127.0.0.1:3000', [
            'form_params' => [
                'code' => $request->code,
                'testcase' => $challenge->tests,
                'proto' => $challenge->prototype
            ]
        ]);
        $first = $r->getBody()->getContents()[0];
        if($r->getBody()== "ok") {
            $message = "success";
        } elseif($first == "*") {
            $message = "compilefail";
        }
        else {
            $message = "fail";
        }
        return ['message' => $message, 'output' => (string) $r->getBody()];
    }
    public function create() {
        return view('create');
    }
    public function view() {
        $challenges = Challenge::all();
        // var_dump($challenges);
        return view('view', compact('challenges'));
    }
    public function submitCreate(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'required',
            'instructions' => 'required',
            'difficulty' => 'required|integer|between:1,3',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }
        $tests = "";
        for($i = 0; $i < count($request->test); $i+=2) {
            $tests .= $request->test[$i] . "^" . $request->test[$i+1] . "#";
        }
        // echo $tests;
        $challenge = new Challenge;
        $challenge->user_id = Auth::user()->id;
        $challenge->title = $request->title;
        $challenge->description = $request->description;
        $challenge->tests = $tests;
        $challenge->difficulty = $request->difficulty;
        $challenge->instructions = $request->instructions;
        $challenge->prototype = $request->prototype;
        $challenge->save();
        return ["message" => "success"];
    }

    public function challengeSolution(Request $request) {
        try {
            $challenge = Challenge::findOrFail($request->id);
        } catch(ModelNotFoundException $e) {
            return ['message' => 'Cannot find Challenge'];
        }
        return ['tests' => $challenge['tests']];
    }
}
