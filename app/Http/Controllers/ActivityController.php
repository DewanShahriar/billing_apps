<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ClientInfo;
use App\Conversation;
use App\User;
use DB;
Use Alert;

class ActivityController extends Controller
{
    //
    public $user_model;
    public $conver_model;
    public function __construct()
	{
	    $this->middleware('auth');
	    $this->user_model = new User();
	    $this->conver_model = new Conversation();
	}

	public function show()
	{
		$users = User::all();
		return view('admin.activity.activity',compact(
			'users'
		));
	}

	public function user_search(Request $request)
    {
        $search_content = $request->search_content;

        $response = $this->user_model->user_search_data($search_content);
        // echo "<pre>";
        // print_r($response);
        // exit;
        
    
        

        return response([
                'status' => "success",
                'data'   => $response['data']
        ]);
    }

    public function show_conversation(Request $request)
    {
    	$id = $request->id;
    	$start = $request->start;
        $limit = $request->limit;

        $request_data = [
            'start'           => $start,
            'limit'           => $limit,
            
        ];

    	$response = $this->conver_model->conversation_data_user($request_data, $id);
    	// echo "<pre>";
     //    print_r($response);
     //    exit;
    	

    	return response([
                'status' => "success",
                'data'   => $response['data']
        ]);
    }

    public function user_name(Request $request)
    {
    	$id = $request->id;
    	

    	$user = User::find($id);
    	$response = $user->name;
    	// echo "<pre>";
     //    print_r($response);
     //    exit;

    	return response([
                'status' => "success",
                'data'   => $response
        ]);

    }
}
