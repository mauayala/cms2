<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CmsMessage;
use App\Models\Message;

class MessageController extends Controller {

	public function index()
	{
		$users = User::where('role', 'customer')->orderBy('username')->get();
		return view('message.index', compact('users'));
    }
    
    public function list()
    {
        $messages = CmsMessage::where('from_user_id', \Auth::user()->id)->get();

        return view('message.list', compact('messages'));
    }

	public function send(Request $request) {
        if($request->user_id == 'message_all') {
            $users = User::where('role', 'customer')->orderBy('username')->get();
            foreach($users as $u) {
                $message = new Message;
                $message->content = $request->message;
                $message->user_id = $u->id;
                $message->save();
            }
        } else {
            $message = new Message;
            $message->content = $request->message;
            $message->user_id = $request->user_id;
            $message->save();
        }
        return back()->with(['note' => 'Successfully sent', 'note_type' => 'success']);
    }
    
    public function read(CmsMessage $message) {
        $message->status = 1;
        $message->save();

        $message = CmsMessage::where('user_id', \Auth::user()->id)->where('status', 0)->first();

        return response()->json($message);
    }

}