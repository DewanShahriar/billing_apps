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
use App\User;
use App\Fee;
use DB;
use PDF;
Use Alert;

class BillpaymentController extends Controller
{

	public $invoice_model;
    public $voucher_model;
	public function __construct()
	{
	    $this->middleware('auth');
        $this->invoice_model = new AccInvoice();
        $this->voucher_model = new AccVoucher();
	}
    
    public function payment(){

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
        $account_month  = AccAccount::where('type','5')->get();
        $others_account = AccAccount::where('type','6')->get();
        $cash_account   = AccAccount::where('type','1')->first();
        $bkash_account  = AccAccount::where('type','2')->first();
        $rocket_account = AccAccount::where('type','3')->first();
        $bank_account   = AccAccount::where('type','4')->first();
    	$allclients     = ClientInfo::all();
    	return view('admin.bill.billpayment',compact(
            'districts',
            'upazillas',
            'allclients',
            'account_month',
            'others_account',
            'cash_account',
            'bkash_account',
            'rocket_account',
            'bank_account'
        ));
    }
    public function get_payment_fee_info(Request $request){
        DB::enableQueryLog();

    	$record_id = $request->record_id;
        $year_id   = $request->year;
            
        $acc_client_id    = AccAccount::where('acc_no',$record_id)->first();
        $client_p_id      = $acc_client_id->id; 
                 

            $voucher_info = DB::table("acc_vouchers AS VCR")

                ->join("acc_invoices AS INV", function($join) use($record_id){
                $join->on('INV.invoice_id', '=', 'VCR.invoice_id')
                ->where([
                     ['INV.is_active', '=', 1],
                     // ['INV.is_paid', '=', 0],
                     ['INV.record_id', '=', $record_id]
                 ]);
                })

                ->join("acc_accounts AS ACC", function($join) use($record_id) {
                $join->on('ACC.id', '=', 'VCR.item_id')
                 ->where([
                     ['ACC.is_active', '=', 1],
                     //['ACC.acc_no', '=', $record_id]
                 ]);
            })
            ->join("client_infos AS CLNT", function($join) use($record_id){
            $join->on('CLNT.record_id', '=', 'INV.record_id')
            ->where([
                 ['CLNT.is_active', '=', 1],
                 ['CLNT.record_id', '=', $record_id]
             ]);
            })
            
             ->select('INV.is_paid', 'VCR.item_id', 'VCR.year','ACC.name', 'CLNT.name as record_name', 'CLNT.address as record_address')
                ->where([
                    ['VCR.is_active','=',1],
                    ['VCR.year','=',$year_id]
                ]);

             $voucher_data = $voucher_info->get();

            if(isset($voucher_data[0])){
                $client_info = [
                    "record_name"    => $voucher_data[0]->record_name,
                    "record_address" => $voucher_data[0]->record_address,
                    "client_p_id" => $client_p_id,
                ];
            }

            // get debit credit balance
            $debit_amount  = DB::table('acc_transactions')
                        ->select(DB::raw('SUM(amount) AS total_amount'))
                        ->where('record_id', '=', $record_id)
                        ->where('is_active', '=', 1)
                        ->where('credit', '!=',$client_p_id)
                        ->whereNull('trans_id')
                        ->first();

                

            $credit_amount = DB::table('acc_transactions')
                        ->select( DB::raw('SUM(amount) AS total_amount'))
                        ->where('record_id', '=', $record_id)
                        ->where('credit', '=', $client_p_id)
                        ->where('is_active', '=', 1)
                       // ->whereNotNull('trans_id')
                        ->first();


            // get due or advance balance
            $due_or_advance = ($debit_amount->total_amount - $credit_amount->total_amount);

            
             return response([
                'status' => "success",
                'data'  => [
                    "client_info"    => $client_info,
                    "voucher_info"   => $voucher_data,
                    "total_due"      => $due_or_advance
                ]
        	]);
   
	}

