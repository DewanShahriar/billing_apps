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

class ReportController extends Controller
{
    public function daily_collection()
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

        return view('admin.report.daily_collection',compact(
            'districts',
            'upazillas',
            'allclients'
        ));
    }

    public function daily_collection_report(Request $request)
    {
        $district_id    = $request->district_name;
        $upazila_id     = $request->upazilla_name;
        $client_type_id = $request->client_type;
        $record_id      = $request->client_name_id;
        $date           = date("Y-m-d");

        $transaction_info = DB::table("acc_transactions AS TRANS")
                                    
                            ->join('client_infos AS CLINF', function($join){
                                $join->on('TRANS.record_id', '=', 'CLINF.record_id');
                            })
                            
                            ->join('bd_locations AS BDDIS', function($join){
                                $join->on('CLINF.district_id', '=', 'BDDIS.id');
                            })

                            ->join('bd_locations AS BDUPZ', function($join){
                                $join->on('CLINF.upazilla_id', '=', 'BDUPZ.id');
                            })

                            ->select('TRANS.invoice_id', 'TRANS.record_id','TRANS.trans_id','TRANS.payment_date', 'TRANS.amount', 'TRANS.remark', 'CLINF.name', 'CLINF.mobile_no', 'CLINF.email','BDDIS.en_name as district_name', 'BDUPZ.en_name as upazilla_name','CLINF.client_type', 'CLINF.record_id')
                            
                            ->where([
                            ['TRANS.is_active', '=', 1],
                            ['TRANS.trans_id', '!=', NULL],
                            ['TRANS.payment_date', '=', $date]
                             ])
                            ->groupBy('TRANS.trans_id')
                            ->orderBy('TRANS.id', 'DESC');


                            if($district_id !=''){
                                $transaction_info->Where("CLINF.district_id", "=", $district_id);
                            }
                            if($upazila_id !=''){
                                $transaction_info->Where("CLINF.upazilla_id", "=", $upazila_id);
                            }
                            if($client_type_id !=''){
                                $transaction_info->Where("CLINF.client_type", "=", $client_type_id);
                            }

                             if($record_id !=''){
                                $transaction_info->Where("CLINF.record_id", "=", $record_id);
                            }

                         $data  = $transaction_info->get();


        // echo "<pre>";
        // print_r($data);exit;

        $pdf = PDF::loadView('admin.report.daily_collection_report', [
            'data'   => $data,
        
        ]);

        return $pdf->stream('daily_collection_report.pdf');
    }

    public function monthly_collection()
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
        $month_id       = date('m');

        return view('admin.report.monthly_collection',compact(
            'districts',
            'upazillas',
            'allclients'
        ));
    }

    public function monthly_collection_report(Request $request)
    {
        DB::enableQueryLog();
        
        $district_id    = $request->district_name;
        $upazila_id     = $request->upazilla_name;
        $client_type_id = $request->client_type;
        $record_id      = $request->client_name_id;
        $monthly_id     = $request->monthly_id;

        $transaction_info = DB::table("acc_transactions AS TRANS")
                                    
                            ->join('client_infos AS CLINF', function($join){
                                $join->on('TRANS.record_id', '=', 'CLINF.record_id');
                            })
                            
                            ->join('bd_locations AS BDDIS', function($join){
                                $join->on('CLINF.district_id', '=', 'BDDIS.id');
                            })

                            ->join('bd_locations AS BDUPZ', function($join){
                                $join->on('CLINF.upazilla_id', '=', 'BDUPZ.id');
                            })

                            ->select('TRANS.invoice_id', 'TRANS.record_id','TRANS.trans_id', 'TRANS.amount', 'TRANS.remark', 'TRANS.payment_date', 'CLINF.name', 'CLINF.mobile_no', 'CLINF.email','BDDIS.en_name as district_name', 'BDUPZ.en_name as upazilla_name','CLINF.client_type', 'CLINF.record_id')
                            
                            ->where([
                            ['TRANS.is_active', '=', 1],
                            ['TRANS.trans_id', '!=', NULL]
                             ])
                            ->whereMonth("TRANS.payment_date", "=", $monthly_id)
                            ->groupBy('TRANS.trans_id')
                            ->orderBy('TRANS.id', 'DESC');


                            if($district_id !=''){
                                $transaction_info->Where("CLINF.district_id", "=", $district_id);
                            }
                            if($upazila_id !=''){
                                $transaction_info->Where("CLINF.upazilla_id", "=", $upazila_id);
                            }
                            if($client_type_id !=''){
                                $transaction_info->Where("CLINF.client_type", "=", $client_type_id);
                            }

                             if($record_id !=''){
                                $transaction_info->Where("CLINF.record_id", "=", $record_id);
                            }

                         $data  = $transaction_info->get();



        //echo "<pre>";
       // print_r(DB::getQueryLog($data));exit;
        //print_r($data);exit;

        $pdf = PDF::loadView('admin.report.monthly_collection_report', [
            'data'         => $data,
            'monthly_id'   => $monthly_id,
            'district_id'  => $district_id,
            'upazila_id'   => $upazila_id,
        
        ]);

        return $pdf->stream('monthly_collection_report.pdf');
    }


    public function accounts_wise_collection()
    {
        $cash_account   = AccAccount::where('type','1')->first();
        $bkash_account  = AccAccount::where('type','2')->first();
        $rocket_account = AccAccount::where('type','3')->first();
        $bank_account   = AccAccount::where('type','4')->first();

        return view('admin.report.accounts_wise_collection',compact(
            'cash_account',
            'bkash_account',
            'bank_account',
            'rocket_account'
        ));
    }

    public function accounts_report(Request $request){

        $account_id   = $request->account_id;
        $from_date    = $request->from_date;
        $to_date      = $request->to_date;
        $get_acc      = AccAccount::where('id', $account_id)->first();
        $account_name = $get_acc->name;


            $transaction_info = DB::table("acc_transactions AS TRANS")
                                
                        ->join('client_infos AS CLINF', function($join){
                            $join->on('TRANS.record_id', '=', 'CLINF.record_id');
                        })

                        ->select('TRANS.invoice_id', 'TRANS.record_id','TRANS.trans_id', 'TRANS.amount', 'TRANS.payment_method_account', 'TRANS.payment_date', 'CLINF.name', 'CLINF.mobile_no', 'CLINF.email','CLINF.client_type', 'CLINF.record_id')
                        
                        ->where([
                        ['TRANS.is_active', '=', 1],
                        ['TRANS.debit', '=', $account_id],
                        ['TRANS.trans_id', '!=', NULL]
                         ])
                        ->whereBetween("payment_date", [$from_date, $to_date ])
                        ->groupBy('TRANS.trans_id')
                        ->orderBy('TRANS.id', 'DESC');


            $data  = $transaction_info->get();

                        // echo "<pre>";
                        // print_r($data);

            $pdf = PDF::loadView('admin.report.accounts_report', [
                'data'         => $data,
                'account_name' => $account_name
            
        ]);

        return $pdf->stream('accounts_report.pdf');
                      

    }
}
