<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\BdLocation;
use App\ClientInfo;
use App\AccAccount;
use App\Conversation;
use App\Fee;
use App\User;
use DB;
use PDF;
use Session;
Use Alert;

class ClientController extends Controller
{
    public $client_model;
    public function __construct()
    {
        $this->middleware('auth');
        $this->client_model = new ClientInfo();
    }

    //client add ui show
    public function create()
    {

    	$user_district_id = Auth::user()->district_id;

        if($user_district_id !=''){
            $districts        = BdLocation::where('type','2')
                                ->where('id',$user_district_id)
                                ->orderBy('en_name')
                                ->get();   
        }else{
            
           $districts = BdLocation::where('type','2')->orderBy('en_name')->get(); 
        }
    	$upazillas = BdLocation::where('type','3')->orderBy('en_name')->get();

    	return view('admin.addclient',compact(
            'districts',
            'upazillas'
        ));
    }

    //fetch all upazilla from database
    public function show_upazilla(Request $request)
    {

        $user_upazila_id = Auth::user()->upazila_id;

        if($user_upazila_id !=''){
            $upazilla        = BdLocation::where('type','3')
                                ->where('id',$user_upazila_id)
                                ->orderBy('en_name')
                                ->get();
        }else{
        	$id       = $request->id;
        	$upazilla = BdLocation::where('parent_id',$id)->orderBy('en_name')->get();
        }
       

    	$data     = '';

    	$data.='<option value="" selected>Select</option>';

    	foreach($upazilla as $row){
            $data.='<option value='.$row->id.'>'.$row->en_name.'</option>';
        }

        return response([
        		'status' => "success",
        		'data'   => $data
        ]);

    }

    //client list ui show
    public function client_show()
    {
        $user_district_id = Auth::user()->district_id;

        if($user_district_id !=''){
            $districts        = BdLocation::where('type','2')
                                ->where('id',$user_district_id)
                                ->orderBy('en_name')
                                ->get();   
        }else{
            
           $districts = BdLocation::where('type','2')->orderBy('en_name')->get(); 
        }
        

        return view('admin.clientstatus',compact('districts'));
    }

    //client all informations
    public function client_data_show(Request $request)
    {

        header("Content-Type: application/json");

        $start    = $request->start;
        $limit    = $request->length;
        $dis_id   = $request->dis_id;
        $upa_id   = $request->upa_id;
        $cli_type = $request->cli_type;
        $status   = $request->status;


        $search_content = ($request['search']['value'] != '') ? $request['search']['value'] : false;

        $request_data = [
            'dis_id'          => $dis_id,
            'upa_id'          => $upa_id,
            'cli_type'        => $cli_type,
            'status'          => $status,
            'start'           => $start,
            'limit'           => $limit,
            
        ];

        $response = $this->client_model->client_info_list_data($request_data, $search_content);

        
        $response['draw']            = $request->draw;
        
        echo json_encode($response);
    }

    //client data update ui show
    public function client_update($id)
    {

        $districts = BdLocation::where('type','2')->get();
        $upazillas = BdLocation::where('type','3')->get();
        $unions    = BdLocation::where('type','4')->get();
        $client    = ClientInfo::where('id',$id)->first();
        $record_id = $client->record_id;
        $fee       = Fee::where('record_id',$record_id)->first();

        return view('admin.update.clientupdate',compact(
            'client',
            'districts',
            'upazillas',
            'unions',
            'fee'
        ));   
    }

    //client profile ui show
    public function client_profile($id)
    {
        $districts = BdLocation::where('type','2')->get();
        $upazillas = BdLocation::where('type','3')->get();
        $client    = ClientInfo::where('id',$id)->first();
        $fee       = Fee::where('record_id',$client->record_id)->first();

        return view('admin.profile.clientprofile',compact(
            'client',
            'districts',
            'upazillas',
            'fee'
        )); 
    }

