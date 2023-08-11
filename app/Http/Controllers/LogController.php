<?php

namespace App\Http\Controllers;
use App\Log;
use DB;

class LogController extends Controller
{

    function getLog() {

		$log = DB::table('log')
        ->orderby('log_time', 'desc')
        ->get();

		return $log;
	}

	function createLog($user_name, $time, $sn) {
		$log = new Log;
        $log->username  = $user_name;
        $log->data      = date('Y-m-d H:i:s', strtotime($time))." (PC Time) | ".$request['sn']." (SN)".$request['data'];

        try{
            $result	= $log->save();
            if ($result) {
                return 1;
            } else {
                return "Error insert log data!";
            }
        }
        catch(\Illuminate\Database\QueryException $e){
            return $e;
        }


	}
}
