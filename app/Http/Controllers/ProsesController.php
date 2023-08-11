<?php

namespace App\Http\Controllers;
use DB;
use App\User;
use App\Finger;
use App\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\FingerController;
use App\Http\Controllers\DeviceController;

class ProsesController extends Controller
{
    var $base_path  = 'http://127.0.0.1:8000/api/';

    function register(Request $request) {

        $user_id = $request['user_id'];
        $time_limit_reg = 15;

	    echo "$user_id;SecurityKey;".$time_limit_reg.";".$this->base_path."process_register;".$this->base_path."getac";
    }

    function verification(Request $request) {

        $user_id = $request['user_id'];
        $fc         = new FingerController();
        $finger		= $fc->getUserFinger($user_id);
        $time_limit_ver = 10;

        if(sizeof($finger) == 0)
            echo "kosong";
        else
            echo $user_id.";".$finger[0]->finger_data.";SecurityKey;".$time_limit_ver.";".$this->base_path."process_verification;".$this->base_path."getac".";extraParams";

    }

    function processRegister(Request $request){

        if($request['RegTemp'] == NULL){
            $response = [
                'status'    => 'F',
                'message'   => 'Error to scan param null.',
                'data'      => ""
            ];

            return response()->json($response, 200);
        }

        $data 		= explode(";",$request['RegTemp']);
		$vStamp 	= $data[0];
		$sn 		= $data[1];
		$user_id	= $data[2];
		$regTemp 	= $data[3];

        $dc = new DeviceController();
		$device = $dc->getDeviceBySn($sn);

		$salt = md5($device[0]->ac.$device[0]->vkey.$regTemp.$sn.$user_id);

		if (strtoupper($vStamp) == strtoupper($salt)) {
            $finger = DB::table('finger')->where('user_id', $user_id)->get();

			if (sizeof($finger) == 0) {
                $finger = new Finger();
                $finger->user_id        = $user_id;
                $finger->finger_id      = 1;
                $finger->finger_data    = $regTemp;

                try{
                    $result	= $finger->save();
                    if ($result) {
                        $response = [
                            'status'    => 'T',
                            'message'   => 'Success.',
                            'data'      => [$finger]
                        ];
                    } else {
                        $response = [
                            'status'    => 'F',
                            'message'   => 'Success.',
                            'data'      => []
                        ];
                    }
                }
                catch(\Illuminate\Database\QueryException $e){
                    $response = [
                        'status'    => 'F',
                        'message'   => $e,
                        'data'      => []
                    ];
                }
			} else {
				$response = [
                    'status'    => 'F',
                    'message'   => '"Template Already Exist.".',
                    'data'      => []
                ];
			}
		} else {
			$response = [
                'status'    => 'F',
                'message'   => 'Parameter Invalid.',
                'data'      => []
            ];
		}

        return response()->json($response, 200);
    }


    function processVerification (Request $request) {

        if($request['VerPas'] == NULL){
            $response = [
                'status'    => 'F',
                'message'   => 'Error to scan param null.',
                'data'      => ""
            ];

            return response()->json($response, 200);
        }

        $data 		= explode(";",$request['VerPas']);
        $user_id	= $data[0];
        $vStamp 	= $data[1];
        $time 		= $data[2];
        $sn 		= $data[3];

        $fc         = new FingerController();
        $dc         = new DeviceController();

        $fingerData = $fc->getUserFinger($user_id);
        $device 	= $dc->getDeviceBySn($sn);

        $salt = md5($sn.$fingerData[0]->finger_data.$device[0]->vc.$time.$user_id.$device[0]->vkey);

        if (strtoupper($vStamp) == strtoupper($salt)) {

            $user           = DB::table('user')->where('user_id', $user_id)->get();
            $log            = new Log();
            $log->user_name = $user[0]->user_name;
            $log->data      = "Verification ".$user[0]->user_name." ".date('Y-m-d H:i:s', strtotime($time))." (PC Time) | ".$sn." (SN)";

            try{
                $result	= $log->save();
                if ($result) {
                    $response = [
                        'status'    => 'T',
                        'message'   => 'Success.',
                        'data'      => [$log]
                    ];
                } else {
                    $response = [
                        'status'    => 'F',
                        'message'   => 'Success.',
                        'data'      => []
                    ];
                }
            }
            catch(\Illuminate\Database\QueryException $e){
                $response = [
                    'status'    => 'F',
                    'message'   => $e,
                    'data'      => []
                ];
            }

        } else {

            $response = [
                'status'    => 'F',
                'message'   => 'Parameter Invalid.',
                'data'      => []
            ];

        }

        return response()->json($response, 200);


    }

}

