<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;

class Support extends Model
{
    

    // support list
public function support_list_data($receive, $search_content)
    {
        DB::enableQueryLog();
    
        $user_district_id = Auth::user()->district_id;
        $user_upazila_id  = Auth::user()->upazila_id;
        $user_type        = Auth::user()->type;   

        $query = DB::table('support AS SUPRT')

            ->join('client_infos AS CLINF', function($join){
                $join->on('CLINF.record_id', '=', 'SUPRT.record_id');
            })

            ->leftJoin('users AS USR', function($join){
                $join->on('USR.id', '=', 'SUPRT.assign_id');
            })
            
            ->select(DB::raw('SQL_CALC_FOUND_ROWS SUPRT.id'), 'SUPRT.record_id','SUPRT.client_type','SUPRT.title', 'SUPRT.description', 'SUPRT.assign_id', 'SUPRT.status', 'SUPRT.created_at', 'CLINF.name','CLINF.mobile_no', 'CLINF.email', 'USR.name as assign_name')

            ->where([
                ['SUPRT.is_active', '=', 1],
            ])
            

            ->offset($receive['start'])
            ->limit($receive['limit'])
            ->orderBy('SUPRT.id', 'DESC');

             if($receive['district_id'] !=''){
                $query->Where("CLINF.district_id", "=", $receive['district_id']);
            }
            if($receive['upazilla_id'] !=''){
                $query->Where("CLINF.upazilla_id", "=", $receive['upazilla_id']);
            }
            if($receive['client_type'] !=''){
                $query->Where("CLINF.client_type", "=", $receive['client_type']); 
            }
             if($receive['status'] !=''){
                $query->Where("SUPRT.status", "=", $receive['status']);
            }
            if($receive['from_date'] !='' || $receive['to_date'] !=''){
                $query->whereBetween("SUPRT.created_at", [$receive['from_date'], $receive['to_date'] ]);
            }

             if($receive['record_id'] !=''){
                $query->Where("CLINF.record_id", "=", $receive['record_id']);
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
            
        //for searching on page
        if($search_content != false){

            $query->Where("CLINF.name", "LIKE", $search_content)
                    ->orWhere("CLINF.mobile_no", "LIKE", $search_content)
                    ->orWhere("CLINF.email", "LIKE", $search_content)
                    ->orWhere("SUPRT.created_at", "LIKE", "%".$search_content."%");
        }

        $data['data'] = $query->get();

        return $data;

    }

    public function support_view_data($id)
    {
        return $query = DB::table('support AS SUPRT')

            ->join('client_infos AS CLINF', function($join){
                $join->on('CLINF.record_id', '=', 'SUPRT.record_id');
            })
            ->join('bd_locations AS BDDIS', function($join){
                $join->on('CLINF.district_id', '=', 'BDDIS.id');
            })

            ->join('bd_locations AS BDUPZ', function($join){
                $join->on('CLINF.upazilla_id', '=', 'BDUPZ.id');
            })

            
            ->select('SUPRT.id','SUPRT.record_id','SUPRT.client_type','SUPRT.title', 'SUPRT.description','SUPRT.attach_file', 'SUPRT.assign_id', 'SUPRT.status', 'SUPRT.created_at', 'CLINF.name','CLINF.mobile_no', 'CLINF.email','BDDIS.en_name as district_name', 'BDUPZ.en_name as upazilla_name')

            ->where([
                ['SUPRT.is_active', '=', 1],
                ['SUPRT.id', '=', $id],
            ])
            ->first();
    }
}
