<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Auth;
use App\Models\AppVersion;
use App\Models\DemoDevice;
use App\Models\Setting;
use App\Models\User;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('username', $request->username)->first();
        $user->app_version_id = AppVersion::where('version', $request->version)->first()->id;
        if($user->devicenumber == $request->device) {
            $user->ip = $request->ip();
        }
        if($user->devicenumber2 == $request->device && $user->ip != $request->ip()) {
            return response()->json(['error' => 'Please make sure both your devices are within the same network']);
        }
        if($user->devicenumber2 != $request->device && $user->devicenumber == null) {
            $user->devicenumber = $request->device;
            $user->model = $request->model;
            $user->ip = $request->ip();
        }
        if($user->devicenumber != $request->device && $user->devicenumber2 == null && $user->ip == $request->ip()) {
            $user->devicenumber2 = $request->device;
            $user->model2 = $request->model;
        }
        $user->save();
        if (
            $user = User::where('username', $request->username)
                    ->where('role', 'customer')
                    ->where(function($q)use($request){
                        $q->where(function($q1) use($request) {
                            $q1->where('devicenumber', $request->device)->where('model', $request->model);
                        })->orWhere(function($q1) use($request) {
                            $q1->where('devicenumber2', $request->device)->where('model2', $request->model);
                        });
                    })
                    ->first()
        ) {
            if($user->trial_ends_at > date('Y-m-d H:i:s')){
				$user->access_token = md5(uniqid($user->username, true));
				$user->access_token_lifetime = date('Y-m-d H:i:s', time() + 3*24*60*60);
				$user->last_action_at = date('Y-m-d H:i:s');
				$user->save();
				return response()->json($user);
			} elseif(!DemoDevice::where('device_number', $request->device)->first()) {
				$demo_device = DemoDevice::create(['device_number' => $request->device]);
				if($user->trial_ends_at < date('Y-m-d H:i:s')) {
					$user->trial_ends_at = \Carbon\Carbon::now()->addDays(2)->format('Y-m-d H:i:s');
				}
				$user->save();

                return response()->json($user);
			} else {
				return response()->json(['error' => $user->username.', lo sentimos tu cuenta ha expirado.']);
			}
        } else {
            return response()->json(['error' => $request->username.', tu cuenta esta ligada a otro aparato, favor de cerrar session alla e intentar de nuevo aqui.']);
        }
    }

    public function background()
    {
        return response()->json(['background' => Setting::first()->login_background]);
    }
}
