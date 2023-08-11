<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Device
Route::get('/device',   'DeviceController@getDevice');
Route::post('/device',  'DeviceController@registerDevice');
Route::post('/device/delete/{sn}',  'DeviceController@deleteDevice');

//User
Route::get('/user', 'UserController@getUser');
Route::post('/user',    'UserController@registerUser');
Route::post('/user/delete/{user_id}',   'UserController@deleteUser');

//Proses
Route::get('/register',   'ProsesController@register');
Route::get('/verification', 'ProsesController@verification');
Route::get('/getac',   'DeviceController@getac');
Route::post('/process_register', 'ProsesController@processRegister');
Route::post('/process_verification', 'ProsesController@processVerification');

//Scan
Route::get('/getscanregister', 'FingerController@getFingerRegister');
Route::get('/getscanverification', 'FingerController@getFingerVerification');



// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
