<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\BdLocation;
use App\ClientInfo;
use App\AccInvoice;
use App\AccAccount;
use App\AccVoucher;
use App\Fee;
use App\Communication;
use DB;
Use Alert;

class CommunicationController extends Controller
{
	public $communication_model;

	public function __construct()
	{
	    $this->middleware('auth');
        $this->communication_model = new Communication();
	}

    //report ui show 
    public function report_show()
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
        $upazillas      = BdLocation::where('type','3')->orderBy('en_name')->get();
        $allclients     = ClientInfo::all();
        return view('admin.communication.report',compact(
            'districts',
            'upazillas',
            'allclients'
        ));
    }

    //quicksms ui show
    public function quicksms_show()
    {
        $allclients     = ClientInfo::all();
        return view('admin.communication.quicksms',compact('allclients'));
    }

    //call from report search button
    public function search_report_list_show(Request $request)
    {
        header("Content-Type: application/json");
        $dis_id   = $request->dis_id;
        $upa_id   = $request->upa_id;
        $cli_type = $request->cli_type;
        $cli_id   = $request->cli_id;
        $com_type = $request->com_type;
        $status   = $request->status;
        $custom   = $request->custom;

        $search_content = ($request['search']['value'] != '') ? $request['search']['value'] : false;

        $request_data = [
            'dis_id'     => $dis_id,
            'upa_id'     => $upa_id,
            'cli_type'   => $cli_type,
            'cli_id'     => $cli_id,
            'com_type'   => $com_type,
            'custom'     => $custom,
            'status'     => $status,
            'limit'      => $request->length,
            'offset'     => $request->start
        ];
        
        
        $response = $this->communication_model->report_search_data($request_data, $search_content);

        $response['draw'] = $request->draw;
        
        echo json_encode($response);
        
    }

    //for quicksms all client show in autoselect dropdown
    public function quicksms_insert(Request $request)
    {
        $user_id    = Auth::user()->id;
        $value = $request->value;
        $message = $request->message;
        
        $insert_data = [];
        $conver_data = [];
        foreach($value as $item){
            
            $str_arr = explode (",", $item);
            $len = count($str_arr);
            //for custom number split
            if($len>1){
                $mobile = $str_arr[0];
                $record_id = $str_arr[1];
            } else{
                $mobile = $str_arr[0];
                $record_id = 200001;
            }

            
            $insert_data[] = [
                'record_id'     => $record_id,
                'to_addr'       => $mobile,
                'title'         => 'Quick sms',
                'message'       => $message,
                'type'          => 1,
                'sending_time'  => date('Y-m-d H:i:s'),
                'created_at'    => date('Y-m-d H:i:s'),
                'created_by'    => $user_id,
                'created_by_ip' => \Request::ip(),
            ];

            $conver_data[] = [
                'record_id'     => $record_id,
                'message'       => 'Quick sms sent',
                'created_at'    => date('Y-m-d H:i:s'),
                'created_by'    => $user_id,
                'created_by_ip' => \Request::ip(),
            ];
            
        }
        // echo "<pre>";
        // print_r($insert_data);
        // exit;

        
        try {

            $store    = DB::table('communications')->insert($insert_data);

            $conver   = DB::table('conversations')->insert($conver_data);

            if($store){
                return response([
                    'status' => "success",
                    'msg' => "Successfully sent.",
                    'data'   => []
                ]);
            }else {
                return response([
                    'status' => "error",
                    'msg' => "Not sent",
                    'data'   => []
                ]);
            }
            
             
                // DB::commit();
                // all good
        } catch(Exception $e) {
            return response([
                'status' => "Database error",
                'msg' => "Database error.",
                'data'   => []
            ]);
                
        }
        
        // return response([
        //         'status' => "success",
        //         'data'   => []
        // ]);
    }
}
