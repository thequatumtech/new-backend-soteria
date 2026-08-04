<?php

namespace App\Http\Controllers;

use App\Models\SupervisorModel;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    function index()
    {
        $supervisordata = SupervisorModel::all();
        return view('content.supervisor.supervisoradd', compact('supervisordata'));
    }
    function SupervisorCreate(Request $request)
    {
        info($request->all());
        SupervisorModel::create([
            'svname' => $request->svname,
            'svemail' => $request->svemail,
            'svmobile_no' => $request->svmobile_no,
            'sv_gender' => $request->sv_gender,
            'joining_date' => $request->joining_date,
            'nationalandpassportno' => $request->nationalandpassportno,
            'agent_commission' => $request->agent_commission,
            'override_commission' => $request->override_commission,
        ]);
        return redirect()->route('pages.supervisor');
    }
    function supervisorEdit($id)
    {
        $supervisordata = SupervisorModel::find($id);
        info($supervisordata);
        return response()->json([
            'supervisor' => $supervisordata,
            'status' => 200
        ]);
    }
    public function supervisorUpdate(Request $request)
    {
        $supervisor = SupervisorModel::find($request->supervisor_id);

        if ($supervisor) {
            $supervisor->update([
                'svname' => $request->svname,
                'svemail' => $request->svemail,
                'svmobile_no' => $request->svmobile_no,
                'sv_gender' => $request->sv_gender,
                'joining_date' => $request->joining_date,
                'nationalandpassportno' => $request->nationalandpassportno,
                'agent_commission' => $request->agent_commission,
                'override_commission' => $request->override_commission,
            ]);

         
            return redirect()->back()->with('success', 'SuperVisor Update successfully');
        }

        return response()->back()->with('error', 'SuperVisor not found');
    }
    function destorysupervisor(Request $request)
    {
        info($request->all());
        $supervisor_id = $request->input('delete_supervisor_id');
        $supervisor = SupervisorModel::find($supervisor_id);
        $supervisor->delete();

        return redirect()->back()->with('status', 'supervisor deleted successfully');
    }
}
