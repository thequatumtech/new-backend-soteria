<?php

namespace App\Http\Controllers;

use App\Models\PurchasePolicy;
use Illuminate\Http\Request;

class RenewalSectionController extends Controller
{

    public function active_policies(Request $request)
    {
        $table_title = __('messages.renewal_section.active_policies');
        $start_date = now()->startOfDay();
        $end_date = now()->addDays(30)->endOfDay();
        $policies = PurchasePolicy::whereBetween('expiry_date',[$start_date,$end_date])->get();
        $is_active_policies = 1;
        return view('admin.renewal_section.index',compact('table_title','policies','is_active_policies'));
    }

    public function expired_policies(Request $request)
    {
        $table_title = __('messages.renewal_section.expired_policies');
        $start_date = now()->subDays(31)->startOfDay();
        $end_date = now()->subDay()->endOfDay();
        $policies = PurchasePolicy::whereBetween('expiry_date',[$start_date,$end_date])->get();
        $is_active_policies = 0;
        return view('admin.renewal_section.index',compact('table_title','policies','is_active_policies'));
    }
}
