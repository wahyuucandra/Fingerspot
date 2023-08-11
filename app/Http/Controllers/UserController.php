<?php

namespace App\Http\Controllers;
use DB;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\FingerController;

class UserController extends Controller
{

	function getUser() {

		$user = DB::table('user')
        ->orderBy('user_name', 'ASC')
        ->get();

		if(sizeof($user) == 0){
            $response = [
                'status'    => 'T',
                'message'   => 'Data Belum Ada.',
                'data'      => []

            ];
        }else{
            $response = [
                'status'    => 'T',
                'message'   => 'Success.',
                'data'      => [$user]
            ];
        }

        return response()->json($response, 200);

	}

	function checkUserName($user_name) {

        $user = DB::table('user')
        ->where('user_name', $user_name)
        ->get();

		if ($user != NULL) {
			return "Username exist!";
		} else {
			return "1";
		}

	}

    function registerUser(Request $request){
        $user = new User;
        $user->user_name  = $request->user_name;

        $userCek = User::where('user_name', $user->user_name)->get();

        if(sizeof($userCek) == 0){
            try{
                $result	= $user->save();
                if ($result) {
                    $response = [
                        'status'    => 'T',
                        'message'   => 'Insert User Berhasil.',
                        'data'      => [$user]
                    ];
                } else {
                    $status=500;
                    $response = [
                        'status'    => 'F',
                        'message'   => 'Gagal Insert User.',
                        'data'      => [$user],
                    ];
                }
            }
            catch(\Illuminate\Database\QueryException $e){
                $response = [
                    'status'    => 'F',
                    'message'   => $e,
                    'data'      => [$user]
                    
                ];
            }
        }else{
            $response = [
                'status'    => 'F',
                'message'   => 'Username sudah ada.',
                'data'      => []
                
            ];
        }

        return response()->json($response, 200);
    }

    public function deleteUser($user_id)
    {
        $user = User::find($user_id);

        if($user==NULL){
            $response = [
                'status' => 'F',
                'message' => 'User Tidak Ditemukan',
                'data' => [],
                
            ];
        }
        else
        {
            $user->delete();
            $response = [
                'status' => 'T',
                'message' => 'Success',
                'data' => [],
            ];
        }

        return response()->json($response,200);
    }

}
