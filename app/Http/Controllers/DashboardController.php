<?php

namespace App\Http\Controllers;

use App\Models\PurchasePolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $total_purchased_policy = PurchasePolicy::whereYear('created_at', date('Y'))->count();
        $start = Carbon::now()->startOfMonth()->subMonth();
        $end = $start->copy()->endOfMonth();
        $last_month_purchased_policy = PurchasePolicy::whereBetween('created_at', [$start, $end])->count();
        $total_premium_up_to_date = PurchasePolicy::whereYear('created_at', date('Y'))->sum('net_premium'); //TODO
        $total_premium_last_month = PurchasePolicy::whereBetween('created_at', [$start, $end])->sum('net_premium'); //TODO
        $highest_sold_policies_by_type = PurchasePolicy::select('policy_type', \DB::raw('count(*) as total'))
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfDay()])
            ->groupBy('policy_type')
            ->orderByDesc('total')
            ->first();
        $highest_sold_policies_by_company = PurchasePolicy::select('insurance_company_id', \DB::raw('count(*) as total'))
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfDay()])
            ->groupBy('insurance_company_id')
            ->orderBy('total', 'desc')
            ->first();
        return view('admin.dashboard',compact('total_purchased_policy','last_month_purchased_policy','total_premium_up_to_date','total_premium_last_month','highest_sold_policies_by_type','highest_sold_policies_by_company'));
    }
}
