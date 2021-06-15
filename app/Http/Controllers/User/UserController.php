<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\BdLocation;
use Session;
use App\User;
use DB;

class UserController extends Controller
{
    public $user_model;

	public function __construct()
	{
	    $this->middleware('auth');
        $this->user_model = new User();
	}

	public function showRegistrationForm()
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
		return view('users.registration', compact(
            'districts',
            'upazillas'
        ));
	}

	public function userSave(Request $request)
	{
		$this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        return redirect('user-list');
	}
	
	protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'          => ['required', 'string', 'max:255'],
            'mobile'        => ['required', 'string','min:11'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'user_name'     => ['required', 'string', 'min:4', 'unique:users'],
            'role_id'       => ['required'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        $user_id = Auth::user()->id;

        return User::create([
            'name'         => $data['name'],
            'user_name'    => $data['user_name'],
            'email'        => $data['email'],
            'mobile'       => $data['mobile'],
            'role_id'      => $data['role_id'],
            'district_id'  => isset($data['district_name']) ? $data['district_name'] : NULL,
            'upazila_id'   => isset($data['upazilla_name']) ? $data['upazilla_name'] : NULL,
            'type'         => isset($data['type']) ? $data['type'] : NULL,
            'is_active'    => 1,
            'status'       => 1,
            'created_by'   => $user_id,
            'created_by_ip'=> \Request::ip(),
            'password'     => Hash::make($data['password']),
        ]);
    }


    public function user_edit($id)
    {
    	$user_info      = User::where('id', $id)->first();

        $user_district_id = Auth::user()->district_id;
        $user_upazila_id  = Auth::user()->upazila_id;

        if($user_district_id !=''){
            $districts        = BdLocation::where('type','2')
                                ->where('id',$user_district_id)
                                ->orderBy('en_name')
                                ->get();   
        }else{
            
           $districts = BdLocation::where('type','2')->orderBy('en_name')->get(); 
        }            

        $upazillas      = BdLocation::where('type','3')->orderBy('en_name')->get();

    	return view('users.user_edit',[
            'user_info'=>$user_info,
            'districts'=>$districts,
            'upazillas'=>$upazillas,
        ]);
    }

    public function userUpdate(Request $request)
    {
    	 $request->validate([
	        'name'          => 'required',
	        'mobile'        => 'required',
	        'email'         => 'required',
	        'role_id'       => 'required',
	        'user_name'     => 'required',

       		]);

       $user_id  = Auth::user()->id;
       $id      = $request->id;
        

      if (trim($request->password) == '' || trim($request->password) ==NULL) {
            $data = [

            'name'         => $request->name,
            'mobile'       => $request->mobile,
            'email'        => $request->email,
            'user_name'    => $request->user_name,
            'district_id'  => isset($request->district_name) ? $request->district_name : NULL,
            'upazila_id'   => isset($request->upazilla_name) ? $request->upazilla_name : NULL,
            'type'         => isset($request->type) ?  $request->type : NULL,
            'role_id'      => $request->role_id,
            'updated_by'   => $user_id,
            'updated_by_ip'=> \Request::ip(),
       ];
    
        }
        else {
             $data = [

            'name'         => $request->name,
            'mobile'       => $request->mobile,
            'email'        => $request->email,
            'user_name'    => $request->user_name,
            'district_id'  => isset($request->district_name) ? $request->district_name : NULL,
            'upazila_id'   => isset($request->upazilla_name) ? $request->upazilla_name : NULL,
            'type'         => isset($request->type) ?  $request->type : NULL,
            'role_id'      => $request->role_id,
            'password'     => Hash::make($request->password),
            'updated_by'   => $user_id,
            'updated_by_ip'=> \Request::ip(),
        ];
        }

        $response = DB::table('users')
                        ->where('id', $id)
                        ->update($data);
      
    	if($response)
    	{
            session()->flash('success','Successfully Update');
            return redirect()->route('user-list')->with('success','Successfully Update');
    	}else{
            session()->flash('error_msg','Please select required informations');

            return redirect()->route('user-list')->with('error_msg','Error');
        }
    }

    public function user_delete(Request $request)
    {


    	$id =  $request->user_id;

        // $data = [
        //     'is_active' =>0,
        //     'datated_at' => Carbon::now(),
        // ];
    
        $data = [ 'is_active' => 0, 'deleted_at' => Carbon::now()];

    	 $deleted = DB::table('users')
                        ->where('id', $id)
                        ->update($data);
    	if($deleted){
            
    		die(json_encode(["status" => "success", "message" => "User deleted successfuly.", "data" => []]));
    	}

    	die(json_encode(["status" => "error", "message" => "User deleted successfuly.", "data" => []]));

    }

    public function user_list()
    {
    	// $users_data = DB::table('users')
     //    ->where('is_active', '=', 1)
     //    ->get();
   
    	return view('users.user_list');
    }

    /*======== User list data ==========*/
    public function user_data(Request $request){

        header("Content-Type: application/json");


        $start = $request->start;
        $limit = $request->length;

        $search_content = ($request['search']['value'] != '') ? $request['search']['value'] : false;


        $from_date = (isset($request->from_date)) ? $request->from_date : date('Y-m-d');

        $to_date = (isset($request->to_date)) ? $request->to_date : date('Y-m-d');

        $request_data = [
            'start' => $start,
            'limit' => $limit,
            'from_date' => $from_date,
            'to_date' => $to_date,
        ];



        // $user_list = new User();

        $response = $this->user_model->user_info_list_data($request_data, $search_content);

        $count = DB::select("SELECT FOUND_ROWS() as `row_count`")[0]->row_count;

        $response['recordsTotal']    = $count;
        $response['recordsFiltered'] = $count;



        $response['draw'] = $request->draw;


        echo json_encode($response);


    }


    public function get_username(Request $request)
    {
    	$user_name = $request->user_name;
    	
    	$users_count = DB::table('users')
        ->where('user_name', '=', $user_name)
        ->where('is_active', '=', 1)
        ->where('status', '=', 1)
        ->count();
        if($users_count >0){
        	echo 1; exit();
        }
    }


    /*================= Change password  =============*/
    public function change_password()
    {
        return view('users.change_password');
    }


    public function password_save(Request $request)
    {
        $request->validate([
            'password' => 'required',
            ]);

        $change_password = User::find($request->id);

        $change_password->password = Hash::make($request->password);

        $change_password->save();

        if($change_password){
            return redirect('user-list');
         }

    }

}