    public function bill_payment(Request $request){

        DB::enableQueryLog();

        $record_id      = $request->client_name; 
        $client_acc_id  = $request->client_primary_id; 
        $payment_method = $request->payment_method; 
        $method_acc     = $request->payment_method_name; 
        $year           = $request->year;
        $total          = $request->total_due_amount;
        $payment        = $request->total;
        $pay_amount     = $request->total;
        $due_or_advance = $request->rest_of_due;
        $payment_date   = $request->payment_date;
        $remark         = $request->remark;
        
        $get_account    = AccAccount::where('type','1')->first();
        $cash_account   = $get_account->id;
 

        $user_id        = Auth::user()->id;
        $current_year   = date("y");
        $created_at     = date("Y-m-d");
        
        $total_count    = DB::table('acc_transactions')
                                ->where('is_active', '=', 1)
                                ->whereYear('created_at', date("Y"))
                                ->groupBy('trans_id')
                                ->count();

        $serial         = $total_count + 1;
       

        $serial_code    = $this->invoice_key($serial);

        $trans_id       = $current_year.$serial_code; 


        $invoice_info   = DB::table('acc_invoices')
                            ->select('record_id','invoice_id', 'amount', 'discount')
                            ->where('record_id', '=', $record_id)
                            ->where('is_active', '=', 1)
                            ->where('is_paid', '=', 0)
                            ->get();
        
     
        $invoice_ids    = array_column(json_decode(json_encode($invoice_info)), "invoice_id");

       

        $prev_payment = DB::table("acc_transactions")
                            ->select("invoice_id", DB::raw('SUM(amount) AS pay_amount'))
                            ->whereIn("invoice_id", $invoice_ids)
                            ->where("credit", "=", $client_acc_id)
                            // ->whereNotNull("trans_id")
                            ->groupBy("invoice_id")
                            ->get();

        $prev_payment_list = array_column(json_decode(json_encode($prev_payment)), "pay_amount", "invoice_id");



        $invoice_data     = [];
        $transaction_data = [];

        foreach ($invoice_info as $value) {
            
            // if given balance finish
            if($payment <= 0)
                break;

            // if found prev payment
            if(isset($prev_payment_list[$value->invoice_id])){

                $value->amount -= $prev_payment_list[$value->invoice_id];
            }

            if($value->amount <= $payment){

                $payment -= $value->amount;
                
                //  invoice data
                $invoice_data[] = [
                    'invoice_id' => $value->invoice_id,
                    'is_paid' => 1
                ];


                // transaction data
                $transaction_data[] = [
                    'record_id'             =>  $record_id,
                    'invoice_id'            =>  $value->invoice_id,
                    'trans_id'              =>  $trans_id,
                    'amount'                =>  $value->amount,
                    'debit'                 =>  $payment_method,
                    'credit'                =>  $client_acc_id,
                    'payment_method'        =>  $payment_method,
                    'payment_method_account'=>  isset($method_acc) ? $method_acc : NULL,
                    'created_at'            =>  $created_at,
                    'remark'                =>  $remark,
                    'payment_date'          =>  $payment_date,
                    'created_by'            =>  $user_id,
                    'created_by_ip'         =>  \Request::ip(),
                ];

            } else {
                // transaction data
                $transaction_data[] = [
                    'record_id'             =>  $record_id,
                    'invoice_id'            =>  $value->invoice_id,
                    'trans_id'              =>  $trans_id,
                    'amount'                =>  $payment,
                    'debit'                 =>  $payment_method ,
                    'credit'                =>  $client_acc_id,
                    'payment_method'        =>  $payment_method,
                    'payment_method_account'=>  isset($method_acc) ? $method_acc : NULL,
                    'remark'                =>  $remark,
                    'payment_date'          =>  $payment_date,
                    'created_at'            =>  $created_at,
                    'created_by'            =>  $user_id,
                    'created_by_ip'         =>  \Request::ip(),
                ];

                // payment make zero
                $payment = 0;
            }


        }
        // for advance 
        if($payment > 0){

             $transaction_data[] = [
                    'record_id'             =>  $record_id,
                    'invoice_id'            =>  NULL,
                    'trans_id'              =>  $trans_id,
                    'amount'                =>  $payment,
                    'debit'                 =>  $payment_method,
                    'credit'                =>  $client_acc_id,
                    'payment_method'        =>  $payment_method,
                    'payment_method_account'=>  isset($method_acc) ? $method_acc : NULL,
                    'remark'                =>  $remark,
                    'payment_date'          =>  $payment_date,
                    'created_at'            =>  $created_at,
                    'created_by'            =>  $user_id,
                    'created_by_ip'         =>  \Request::ip(),
                ];

            
        }

            
        // Transaction start 
        DB::beginTransaction();

        try {
            
            foreach($invoice_data as $item){
                DB::table('acc_invoices')
                     ->where('invoice_id', $item['invoice_id'])
                     ->update($item);
            }
            

            DB::table('acc_transactions')
                ->insert($transaction_data);   
         
            DB::commit();

            // all good
            die(json_encode(['status' => 'success', 'message' => 'Successfully Payment', 'data' => []]));

        } catch (\Exception $e) {
            DB::rollback();
            echo "<pre>";
            echo $e;
            
            die(json_encode(['status' => 'error', 'message' => 'Fail to complete payment. Try again.', 'data' => []]));
        }
        // Transaction end 
                       
    
    }


