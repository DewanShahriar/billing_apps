<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class AccVoucher extends Model
{
    //voucher data return to invoice view
    public function voucher_details_list($id)
    {
    	DB::enableQueryLog();

        $query = DB::table('acc_vouchers AS VCR')

            ->join('acc_accounts AS ACC', function($join){
                $join->on('ACC.acc_no', '=', 'VCR.item_id');
            }) 
            
            ->select('VCR.rate','VCR.year','ACC.name','ACC.acc_no')

            ->where([
                ['VCR.is_active', '=', 1],
                ['VCR.invoice_id', '=', $id]
            ]);
        
        $data['data'] = $query->get();

        return $data;

    }
}
