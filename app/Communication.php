<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;
class Communication extends Model
{
    //all sms, email search list
    public function report_search_data($received, $search_content)
    {
        DB::enableQueryLog();
        
        $user_district_id = Auth::user()->district_id;
        $user_upazila_id  = Auth::user()->upazila_id;
        $user_type        = Auth::user()->type;
        $query = DB::table('communications AS COM')

            ->join('client_infos AS CLINF', function($join){
                $join->on('CLINF.record_id', '=', 'COM.record_id');
            })

            ->select(DB::raw('SQL_CALC_FOUND_ROWS COM.id'), 'CLINF.name','CLINF.mobile_no', 'COM.title', 'COM.message','COM.type' , 'COM.sending_time' , 'COM.is_send','COM.is_process','COM.to_addr')
        
            ->orderBy('COM.id', 'DESC');

            // custom search by district,upazilla,client,sms,email,sent,failed,unsent
            if($search_content != false){
            $query->Where("CLINF.name", "LIKE", "%".$search_content."%");              
            }
            if($received['dis_id'] !=''){
                $query->Where("CLINF.district_id", "=", $received['dis_id']);
            }
            if($received['upa_id'] !=''){
                $query->Where("CLINF.upazilla_id", "=", $received['upa_id']);
            }
            if($received['cli_type'] !=''){
                $query->Where("CLINF.client_type", "=", $received['cli_type']);
            }
            if($received['cli_id'] !=''){
                $query->Where("CLINF.record_id", "=", $received['cli_id']);
            }
            if($received['com_type'] !=''){
                $query->Where("COM.type", "=", $received['com_type']);
            }
            if($received['status'] !=''){
                $query->Where("COM.is_send", "=", $received['status']);
            }
            if($received['custom'] !=''){
                $query->Where("CLINF.record_id", "=", 200001);
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
            

            // offset limit add
            $query->offset($received['offset'])->limit($received['limit']);
        
        $data['data'] = $query->get();

        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;
        
        $data['recordsTotal']    = $count;
        $data['recordsFiltered'] = $count;

        return $data;
    }

    //select 10 unsent sms data 
    public function sms_process()
    {
        DB::enableQueryLog();

        $query = DB::table('communications AS COM')

            ->select('COM.id','COM.to_addr','COM.message')

            ->where([
                ['COM.is_process', '=', 0],
                ['COM.type', '=', 1],
            ])
            
            ->limit(10)
            ->orderBy('COM.id', 'ASC');

        
        $data['data'] = $query->get();

        return $data;
    }

    // select 1 unsent email data
    public function email_process()
    {
        DB::enableQueryLog();

        $query = DB::table('communications AS COM')

            ->select('COM.id','COM.to_addr','COM.message')

            ->where([
                ['COM.is_process', '=', 0],
                ['COM.type', '=', 2],
            ])
            
            ->limit(1)
            ->orderBy('COM.id', 'ASC');
  
        $data['data'] = $query->get();

        return $data;
    }
}
