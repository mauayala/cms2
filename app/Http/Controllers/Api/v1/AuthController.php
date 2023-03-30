<?php  
namespace App\Http\Controllers\Api\v1;

use App\Models\DemoDevice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller 
{
    public function login (Request $request)
    {
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'role' => 'customer'])) {
			$user = User::find(\Auth::user()->id);
			if(\Auth::user()->trial_ends_at > date('Y-m-d H:i:s') && ($user->devicenumber==$request->device || is_null($user->devicenumber))){
				$user->ip = $request->ip();
				$user->access_token = md5(uniqid($user->username, true));
				$user->access_token_lifetime = date('Y-m-d H:i:s', time() + 3*24*60*60);
				$user->last_action_at = date('Y-m-d H:i:s');
                if(is_null($user->devicenumber)){
                    $user->devicenumber = $request->device;
                }
                if(is_null($user->model)){
                    $user->model = $request->model;
                }
				$user->save();
				return response()->json($user);
			} elseif(!DemoDevice::where('device_number', $request->device)->first()) {
				$demo_device = DemoDevice::create(['device_number' => $request->device]);
				if($user->trial_ends_at < date('Y-m-d H:i:s')) {
					$user->trial_ends_at = \Carbon\Carbon::now()->addDays(2)->format('Y-m-d H:i:s');
				}
				$user->save();
			} elseif(empty($user->devicenumber) || $user->devicenumber != $request->device){
				return response()->json(['error' => $user->username.', tu cuenta esta ligada a otro aparato, favor de cerrar session alla e intentar de nuevo aqui.']);
			} else {
				return response()->json(['error' => $user->username.', lo sentimos tu cuenta ha expirado.']);
			}
		} else {

			return response()->json(['error' => 'Invalid username or password']);

			
			$username = $request->username;
	        $password = $request->password;
	        $device = $request->device;
	        $remote_url = 'https://cloudtvroku.com/api/auth_check.php?username='.$username.'&password='.$password.'&device='.$device;

	        // Create a stream
	        $opts = array(
	          	'http'=>array(
	            	'method'=>"GET",
	            	'header' => "Authorization: Basic " . base64_encode("mauricio:Proxy4121")                 
	          	)
	        );
	        $context = stream_context_create($opts);
	        // Open the file using the HTTP headers set above
	        $file = file_get_contents($remote_url, false, $context);
	        $file = json_decode($file);
            
	        if($file->status == 'false'){
	            return response()->json(['error' => 'Invalid username or password']);
	        } elseif($file->status == 'true') {
                $user = new User;
                $user->firstname = $file->user->first_name;
                $user->lastname = $file->user->last_name;
                $user->username = $file->user->username;
                $user->password = Hash::make($password);
                $user->trial_ends_at = $file->user->subscription_end;
                $user->email = $file->user->email_address;
                $user->role = 'customer';
                $user->save();
                if (Auth::attempt(['username' => $username, 'password' => $password, 'role' => 'customer'])) {
                    if(\Auth::user()->trial_ends_at > date('Y-m-d H:i:s')){
                        $user = User::find(\Auth::user()->id);
                        $user->ip = $request->ip();
                        $user->access_token = md5(uniqid($user->username, true));
                        $user->access_token_lifetime = date('Y-m-d H:i:s', time() + 3*24*60*60);
                        $user->save();
                        return response()->json($user);
                    } else {
                        return response()->json(['error' => 'Your subscription is expired']);
                    }
                }
	        }		

			return response()->json(['error' => 'Invalid username or password']);
		}
	}

	public function login1 (Request $request)
    {
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'role' => 'customer'])) {
			$user = User::find(\Auth::user()->id);
			if(\Auth::user()->trial_ends_at > date('Y-m-d H:i:s')){
				$user->ip = $request->ip();
				$user->access_token = md5(uniqid($user->username, true));
				$user->access_token_lifetime = date('Y-m-d H:i:s', time() + 3*24*60*60);
				$user->last_action_at = date('Y-m-d H:i:s');
                $user->save();
				return response()->json($user);
			} else {
				return response()->json(['error' => $user->username.', lo sentimos tu cuenta ha expirado.']);
			}
		}		
	}

	public function logout(Request $request){
		$user = User::where('access_token', $request->token)->first();
		$user->mac = null;
		$user->model = null;
		$user->devicenumber = null;
		$user->save();
		return response()->json(['succses' => 'Logged out']);
	}
}
