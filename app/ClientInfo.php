<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;
class ClientInfo extends Model
{
    //client list data fetch from database
    public function client_info_list_data($received, $search_content)
    {
        DB::enableQueryLog();
        $user_id          = Auth::user()->id;
        $user_type        = Auth::user()->type;
        $user_district_id = Auth::user()->district_id;
        $user_upazila_id  = Auth::user()->upazila_id;

        $query = DB::table('client_infos AS CLINF')

            ->join('bd_locations AS BDDIS', function($join){
                                $join->on('CLINF.district_id', '=', 'BDDIS.id');
                            })


            ->join('bd_locations AS BDUPZ', function($join){
                                $join->on('CLINF.upazilla_id', '=', 'BDUPZ.id');
                            })    

            ->select(DB::raw('SQL_CALC_FOUND_ROWS CLINF.id'), 'CLINF.name', 'CLINF.mobile_no', 'CLINF.email', 'CLINF.weblink','CLINF.client_type','CLINF.status','CLINF.domain_expire','BDDIS.en_name as district_name', 'BDUPZ.en_name as upazilla_name')

            ->where(function($query) use($user_district_id,$user_upazila_id,$user_type)                {
                        
                        if ($user_district_id =='' && $user_upazila_id =='' && $user_type==''){ $query
                              ->where([
                                         ['CLINF.is_active', '=', 1],
                                     ]);
                    
                          }
                })

            ->offset($received['start'])
            ->limit($received['limit'])
            ->orderBy('id', 'DESC');

        //for searching on page
        if($search_content != false){
            $query->Where("name", "LIKE", "%".$search_content."%");              
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
        if($received['status'] !=''){
            $query->Where("CLINF.status", "=", $received['status']);
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

    public function client_info_list_pdf($received)
    {
        DB::enableQueryLog();

        $query = DB::table('client_infos AS CLI')

            ->join('fees AS FEE', function($join){
                $join->on('CLI.record_id', '=', 'FEE.record_id');
            })

            ->join('bd_locations AS BDL', function($join){
                $join->on('CLI.upazilla_id', '=', 'BDL.id');
            })

            ->join('bd_locations AS BDLD', function($join){
                $join->on('CLI.district_id', '=', 'BDLD.id');
            })

            ->select('CLI.name', 'CLI.mobile_no', 'CLI.email', 'CLI.weblink','CLI.client_type','CLI.status','CLI.domain_expire','BDL.en_name AS en_name','BDLD.en_name AS district_name','FEE.fee_amount')

            ->where([
                ['CLI.is_active', '=', 1],
                ['CLI.client_type', '!=', 4]
            ])

            
            ->orderBy('CLI.id', 'DESC');

        //for searching on page
        
        if($received['dis_id'] !=''){
            $query->Where("district_id", "=", $received['dis_id']);
        }
        if($received['upa_id'] !=''){
            $query->Where("upazilla_id", "=", $received['upa_id']);
        }
        if($received['cli_type'] !=''){
            $query->Where("client_type", "=", $received['cli_type']);
        }
        if($received['status'] !=''){
            $query->Where("status", "=", $received['status']);
        }

        $data['data'] = $query->get();
        
        return $data;
    }

    public function client_search_data($search_content)
    {
        DB::enableQueryLog();

        $query = DB::table('client_infos')

            ->select('name', 'mobile_no', 'email', 'weblink','client_type','status','domain_expire','record_id')

            ->where([
                ['is_active', '=', 1],
                ['client_type', '!=', 4]
            ])

            ->orderBy('name', 'ASC');

        //for searching on page
        if($search_content != false){
            $query->Where("name", "LIKE", "%".$search_content."%");              
        }
        

        $data['data'] = $query->get();
        
        return $data;
    }

    public function client_name_data($record_id)
    {
        DB::enableQueryLog();

        $query = DB::table('client_infos AS CLINF')

            ->join('bd_locations AS BDDIS', function($join){
                                $join->on('CLINF.district_id', '=', 'BDDIS.id');
                            })

            ->join('bd_locations AS BDUPZ', function($join){
                                $join->on('CLINF.upazilla_id', '=', 'BDUPZ.id');
                            })    

            ->select('CLINF.name', 'BDDIS.en_name as district_name', 'BDUPZ.en_name as upazilla_name')

            ->where([
                
                ['CLINF.record_id', '=', $record_id]
            ])

            ->orderBy('CLINF.name', 'ASC');

        $data['data'] = $query->first();
        
        return $data;
    }
}
