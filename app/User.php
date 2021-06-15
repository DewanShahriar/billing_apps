<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use DB;
class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    //public $fillable = ['is_active','datated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'user_name', 'email','mobile','role_id','type', 'created_by','district_id','upazila_id', 'password','is_active','datated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


 /*=== User  Info list data=====*/

    public function user_info_list_data($receive, $search_content)
    {

        DB::enableQueryLog();

        $user_district_id = Auth::user()->district_id;
        $user_upazila_id  = Auth::user()->upazila_id;
        $user_type        = Auth::user()->type; 

        $query = DB::table('users')

            ->select(DB::raw('SQL_CALC_FOUND_ROWS id as user_id'), 'name', 'mobile', 'email', 'user_name','role_id','type','district_id', 'upazila_id','type')

            ->where([
                ['status',    '=', 1],
                ['is_active', '=', 1],
            ])

            ->offset($receive['start'])
            ->limit($receive['limit'])
            ->orderBy('user_id', 'DESC');

        //for searching on page
        if($search_content != false){

            $query->Where("name", "LIKE", $search_content)
                    ->orWhere("mobile", "LIKE", $search_content)
                    ->orWhere("email", "LIKE", $search_content)
                    ->orWhere("user_name", "LIKE", "%".$search_content."%");
        }

        // user session wise get
            if($user_district_id !=''){
                $query->Where("district_id", "=", $user_district_id);
            }
            if($user_upazila_id !=''){
                $query->Where("upazila_id", "=", $user_upazila_id);
            }
            if($user_type !=''){
                $query->Where("type", "=", $user_type);
            }

        $data['data'] = $query->get();
        
        return $data;
    }

    public function user_search_data($search_content)
    {

        DB::enableQueryLog();

        $query = DB::table('users')

            ->select('id', 'name', 'mobile')

            ->where([
                ['status',    '=', 1],
                ['is_active', '=', 1],
            ])

            ->orderBy('name', 'ASC');

        //for searching on page
        if($search_content != false){
            $query->Where("name", "LIKE", "%".$search_content."%");              
        }


        $data['data'] = $query->get();
        
        return $data;
    }
}
