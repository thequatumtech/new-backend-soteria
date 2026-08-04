<?php

namespace App\Http\Controllers;

use App\Models\PrivacyModel;
use Illuminate\Http\Request;

class PrivacyandPolicyController extends Controller
{
    function index()
    {
        return view('content.privacy.privacypolicy');
    }

    function privacyForm()
    {
        return view('content.privacy.privacypolicyadd');
    }
    function privacyStore(Request $request)
    {
        info($request->all());
        PrivacyModel::create([
            'company_name' => $request->company_name,
            'line_of_bussiness' => $request->line_of_bussiness,
            'privacy_policy_content' => $request->privacy_policy_content,
            'policy_file' => $request->policy_file,
        ]);
        return redirect()->route('pages.privacy');
    }
}