    //client data insert into database
    public function client_insert(Request $request)
    {
        $this->validate($request, [
            'name'          => 'required',
            'district_id'   => 'required',
            'upazilla_id'   => 'required',
            'mobile_no'     => 'required',
            'email'         => 'required',
            'weblink'       => 'required',
            'address'       => 'required',
            'client_type'   => 'required',
            'service_date'  => 'required',
            'fee_amount'    => 'required',
            'quantity'      => 'required',
            'payment_type'  => 'required',
            'domain_expire' => 'required' 
        ]);
        // record id generate
        $count   = ClientInfo::get()->count();
        $count++;
        $y       = date('y');
        $a       = $request->input('client_type');
        $inid    = str_pad($count,4,'0',STR_PAD_LEFT);
        $record_id     = $a.$y.$inid;
        $user_id = Auth::user()->id;
        
        // data for clientinfo table
        $client_data                = new ClientInfo;
        $client_data->name          = $request->name;
        $client_data->record_id     = $record_id;
        $client_data->district_id   = $request->district_id;
        $client_data->upazilla_id   = $request->upazilla_id;
        $client_data->code          = $request->code;
        $client_data->mobile_no     = $request->mobile_no;
        $client_data->email         = $request->email;
        $client_data->weblink       = $request->weblink;
        $client_data->address       = $request->address;
        $client_data->client_type   = $request->client_type;
        $client_data->union_id      =  isset($request->union_id) ? $request->union_id : NULL;
        $client_data->service_date  = $request->service_date;
        $client_data->domain_expire = $request->domain_expire;
        $client_data->created_by    = $user_id;
        $client_data->created_by_ip = \Request::ip();




        // data for fee table
        $fee_data                   = new Fee;
        $fee_data->record_id        = $record_id;
        $fee_data->fee_amount       = $request->fee_amount;
        $fee_data->quantity         = $request->quantity;
        $fee_data->payment_type     = $request->payment_type;
        $fee_data->created_by       = $user_id;
        $fee_data->created_by_ip    = \Request::ip();

        // data for account table
        $acc_data                   = new AccAccount;
        $acc_data->acc_no           = $record_id;
        $acc_data->name             = $record_id;
        $acc_data->type             = 7;
        $acc_data->created_by       = $user_id;
        $acc_data->created_by_ip    = \Request::ip();

        $conversation_data = new Conversation;
        $conversation_data->record_id = $record_id;
        $conversation_data->message   = 'Client created';
        $conversation_data->created_by = $user_id;
        $conversation_data->created_by_ip = \Request::ip();

        

        try{
            // data transection insert into database 3 table
            DB::transaction(function () use ($client_data, $fee_data,$acc_data,$conversation_data){
                $client_data->save();
                $fee_data->save(); 
                $acc_data->save();  
                $conversation_data->save(); 
            });
            // flash message notification
            session()->flash('notif','Successfully inserted');
            return redirect()->route('client.client_show')->with('success','Successfully inserted');
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            //dd($ex->getMessage());

            // flash message notification
            session()->flash('error_msg','Please select required informations');

            return redirect()->route('client.create')->with('success','Error');
        }      
    }

