<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\BdLocation;
use App\ClientInfo;
use App\AccInvoice;
use App\AccAccount;
use App\AccVoucher;
use App\AccTransaction;
use App\Fee;
use App\Communication;
use App\Conversation;
use DB;
use PDF;
Use Alert;

class BillprepareController extends Controller
{
    public $invoice_model;
    public $voucher_model;
	public function __construct()
	{
	    $this->middleware('auth');
        $this->invoice_model = new AccInvoice();
        $this->voucher_model = new AccVoucher();
	}

    //bill generate ui show   
    public function create_bill()
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

    	//$upazillas      = BdLocation::where('type','3')->orderBy('en_name')->get();
        $account_month  = AccAccount::where('type','5')->get();
        $others_account = AccAccount::where('type','6')->get();
    	$allclients     = ClientInfo::all();
    	return view('admin.bill.billprepare',compact(
            'districts',
            'upazillas',
            'allclients',
            'account_month',
            'others_account'
        ));
    }

    //bill generate data insert into database
    public function bill_generate(Request $request){
       
        $user_id     = Auth::user()->id;
        $year        = date("y");
        $month       = date("m");
        $created_at  = date("Y-m-d");
        $record_id   = $request->client_name;
        $discount    = $request->discount;
        
        $total_count = DB::table('acc_invoices')
                                ->count();
        $serial      = $total_count+1;

        $serial_code = $this->invoice_key($serial);

        $invoice_id  = $year.$month.$serial_code;
        
        $month_ids   = $request->month_ids;

        // get account no 

        $get_account     = AccAccount::where('type','1')->first();

        $cash_account    = $get_account->id;

        $discount_acc    = AccAccount::where('type','8')->first();

        $discount_acc_no = $discount_acc->id;

        $client_acc      = AccAccount::where('acc_no',$record_id)->first();

        $client_acc_id   = $client_acc->id;

        if(empty($cash_account))
            die(json_encode(['status' => 'error', 'message' => 'System Error. Please contact with administrator.', 'data'  => []]));

        $invoice_data = [
                'invoice_id'        => $invoice_id,
                'record_id'         => $request->client_name,
                'amount'            => isset($request->amount) ? $request->amount : 0,
                'discount'          => isset($request->discount) ? $request->discount : 0,
                'net_amount'        => $request->total,
                'last_payment_date' => $request->last_payment_date,
                'remark'            => $request->remark,
                'created_at'        => $created_at,
                'created_by'        => $user_id,
                'created_by_ip'     => \Request::ip(),

        ];

        $voucher_data     = [];
        $transaction_data = [];

        if (is_array($month_ids) || is_object($month_ids)){    
            
            foreach($month_ids as $item){

                // voucher data
                $voucher_data[] = [
                    'invoice_id'    => $invoice_id,
                    'item_id'       => $item,
                    'discount'      => isset( $request->discount)  ? $request->discount : 0,
                    'rate'          => isset($request->fee) ? $request->fee : 0,
                    'year'          => $request->year,
                    'created_at'    => $created_at,
                    'created_by'    => $user_id,
                    'created_by_ip' => \Request::ip(),
                ];

                // transaction data
                $transaction_data[] = [
                    'invoice_id'    =>  $invoice_id,
                    'record_id'     =>  $request->client_name,
                    'trans_id'      =>  NULL,
                    'amount'        =>  isset($request->fee) ? $request->fee : 0,
                    'debit'         =>  $item,
                    'credit'        =>  $cash_account,
                    'created_at'    =>  $created_at,
                    'created_by'    =>  $user_id,
                    'created_by_ip' =>  \Request::ip(),
                ];

            }  // foreach end

            // if discount 
             if($discount > 0){

                // transaction data
                $transaction_data[] = [
                    'invoice_id'    =>  $invoice_id,
                    'record_id'     =>  $request->client_name,
                    'trans_id'      =>  NULL,
                    'amount'        =>  $discount,
                    'debit'         =>  $discount_acc_no,
                    'credit'        =>  $client_acc_id,
                    'created_at'    =>  $created_at,
                    'created_by'    =>  $user_id,
                    'created_by_ip' =>  \Request::ip(),
                ];

            }//  discount  end
    
         }   


            // others service calculation
            $others_service_ids   = $request->others_service_ids;
            $others_service_value = $request->others_service_value;

            if (is_array($others_service_ids) || is_object($others_service_ids)){

                foreach($others_service_ids as $k => $item){

                    // voucher data
                     $voucher_data[] = [
                        'invoice_id'    => $invoice_id,
                        'item_id'       => $item,
                        'discount'      => 0,
                        'rate'          => isset($others_service_value[$k]) ? $others_service_value[$k] : 0,
                        'year'          => $request->year,
                        'created_at'    =>  $created_at,
                        'created_by'    => $user_id,
                        'created_by_ip' => \Request::ip(),
                    ];

                     $transaction_data[] = [
                            'invoice_id'    =>  $invoice_id,
                            'record_id'     =>  $request->client_name,
                            'trans_id'      =>  NULL,
                            'amount'        =>  isset($others_service_value[$k]) ? $others_service_value[$k] : 0,
                            'debit'         =>  $item,
                            'credit'        =>  $cash_account,
                            'created_at'    =>  $created_at,
                            'created_by'    =>  $user_id,
                            'created_by_ip' =>  \Request::ip(),
                        ];  
                }

            }


        // balance
        $debit_amount  = DB::table('acc_transactions')
                        ->select(DB::raw('SUM(amount) AS total_amount'))
                        ->where('record_id', '=', $request->client_name)
                        ->where('is_active', '=', 1)
                        ->whereNull('trans_id')
                        ->first();

        $credit_amount  = DB::table('acc_transactions')
                        ->select( DB::raw('SUM(amount) AS total_amount'))
                        ->where('record_id', '=', $request->client_name)
                        ->where('is_active', '=', 1)
                        ->whereNotNull('trans_id')
                        ->first();


         $total_debit  = $debit_amount->total_amount;                
         $total_credit = $credit_amount->total_amount; 

         if($total_credit > $total_debit){  // if advance balance

            // generate transaction ID
            $total_count    = DB::table('acc_transactions')
                                ->where('is_active', '=', 1)
                                ->whereYear('created_at', date("Y"))
                                ->groupBy('trans_id')
                                ->count();

            $serial_no         = $total_count + 1;
           

            $serial_code    = str_repeat("0", (4 - strlen($serial_no))) . $serial_no;

            $trans_id       = $year.$serial_code; 

            $advance = ($credit_amount->total_amount - $debit_amount->total_amount );

            // if invoice amount less than or equal balance
            if($invoice_data['net_amount'] <= $advance){

                $invoice_data['is_paid'] = 1;
                $invoice_data['updated_at'] = $created_at;
                $invoice_data['updated_by'] = $invoice_data['created_by'];
                $invoice_data['updated_by_ip'] = \Request::ip();

                
            }
            
                // transaction data
                $transaction_data[] = [
                    'record_id'             =>  $invoice_data['record_id'],
                    'invoice_id'            =>  $invoice_data['invoice_id'],
                    'trans_id'              =>  $trans_id,
                    'amount'                =>  $invoice_data['net_amount'],
                    'debit'                 =>  $cash_account,
                    'credit'                =>  $client_acc_id,
                    'created_at'            =>  $created_at,
                    'created_by'            =>  $user_id,
                    'created_by_ip'         =>  \Request::ip(),
                ];

            }

        $conversation_data = new Conversation;
    	$conversation_data->record_id = $record_id;
    	$conversation_data->message = 'Bill generated';
    	$conversation_data->created_at = date('Y-m-d H:i:s');
    	$conversation_data->created_by = $user_id;
    	$conversation_data->created_by_ip = \Request::ip();
    	$conversation_data->save();

            // echo "<pre>";
            // print_r($transaction_data);
            // exit;

        // DB transactions Start 

        DB::beginTransaction();

        try {
            
            $acc_invoices_store    = DB::table('acc_invoices')->insert($invoice_data);

            
            $acc_vouchers_store    = DB::table('acc_vouchers')->insert($voucher_data); 

            $acc_transaction_store = DB::table('acc_transactions')->insert($transaction_data);  

            
            if($acc_invoices_store){
                echo json_encode(['status' => 'success', 'message' => 'Successfully Genarated', 'data' => []]);
            }else{
                echo json_encode(['status' => 'error', 'message' => 'Something Wrong', 'data' => []]);
            }
            
             
                DB::commit();
                // all good
        } catch (\Exception $e) {
                DB::rollback();
                echo json_encode(['status' => 'error', 'message' => 'Database error', 'data' => []]);
                  
                  echo $e;
        }

        // DB transactions end 

   }
    
    public function invoice_key($x)
    {
            if(strlen($x)<=1){
                return '000'.$x;
            }
            else if(strlen($x)<=2){
                return '00'.$x;
            }
            else if(strlen($x)<=3){
                return '0'.$x;
            }
            else if(strlen($x)<=4){
                return $x;
            }
        }

    //client type select than show client
    public function ajax_client(Request $request)
    {
        $type = $request->type;
        $upa = $request->upa;
        $clients = ClientInfo::where('client_type',$type)->where('upazilla_id',$upa)->get();

        $data = '';
        $data.='<option value="" selected>Select</option>';
        foreach($clients as $row){
            $data.='<option value='.$row->record_id.'>'.$row->name.'</option>';                        
        }

        return response([
                'status' => "success",
                'data'   => $data
        ]);
    }

    //bill list ui show
    public function bill_show()
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

                                
        return view('admin.bill.billlist',compact(
            'districts'
        ));
    }

    //all bill show from database
    public function bill_list_show(Request $request)
    {

        header("Content-Type: application/json");
        $start    = $request->start;
        $limit    = $request->length;
        $dis_id   = $request->dis_id;
        $upa_id   = $request->upa_id;
        $cli_type = $request->cli_type;
        $cli_id   = $request->cli_id;
        $status   = $request->status;

        $search_content = ($request['search']['value'] != '') ? $request['search']['value'] : false;

        $request_data = [
            'dis_id'     => $dis_id,
            'upa_id'     => $upa_id,
            'cli_type'   => $cli_type,
            'cli_id'     => $cli_id,
            'status'     => $status,
            'start'      => $start,
            'limit'      => $limit,
            
        ];

        $response = $this->invoice_model->bill_list_data($request_data, $search_content);

        $response['draw']            = $request->draw;
        
        echo json_encode($response);
    }

    //call from delete button delete invoice and vouchers
    public function bill_delete(Request $request)
    {
        $id = $request->id;
        $invoice           = AccInvoice::where('invoice_id',$id)->first();;
        $user_id           = Auth::user()->id;

        // echo "<pre>";
        // print_r($invoice->record_id);
        // exit;

        $conversation_data = new Conversation;
        $conversation_data->record_id  = $invoice->record_id;
        $conversation_data->message    = 'Bill deleted';
        $conversation_data->created_by = $user_id;
        $conversation_data->created_by_ip = \Request::ip();
        $conversation_data->save();

        AccInvoice::where('invoice_id', '=', $id)->update(['is_active' => 0]);
        
        AccVoucher::where('invoice_id', '=', $id)->update(['is_active' => 0]);

        AccTransaction::where('invoice_id', '=', $id)->update(['is_active' => 0]);

        echo json_encode([
            'status'  => "success",
            'message' => "Successfully Deleted.",
            'data'    => []
        ]);

    }

    //invoice view in pdf
    public function bill_view($id)
    {

        $invoice_data      = AccInvoice::where('invoice_id',$id)->first();

        $voucher_data      = $this->voucher_model->voucher_details_list($id);
        
        $client_details    = ClientInfo::where('record_id',$invoice_data->record_id)->first();

        $fee_data          = Fee::where('record_id',$invoice_data->record_id)->first();

        $pdf = PDF::loadView('admin.invoice.invoice', [
            'invoice_data'   => $invoice_data, 
            'client_details' => $client_details, 
            'voucher_data'   => $voucher_data,
            'fee_data'       => $fee_data
        ]);

        return $pdf->stream('invoice.pdf');

    }

    //get client unit fee for bill generate  
    public function get_fee_info(Request $request){

        $record_id = $request->record_id;  
        $fee_data  = Fee::where('record_id', $record_id)->first();
        
        if($record_id == 'none'){
            $fee = 0;
        } else {
            $fee = $fee_data->fee_amount;
        }

        $quantity  = $fee_data->quantity;
        $total_fee = $fee*$quantity;
        $fee_info  = '';

        $fee_info.='<input name="fee" id="fee" value='.$total_fee.' class="form-control fee_amount" disabled>';

        return response([
            'status' => "success",
            'data'   => $fee_info,
        ]);

    }

    //get monthly fee of a client
    public function get_monthly_fee_info(Request $request)
    {
        $all_month  = AccAccount::where('type','5')->get();  
    
        $request_data = [
            'record_id'=> $request->record_id,
            'year'     => $request->year
        ];
        
        $get_fee_info = $this->invoice_model->get_monthly_fee_info($request_data);
        
        return response([
                'status' => "success",
                'data'   => $get_fee_info,
        ]);

    }

    //call from sms button in  bill list. insert into communicaton table 
    public function sms_save(Request $request)
    {

        $invoice_id = $request->id;
        $user_id    = Auth::user()->id;

        $commu_data                = new Communication;
        $commu_data->record_id     = $request->cli_id;
        $commu_data->to_addr       = $request->cli_mo;
        $commu_data->title         = 'Invoice SMS';
        $commu_data->message       = $request->msg_body;
        $commu_data->type          = 1;
        $commu_data->sending_time  = date('Y-m-d H:i:s');
        $commu_data->created_by    = $user_id;
        $commu_data->created_by_ip = \Request::ip();

        $conversation_data = new Conversation;
        $conversation_data->record_id = $request->cli_id;
        $conversation_data->message   = 'Invoice SMS sent';
        $conversation_data->created_by = $user_id;
        $conversation_data->created_by_ip = \Request::ip();

        $commu_data->save();
        $conversation_data->save();

        echo json_encode([
            'status'  => "success",
            'message' => "Successfully Sms Sent.",
            'data'    => []
        ]);

    }

    //call from email button in  bill list. insert into communicaton table 
    public function email_save(Request $request)
    {

        $invoice_id = $request->id;
        $user_id    = Auth::user()->id;

        $email_data                = new Communication;
        $email_data->record_id     = $request->cli_id;
        $email_data->to_addr       = $request->cli_email;
        $email_data->title         = 'Your bill is ready';
        $email_data->message       = $request->msg_body;
        $email_data->type          = 2;
        $email_data->attachment    = $invoice_id;
        $email_data->sending_time  = date('Y-m-d H:i:s');
        $email_data->created_by    = $user_id;
        $email_data->created_by_ip = \Request::ip(); 

        $conversation_data = new Conversation;
        $conversation_data->record_id = $request->cli_id;
        $conversation_data->message   = 'Invoice Email sent';
        $conversation_data->created_by = $user_id;
        $conversation_data->created_by_ip = \Request::ip();

        $email_data->save();
        $conversation_data->save();

        echo json_encode([
            'status'  => "success",
            'message' => "Successfully email Sent.",
            'data'    => []
        ]);
    }

    public function bill_update($id)
    {
        

        
            
        $districts = BdLocation::where('type','2')->orderBy('en_name')->get(); 
        
        //$upazillas      = BdLocation::where('type','3')->orderBy('en_name')->get();
        $account_month  = AccAccount::where('type','5')->get();
        $others_account = AccAccount::where('type','6')->get();
        // $allclients     = ClientInfo::all();
        return view('admin.bill.billupdate',compact(
            'districts',
            // 'allclients',
            'account_month',
            'others_account'
        ));

           
    }

}
