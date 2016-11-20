<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Validator;
use App\Challenge;
use App\ChallengeData;
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
        $challenge_data = ChallengeData::where('user_id', Auth::user()->id)->where('challenge_id', $challenge->id);
        if($challenge_data->count() == 0) {
            $data = new ChallengeData(['user_id' => Auth::user()->id, 'challenge_id' => $challenge->id]);
            $data->code = "public static " . $challenge->return_type . " " . $challenge->prototype . " {
// Your code here
}";
            $data->completed = 0;
            $data->save();
        }
        $challenge_data = $challenge_data->first();
        return view('challenge', compact('challenge', 'challenge_data'));
    }
    public function submit(Request $request) {
        $client = new Client;
        $challenge = Challenge::where('id', $request->challenge)->first();
      
        $r = $client->request('POST', 'http://127.0.0.1:3000', [
            'form_params' => [
                'code' => $request->code,
                'testcase' => $challenge->tests,
                'proto' => $challenge->prototype,
                'return_type' => $challenge->return_type,
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
    public function commitCode(Request $request) {
        $data = ChallengeData::firstOrNew(['user_id' => Auth::user()->id, 'challenge_id' => $request->challenge_id]);
        $data->code = $request->code;
        $data->completed = 0;
        $data->save();
        return ['message' => 'success'];    
    }

    public function submitCreate(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'required',
            'instructions' => 'required',
            'difficulty' => 'required|integer|between:1,3',
            'return_type' => 'required',
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
        $challenge->return_type = $request->return_type;
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
