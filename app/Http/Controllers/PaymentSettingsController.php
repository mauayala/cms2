<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentSetting;

class PaymentSettingsController extends Controller {

	public function index()
	{
		$payment_settings = PaymentSetting::first();
		return view('paymentsettings.index', compact('payment_settings'));
	}

	public function save_payment_settings(Request $request){

		$input = $request->all();

		$payment_settings = PaymentSetting::first();

		if(!isset($input['live_mode'])){
			$input['live_mode'] = 0;
		}

        $payment_settings->update($input);

        return redirect('dashboard/payment_settings')->with(['note' => 'Successfully Updated Payment Settings!', 'note_type' => 'success']);

	}

}