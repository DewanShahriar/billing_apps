<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\BdLocation;
use App\ClientInfo;
use App\AccInvoice;
use App\AccAccount;
use App\User;
use App\Support;
use DB;
use PDF;
use Session;

class SupportCornerController extends Controller
{
	public $invoice_model;
    public $support_model;
	public function __construct()
	{
	    $this->middleware('auth');
        $this->invoice_model = new AccInvoice();
        $this->support_model = new Support();
	}


	public function support_reports(){


        $user_district_id = Auth::user()->district_id;

        if($user_district_id !=''){
            $districts        = BdLocation::where('type','2')
                                ->where('id',$user_district_id)
                                ->orderBy('en_name')
                                ->get();   
        }else{
            
           $districts = BdLocation::where('type','2')->orderBy('en_name')->get(); 
        }              
        $upazillas      = BdLocation::where('type','3')->orderBy('en_name')->get();
        $allclients     = ClientInfo::all();

        $users_data     = DB::table('users')
        ->select("id", "name")
        ->where('is_active', '=', 1)
        ->get();

        return view('admin.support_corner.support_reports',compact(
            'districts',
            'upazillas',
            'allclients',
            'users_data'
        ));
    }
    // support  list get
    public function get_support_list(Request $request)
    {

        header("Content-Type: application/json");
        $district_id      = $request->district_id;
        $upazilla_id      = $request->upazilla_id;
        $client_type      = $request->client_type;
        $status           = $request->status;
        $record_id        = $request->record_id;

        $start = $request->start;
        $limit = $request->length;
        $search_content = ($request['search']['value'] != '') ? $request['search']['value'] : false;

        $from_date = (isset($request->from_date)) ? $request->from_date : NULL;

        $to_date = (isset($request->to_date)) ? $request->to_date : NULL;

        $request_data = [
            'start'       => $start,
            'limit'       => $limit,
            'district_id' => $district_id,
            'upazilla_id' => $upazilla_id,
            'client_type' => $client_type,
            'status'      => $status,
            'record_id'   => $record_id,
            'from_date'   => $from_date,
            'to_date'     => $to_date,
        ];

        $response = $this->support_model->support_list_data($request_data, $search_content);
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;
        $response['recordsTotal']    = $count;
        $response['recordsFiltered'] = $count;
        $response['draw']            = $request->draw;
        
        echo json_encode($response);
    }

    
    public function new_ticket()
    {

    	$districts      = BdLocation::where('type','2')->orderBy('en_name')->get();
        $upazillas      = BdLocation::where('type','3')->orderBy('en_name')->get();
        $allclients     = ClientInfo::all();

        return view('admin.support_corner.new_ticket',compact(
            'districts',
            'upazillas',
            'allclients'
        ));
    }

    public function support_save(Request $request)
    {

    	$request->validate([
            'title' => 'required',
            //'attach_file'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $record_id      = $request->client_name_id;
        $client_type_id = $request->client_type;
        $title 			= $request->title;
        $description    = $request->description;
        $attach_file    = $request->attach_file;
        $date_time      = date('d_m_Y_H_i_s');
        $user_id        = Auth::user()->id;

        if ($attach_file !='') {

        	 $fileName = $record_id.$date_time.'.'.$attach_file->extension();  
   
        	 $request->attach_file->move(public_path('assets/images'), $fileName);
        }


        $support_data = [

        		'record_id'    =>$record_id,
        		'client_type'  =>$client_type_id,
        		'title'        =>$title,
        		'description'  =>$description,
        		'attach_file'  => isset($request->attach_file) ? $fileName : null,
        		'created_at'   => date("Y-m-d H:i:s"),
                'created_by'   => $user_id,
                'created_by_ip'=> \Request::ip(),
        ];


        $response = DB::table('support')->insert($support_data);
        if($response)
        {

	    // set session flashdata
      	 session()->flash('success','Successfully Send');
         
         return redirect()->route('support.support_reports')->with('success','Successfully Send');

        }else
        {
        	 // set session flashdata
            session()->flash('error_msg','Something Wrong');

            return redirect()->route('support.support_reports')->with('success','Error');
        }

    }

    public function view_ticket($id)
    {
    	
    	$get_ticket_data = $this->support_model->support_view_data($id);


    	return view('admin.support_corner.view_ticket', compact('get_ticket_data'));
    }

    // assign ticket save
    public function ticket_assign_save(Request $request)
    {
   		//DB::enableQueryLog();

    	$ticket_id = $request->ticket_id;
    	$user_id   = $request->user_id;

    	$response    = DB::table('support')
              			->where('id', $ticket_id)
              			->update(['assign_id' => $user_id]);

         // echo "<pre>"  ;
         // print_r(DB::getQueryLog($response));		

        if($response){
            
    		die(json_encode(["status" => "success", "message" => "Assign  successfuly.", "data" => []]));
    	}

    	die(json_encode(["status" => "error", "message" => "Something Wrong.", "data" => []]));      			
    }

    public function ticket_close_save(Request $request)
    {
        
        $ticket_id         = $request->ticket_id;
        $success_message   = $request->success_message;
        $user_id        = Auth::user()->id;

        $data =[
            'status'=> 1,
            'success_message'=> $success_message,
            'updated_at'     => date("Y-m-d H:i:s"),
            'updated_by'     => $user_id,
            'updated_by_ip'  => \Request::ip(),
        ];


        $response    = DB::table('support')
                        ->where('id', $ticket_id)
                        ->update($data);

        if($response){
            
            die(json_encode(["status" => "success", "message" => "Close  successfuly.", "data" => []]));
        }

        die(json_encode(["status" => "error", "message" => "Something Wrong.", "data" => []]));                 
    }

    public function re_open_ticket(Request $request)
    {
        $ticket_id = $request->ticket_id;

        $response    = DB::table('support')
                        ->where('id', $ticket_id)
                        ->update(['status' => 2]);  

        if($response){
            
            die(json_encode(["status" => "success", "message" => "Re-open  successfuly.", "data" => []]));
        }
        die(json_encode(["status" => "error", "message" => "Something Wrong.", "data" => []]));     
    }

    // remove assign 

    public function remove_assign(Request $request)
    {
         $ticket_id = $request->ticket_id;

        $response    = DB::table('support')
                        ->where('id', $ticket_id)
                        ->update(['assign_id' => NULL]);  

        if($response){
            
            die(json_encode(["status" => "success", "message" => "Remove  successfuly.", "data" => []]));
        }
        die(json_encode(["status" => "error", "message" => "Something Wrong.", "data" => []]));     
    }
}
