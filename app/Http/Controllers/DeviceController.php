<?php

namespace App\Http\Controllers;
use DB;
use App\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
	function getDeviceAcSn($vc) {

        $device = DB::table('device')
        ->where('vc', $vc)
        ->get();

        return $device;
	}

	function getDeviceBySn($sn) {
		$device = DB::table('device')
        ->where('sn', $sn)
        ->get();

        return $device;
	}

    function deviceCheckSn($sn) {

        echo $sn;

		$device = DB::table('device')
        ->where('sn', $sn)
        ->get();

        if ($device != NULL) {
			return "sn already exist!";
		} else {
			return 1;
		}
	}

    function getac(Request $request) {
        if($request->vc == null){
            echo "Data tidak ditemukan.";
        }else{
            $data = $this->getDeviceAcSn("02AA186F152AF51");

            echo $data[0]->ac.$data[0]->sn;
        }
    }

    function getDevice() {
        $device = DB::table('device')
        ->orderBy('device_name', 'ASC')
        ->get();

        if(sizeof($device) == 0){
            $response = [
                'status'    => 'T',
                'message'   => 'Data Belum Ada.',
                'data'      => []

            ];
        }else{
            $response = [
                'status'    => 'T',
                'message'   => 'Success.',
                'data'      => [$device]
            ];
        }

        return response()->json($response, 200);
	}

    function registerDevice(Request $request) {
        $device = new Device;
        $device->device_name    = $request->device_name;
        $device->sn             = $request->sn;
        $device->vc             = $request->vc;
        $device->ac             = $request->ac;
        $device->vkey           = $request->vkey;

        $deviceCek = Device::where('sn', $request->sn)->get();

        if(sizeof($deviceCek) == 0){
            try{
                $result	= $device->save();
                if ($result) {
                    $response = [
                        'status'    => 'T',
                        'message'   => 'Success.',
                        'data'      => [$device]
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
        }else{
            $response = [
                'status'    => 'F',
                'message'   => 'Device sudah ada.',
                'data'      => []
            ];
        }

        return response()->json($response, 200);
	}

    public function deleteDevice($sn)
    {
        $device = Device::find($sn);

        if($device==NULL){
            $response = [
                'status' => 'F',
                'message' => 'Device Tidak Ditemukan',
                'data' => [],
                
            ];
        }
        else
        {
            $device->delete();
            $response = [
                'status' => 'T',
                'message' => 'Success',
                'data' => [],
            ];
        }

        return response()->json($response,200);
    }
}
