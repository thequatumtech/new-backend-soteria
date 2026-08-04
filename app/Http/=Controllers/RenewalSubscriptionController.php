<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RenewalSubscriptionController extends Controller
{
    function index(){
        return view('content.renewal.renewalsub');
    }
}
