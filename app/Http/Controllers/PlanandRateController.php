<?php

namespace App\Http\Controllers;

use App\Models\InsurancePlanModel;
use Illuminate\Http\Request;

class PlanandRateController extends Controller
{
    function index()
    {
        $plandata = InsurancePlanModel::all();
        return view('content.plan.plan', compact('plandata'));
    }
    public function PlanCreate(Request  $request)
    {
        info($request->all());

        $plandata = [
            'company_name' => $request->company_name,
            'lineofbussines' => $request->lineofbussines,
            'plan_name' => $request->plan_name,
            'limit' => $request->limit,
            'plan_fee' => $request->plan_fee,
            'sales_tax' => $request->sales_tax,
            'net_premium' => $request->net_premium,
            'gross_premium' => $request->gross_premium,
            'commission' => $request->commission,
            'stamp_fee' => $request->stamp_fee,
            'commission_percent' => $request->commission_percent,
        ];
        $insuranceplan = InsurancePlanModel::create($plandata);
        return redirect()->route('pages.plan')->with('Sucess', 'Sucessfully Created Plan');
    }
    function PlanEdit($id)
    {
        $plandata = InsurancePlanModel::find($id);
        info($plandata);
        return response()->json([
            'plan' => $plandata,
            'status' => 200
        ]);
    }
    public function PlanUpdate(Request $request)
    {
        info($request->all());
        $plan = InsurancePlanModel::find($request->plan_id);
        
        $plan->update([
            'company_name' => $request->company_name,
            'lineofbussines' => $request->lineofbussines,
            'plan_name' => $request->plan_name,
            'limit' => $request->limit,
            'plan_fee' => $request->plan_fee,
            'sales_tax' => $request->sales_tax,
            'net_premium' => $request->net_premium,
            'gross_premium' => $request->gross_premium,
            'commission' => $request->commission,
            'stamp_fee' => $request->stamp_fee,
            'commission_percent' => $request->commission_percent,
        ]);
        return redirect()->back()->with('success', 'Plan Update successfully');
    }
}
