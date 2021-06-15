<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Conversation extends Model
{
    public function conversation_data($received, $record_id)
    {
    	DB::enableQueryLog();

        $query = DB::table('conversations AS CNV')

            ->join('client_infos AS CLI', function($join){
                $join->on('CNV.record_id', '=', 'CLI.record_id');
            })

            ->join('users AS USR', function($join){
                $join->on('CNV.created_by', '=', 'USR.id');
            })

            ->select('CLI.name AS clientname',  'CNV.message','CNV.created_at','USR.name')

            ->where([
                
                ['CNV.record_id', '=', $record_id],
            ])

            ->offset($received['start'])
            ->limit($received['limit'])
            ->groupBy('CNV.id')
            ->orderBy('CNV.created_at', 'DESC');
        
        

        $data['data'] = $query->get();
        
        return $data;
    }

    public function conversation_data_user($received, $id)
    {
        DB::enableQueryLog();
        
        $query = DB::table('conversations AS CNV')

            ->join('client_infos AS CLI', function($join){
                $join->on('CNV.record_id', '=', 'CLI.record_id');
            })

            ->join('users AS USR', function($join){
                $join->on('CNV.created_by', '=', 'USR.id');
            })
            

            ->select('CLI.name AS clientname',  'CNV.message','CNV.created_at','USR.name')

            ->where([
                ['USR.id', '=', $id],
            ])

            ->offset($received['start'])
            ->limit($received['limit'])
            ->groupBy('CNV.id')
            ->orderBy('CNV.created_at', 'DESC');
        
    
        $data['data'] = $query->get();
        // echo "<pre>";
        // print_r($data);
        // exit;
        return $data;
    }

    
}
