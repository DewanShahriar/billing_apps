<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\BdLocation;
use App\ClientInfo;
use App\AccInvoice;
use App\AccAccount;
use App\AccVoucher;
use App\Communication;
use App\Fee;
use DB;
use PDF;
Use Alert;

class Process extends Controller
{
   
    public $communication_model;

    public function __construct()
	{  
        $this->communication_model = new Communication();
	}

	//call this function for sending sms
    public function sms()
    {
    	$sms_data   = $this->communication_model->sms_process();
    	$total_sms  = count($sms_data['data']);
    	$total_sent = 0;
    	
    	foreach($sms_data['data'] as $k => $value){

			$destination = "http://quicksmsbd.com/bulksms/index.php/Smsapi?key=E22D742CA7EFF71918EE2B71B289A719&username=billing_apps&mobile=$value->to_addr&msg=" . urlencode($value->message);

			//send this url to _cURL function
			$response = $this->__cURL($destination);
			
			// if successfully sms send and database update
			if($response == 1){
				$total_sent++;

				$fee_update = Communication::where('id',$value->id)->first();
				$fee_update->is_process = 1;
				$fee_update->is_send = 1;
				$fee_update->save();
			} else{
				$fee_update = Communication::where('id',$value->id)->first();
				$fee_update->is_send = 2;
				$fee_update->save();
			}
		}

		die("Total SMS data : $total_sms, Total sent SMS : $total_sent");
    	
    }

    // cURL calling function
	private function __cURL($destination){
		// call cURL
		$ch = curl_init();

		// $data = ['data' => $data, 'sid' => $sid, 'eiin' => $eiin];
		// $data = ['data' => $data];

		curl_setopt($ch, CURLOPT_URL, $destination);
		// curl_setopt($ch, CURLOPT_POST, 1);
		// curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);    // 60 seconds
		// curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$output = curl_exec($ch);

		curl_close($ch);

		return $output;
	}

	//call this function for sending email
	public function email()
	{
		$email_data   = $this->communication_model->email_process();
		try{
			foreach ($email_data['data'] as $key => $data) {
				Mail::to('drahman151055@bscse.uiu.ac.bd')->send(new SendMail($data));
				$fee_update = Communication::where('id',$data->id)->first();
				$fee_update->is_process = 1;
			}
			// Mail::to('drahman151055@bscse.uiu.ac.bd')->send(new SendMail($data));
			die("ok");
			
		} catch(Exception $e){
			die("error");
		}
	}
}
