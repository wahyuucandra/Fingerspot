<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Finger;
use App\User;
use DB;

class FingerController extends Controller
{
    var $base_path  = 'http://127.0.0.1:8000/api/';

    function getUserFinger($user_id) {
		$finger = DB::table('finger')
        ->where('user_id', $user_id)
        ->get();

		return $finger;
	}

    function getFingerRegister(Request $request){

        $user_id = $request['user_id'];

        if($user_id == NULL){
            $response = [
                'status'    => 'F',
                'message'   => 'User Tidak Ditemukan.',
                'data'      => []
            ];
        }else{
            $user = User::find($user_id);

            if($user == NULL){
                $response = [
                    'status'    => 'F',
                    'message'   => 'User Tidak Ditemukan.',
                    'data'      => ""
                ];
            }else{
                $url_register = base64_encode($this->base_path."register?user_id=".$user_id);

                $response = [
                    'status'    => 'T',
                    'message'   => 'Success.',
                    'data'      => "finspot:FingerspotReg;".$url_register
                ];
            }
        }

        return response()->json($response, 200);
    }

    function getFingerVerification(Request $request){

        $user_id = $request['user_id'];

        if($user_id == NULL){
            $response = [
                'status'    => 'F',
                'message'   => 'User Tidak Ditemukan.',
                'data'      => []
            ];
        }else{
            $user = User::find($user_id);

            if($user == NULL){
                $response = [
                    'status'    => 'F',
                    'message'   => 'User Tidak Ditemukan.',
                    'data'      => ""
                ];
            }else{
                $url_verification = base64_encode($this->base_path."verification?user_id=".$user_id);

                $response = [
                    'status'    => 'T',
                    'message'   => 'Success.',
                    'data'      => "finspot:FingerspotVer;".$url_verification
                ];
            }
        }

        return response()->json($response, 200);
    }
}