    // invoice key generate 
    public function invoice_key($serial_no, $length = 4)
    {
        return str_repeat("0", ($length - strlen($serial_no))) . $serial_no;
    }

    // money receipt list 
    public function money_receipt_list(){


        $user_district_id = Auth::user()->district_id;
        $districts = BdLocation::where('type','2')
                                ->where('id',$user_district_id)
                                ->orderBy('en_name')
                                ->get();
                                
        $upazillas      = BdLocation::where('type','3')->orderBy('en_name')->get();
        $allclients     = ClientInfo::all();
        return view('admin.bill.money_receipt_list',compact(
            'districts',
            'upazillas',
            'allclients'
        ));
    }

        // Bill Payment money receipt list get
    public function money_receipt_list_show(Request $request)
    {

        header("Content-Type: application/json");
        $district_id      = $request->district_id;
        $upazilla_id      = $request->upazilla_id;
        $client_type      = $request->client_type;
        $record_id        = $request->record_id;

        $start = $request->start;
        $limit = $request->length;
        $search_content = ($request['search']['value'] != '') ? $request['search']['value'] : false;

        $from_date = (isset($request->from_date)) ? $request->from_date : date('Y-m-d');

        $to_date = (isset($request->to_date)) ? $request->to_date : date('Y-m-d');

        $request_data = [
            'start'       => $start,
            'limit'       => $limit,
            'district_id' => $district_id,
            'upazilla_id' => $upazilla_id,
            'client_type' => $client_type,
            'record_id'   => $record_id,
            'from_date'   => $from_date,
            'to_date'     => $to_date,
        ];

        $response = $this->invoice_model->money_receipt_list_data($request_data, $search_content);
        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;
        $response['recordsTotal']    = $count;
        $response['recordsFiltered'] = $count;
        $response['draw']            = $request->draw;
        
        echo json_encode($response);
    }


    // money_receipt view
    public function money_receipt_view($id){
       

         //$transaction_data  = AccTransaction::where('trans_id',$id)->whereNotNull('trans_id')->first();

        $transaction_data= DB::table("acc_transactions")
                                    ->select('record_id', 'trans_id', 'payment_date','remark', 'created_by','created_at', DB::raw("SUM(amount) as total_amount "))
                                    ->whereNotNull("trans_id")
                                    ->where('trans_id', '=', $id)
                                    ->first();


        $client_details    = ClientInfo::where('record_id',$transaction_data->record_id)->first();


        $user_data         = User::where('id',$transaction_data->created_by)->first();

        $pdf = PDF::loadView('admin.money_receipt.money_receipt', [
            'client_details'   => $client_details,
            'transaction_data' => $transaction_data,
            'user_data'        => $user_data,
        ]);

        return $pdf->stream('money_receipt.pdf');

    }

}

