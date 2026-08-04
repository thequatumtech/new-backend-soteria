<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class Pagecontroller extends Controller
{
    public function index()
    {
//        Session::put("success", "Insert value success");
        return view('content.dashboard');
    }


    public function agent()
    {
        return view('content.agent.agentadd');

    }
}
