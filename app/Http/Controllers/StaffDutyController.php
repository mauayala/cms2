<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StaffDuty;
use App\Models\User;

class StaffDutyController extends Controller {

    public function index()
    {
        $staff_duties = StaffDuty::all();
        $users = User::where('role', 'staff')->get();
        return view('staff_duties.index', compact('staff_duties', 'users'));
    }

    public function update(Request $request)
    {
        $i = 0;
        foreach($request->staff_duties as $sd) {
            $staff_duty = StaffDuty::find($sd);
            $staff_duty->update([
                'user_id'           => $request->users[$i],
                'max_broken_link'   => $request->max_broken_links[$i]
            ]);
            $i++;
        }

        return redirect('/dashboard/staff_duties')->with(['note' => 'Successfully Updated Staff duties!', 'note_type' => 'success']);
    }
}