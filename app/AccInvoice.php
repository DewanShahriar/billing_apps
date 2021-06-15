<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;
class AccInvoice extends Model
{
    //all bill list from database 
    public function bill_list_data($receive, $search_content)
    {
        //DB::enableQueryLog();
        $user_district_id = Auth::user()->district_id;
        $user_upazila_id  = Auth::user()->upazila_id;
        $user_type        = Auth::user()->type;

        $query = DB::table('acc_invoices AS INV')

            ->join('client_infos AS CLINF', function($join){
                $join->on('CLINF.record_id', '=', 'INV.record_id');
           
             })

            ->join('acc_vouchers AS VCR', function($join){
                $join->on('VCR.invoice_id', '=', 'INV.invoice_id');
            })

            ->join('acc_accounts AS ACC', function($join){
                $join->on('ACC.acc_no', '=', 'VCR.item_id');
            })

            
            ->select(DB::raw('SQL_CALC_FOUND_ROWS INV.id'), 'INV.invoice_id', 'INV.record_id', 'INV.net_amount', 'INV.discount','INV.is_paid','INV.is_active','INV.created_at','INV.last_payment_date', 'CLINF.name', 'CLINF.mobile_no', 'CLINF.email', DB::raw('GROUP_CONCAT(ACC.name) AS names'))

            ->where([
                ['VCR.is_active', '=', 1],
                ['INV.is_active', '=', 1],
            ])
            

            ->offset($receive['start'])
            ->limit($receive['limit'])
            ->groupBy('INV.id')
            ->orderBy('INV.id', 'DESC');

        if($search_content != false){
            $query->Where("CLINF.name", "LIKE", "%".$search_content."%");              
        }

        // user session wise get
        if($user_district_id !=''){
            $query->Where("CLINF.district_id", "=", $user_district_id);
        }
        if($user_upazila_id !=''){
            $query->Where("CLINF.upazilla_id", "=", $user_upazila_id);
        }
        if($user_type !=''){
            $query->Where("CLINF.client_type", "=", $user_type);
        }


        $data['data'] = $query->get();

        $count    = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        $data['recordsTotal']    = $count;
        $data['recordsFiltered'] = $count;

        return $data;

    }

    public function get_monthly_fee_info($receive){
        DB::enableQueryLog();

        $record_id = $receive['record_id'];
        $year_id = $receive['year'];
        // echo $year;exit();
        $query = DB::table("acc_invoices AS INV")
      
            ->join("acc_vouchers AS VCR", function($join) use($year_id){
                $join->on('VCR.invoice_id', '=', 'INV.invoice_id')
                ->where([
                     ['VCR.is_active', '=', 1],
                     ['VCR.year', '=', $year_id]
                 ]);
            })

            ->join("acc_accounts AS ACC", function($join){
                $join->on('ACC.acc_no', '=', 'VCR.item_id')
                 ->where([
                     ['ACC.is_active', '=', 1],
                     ['ACC.type', '=', 5]
                 ]);
            })

            ->select('INV.invoice_id', 'INV.is_paid','VCR.item_id','VCR.rate', 'VCR.year', 'ACC.name', 'ACC.acc_no')

            ->where([
                ['INV.is_active','=',1],
                ['INV.record_id','=',$record_id]
            ]);

        $data['data']= $query->get();

        return $data['data'];
    }

    public function get_month_name($inv_id)
    {
        DB::enableQueryLog();

        $query = DB::table('acc_invoices AS INV')

            ->join('acc_vouchers AS VCR', function($join){
                $join->on('INV.invoice_id', '=', 'VCR.invoice_id');
            })

            ->join('client_infos AS CLI', function($join){
                $join->on('INV.record_id', '=', 'CLI.record_id');
            })

            ->join('acc_accounts AS ACC', function($join){
                $join->on('VCR.item_id', '=', 'ACC.acc_no');
            })

            
            ->select('INV.net_amount','INV.created_at','VCR.rate','VCR.year','ACC.name','CLI.mobile_no','CLI.name','CLI.record_id')

            ->where([
                ['INV.is_active', '=', 1],
                ['VCR.invoice_id', '=', $inv_id]
            ]);

            $all_data['data'] = $query->get();

        return $all_data;
        
    }

    // Monwy Receipt list
public function money_receipt_list_data($receive, $search_content)
    {
        //DB::enableQueryLog();
        $user_district_id = Auth::user()->district_id;
        $user_upazila_id  = Auth::user()->upazila_id;
        $user_type        = Auth::user()->type;

        $query = DB::table('acc_transactions AS TRANS')

            ->join('client_infos AS CLINF', function($join){
                $join->on('CLINF.record_id', '=', 'TRANS.record_id');
            })
            
            ->select(DB::raw('SQL_CALC_FOUND_ROWS TRANS.id'), 'TRANS.invoice_id', 'TRANS.record_id','TRANS.trans_id','TRANS.payment_date', 'CLINF.name', 'CLINF.mobile_no', 'CLINF.email', DB::raw('SUM(TRANS.amount) AS total_amount'))

            ->where([
                ['TRANS.is_active', '=', 1],
                ['TRANS.trans_id', '!=', NULL],
            ])
            

            ->offset($receive['start'])
            ->limit($receive['limit'])
            ->groupBy('TRANS.trans_id')
            ->orderBy('TRANS.id', 'DESC');

            if($receive['district_id'] !=''){
                $query->Where("CLINF.district_id", "=", $receive['district_id']);
            }
            if($receive['upazilla_id'] !=''){
                $query->Where("CLINF.upazilla_id", "=", $receive['upazilla_id']);
            }
            if($receive['client_type'] !=''){
                $query->Where("CLINF.client_type", "=", $receive['client_type']);
            }

            if($receive['record_id'] !=''){
                $query->Where("CLINF.record_id", "=", $receive['record_id']);
            }
            // user session wise get
            if($user_district_id!=''){
                $query->Where("CLINF.district_id", "=", $user_district_id);
            }
            if($user_upazila_id!=''){
                $query->Where("CLINF.upazilla_id", "=", $user_upazila_id);
            }
            if($user_type!=''){
                $query->Where("CLINF.client_type", "=", $user_type);
            }
                
        //for searching on page
        if($search_content != false){

            $query->Where("CLINF.name", "LIKE", $search_content)
                    ->orWhere("CLINF.mobile_no", "LIKE", $search_content)
                    ->orWhere("CLINF.email", "LIKE", $search_content)
                    ->orWhere("TRANS.payment_date", "LIKE", "%".$search_content."%");
        }

        $data['data'] = $query->get();

        return $data;

    }


}
