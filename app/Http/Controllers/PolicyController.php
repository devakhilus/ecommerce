<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function privacy()
    {
        return view('policies.privacy');
    }

    public function terms()
    {
        return view('policies.terms');
    }

    public function refund()
    {
        return view('policies.refund');
    }

    public function shipping()
    {
        return view('policies.shipping');
    }
}
