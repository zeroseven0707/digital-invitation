<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalController extends Controller
{
    /**
     * Show Terms of Service page
     */
    public function terms()
    {
        return view('legal.terms');
    }

    /**
     * Show Privacy Policy page
     */
    public function privacy()
    {
        return view('legal.privacy');
    }
}