    //after update client data insert into database
    public function client_edit(Request $request)
    {

        $this->validate($request, [
            'name'          => 'required',
            'district_id'   => 'required',
            'upazilla_id'   => 'required',
            'mobile_no'     => 'required',
            'email'         => 'required',
            'weblink'       => 'required',
            'address'       => 'required',
            'client_type'   => 'required',
            'service_date'  => 'required',
            'fee_amount'    => 'required',
            'quantity'      => 'required',
            'payment_type'  => 'required',
            'status'        => 'required'
        ]);

        // client table update
        $user_id                      = Auth::user()->id;
        $client_update                = ClientInfo::find($request->client_id);
        $client_update->name          = $request->name;
        $client_update->district_id   = $request->district_id;
        $client_update->upazilla_id   = $request->upazilla_id;
        $client_update->code          = $request->code;
        $client_update->mobile_no     = $request->mobile_no;
        $client_update->email         = $request->email;
        $client_update->weblink       = $request->weblink;
        $client_update->address       = $request->address;
        $client_update->client_type   = $request->client_type;
        $client_update->union_id      = isset($request->union_id) ? $request->union_id : NULL;
        $client_update->service_date  = $request->service_date;
        $client_update->domain_expire = $request->domain_expire;
        $client_update->status        = $request->status;
        $client_update->updated_by    = $user_id;
        $client_update->updated_by_ip = \Request::ip();


        // fee table update
        $fee_update                   = Fee::where('record_id',$request->record_id)->first();
        $fee_update->fee_amount       = $request->fee_amount;
        $fee_update->payment_type     = $request->payment_type;
        $fee_update->quantity         = $request->quantity;
        $fee_update->updated_by       = $user_id;
        $fee_update->updated_by_ip    = \Request::ip();

        $conversation_data = new Conversation;
        $conversation_data->record_id = $request->record_id;
        $conversation_data->message   = 'Client informations updated';
        $conversation_data->created_by = $user_id;
        $conversation_data->created_by_ip = \Request::ip();

        // data table transaction
        DB::transaction(function () use ($client_update, $fee_update, $conversation_data) {
            $client_update->save();
            $fee_update->save();
            $conversation_data->save();
        });

        // update message notification
        session()->flash('updates','Successfully updated');

        return redirect()->route('client.client_show')->with('success','inserted');
    }

    //for client delete
    public function client_delete(Request $request)
    {

        $id                = $request->id;
        $user_id           = Auth::user()->id;
        $client            = ClientInfo::find($id);
        $client->is_active = 0;

        $conversation_data = new Conversation;
        $conversation_data->record_id  = $client->record_id;
        $conversation_data->message    = 'Client deleted';
        $conversation_data->created_by = $user_id;
        $conversation_data->created_by_ip = \Request::ip();

        $client->save();
        $conversation_data->save();

        echo json_encode([
            'status'  => "success",
            'message' => "Successfully Deleted.",
            'data'    => []
        ]);
        
    }

    public function client_data_pdf(Request $request)
    {

        header("Content-Type: application/json");

        // $dis_id   = $request->dis_id;
        $dis_id   = $request->district_name;
        $upa_id   = $request->upazilla_name;
        $cli_type = $request->client_type;
        $status   = $request->status;
        
        // echo "<pre>";
        // print_r($status);
        // exit;
        $request_data = [
            'dis_id'     => $dis_id,
            'upa_id'     => $upa_id,
            'cli_type'   => $cli_type,
            'status'     => $status,
        ];
        
        $response = $this->client_model->client_info_list_pdf($request_data);
        // echo "<pre>";
        // print_r($response);
        // exit;
        $district = BdLocation::where('id',$dis_id)->get();
        $upazilla = BdLocation::where('id',$upa_id)->get();
        // echo "<pre>";
        // print_r($district);
        // exit;

        $pdf = PDF::loadView('admin.clientpdf', [
            'data'     => $response,
            'district' => $district,
            'upazilla' => $upazilla
            
        ]);

        return $pdf->stream('client_list.pdf');
        // echo json_encode($response);
    }


        //get_union_list type select than show client
    public function get_union_list(Request $request)
    {
        $type = $request->type;
        $upa = $request->upa;
        $clients = BdLocation::where('parent_id',$upa)->get();
        // echo "<pre>";
        // print_r($clients);

        $data = '';
        $data.='<option value="" selected>Select</option>';
        foreach($clients as $row){
            $data.='<option value='.$row->id.'>'.$row->en_name.'</option>';                        
        }

        return response([
                'status' => "success",
                'data'   => $data
        ]);
    }
}
