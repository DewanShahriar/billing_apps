<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ClientInfo;
use App\Conversation;
use App\User;
use DB;
Use Alert;

class ConversationController extends Controller
{
    //
    public $client_model;
    public $conver_model;
    public function __construct()
	{
	    $this->middleware('auth');
	    $this->client_model = new ClientInfo();
	    $this->conver_model = new Conversation();
	}

	public function show()
	{
		$clients = ClientInfo::all();
		return view('admin.conversation.conversation',compact(
			'clients'
		));
	}

	public function client_search(Request $request)
    {
        $search_content = $request->search_content;

        $response = $this->client_model->client_search_data($search_content);

        // $data = '';
    
        // foreach($response['data'] as $row){
        //     $data.='<div class="chat-user">
        //                 <div class="chat-user-name">
        //                     <a href="#">'.$row->name.'</a>
        //                 </div>
        //             </div>';                        
        // }
    	
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
    	$record_id = $request->id;
    	$start = $request->start;
        $limit = $request->limit;

        $request_data = [
            'start'           => $start,
            'limit'           => $limit,
            
        ];

    	$response = $this->conver_model->conversation_data($request_data, $record_id);
    	// echo "<pre>";
     //    print_r($response);
     //    exit;
    	

    	return response([
                'status' => "success",
                'data'   => $response['data']
        ]);
    }

    public function conversation_insert(Request $request)
    {
    	$record_id = $request->id;
    	$message = $request->msg;
    	$user_id = Auth::user()->id;

    	$conversation_data = new Conversation;
    	$conversation_data->record_id = $record_id;
    	$conversation_data->message = $message;
    	$conversation_data->created_by = $user_id;
    	$conversation_data->created_by_ip = \Request::ip();
        $start = 0;
        $limit = 20;
    	$conversation_data->save();
        $request_data = [
            'start'           => $start,
            'limit'           => $limit,
            
        ];
    	$response = $this->conver_model->conversation_data($request_data, $record_id);

        $user_name = User::where('id',$user_id)->first();
        $date_time = date('Y-m-d H:i:s');
    	return response([
                'status' => "success",
                'data'   => $user_name->name,
                'date'   => $date_time,
        ]);
    }

    public function client_name(Request $request)
    {
    	$record_id = $request->id;
    	

    	$response = $this->client_model->client_name_data($record_id);
    	// echo "<pre>";
     //    print_r($response);
     //    exit;

    	return response([
                'status' => "success",
                'data'   => $response['data']
        ]);

    }
}
