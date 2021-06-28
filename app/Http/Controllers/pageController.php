<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class pageController extends Controller
{
    public function termsConditions(){
		return view('terms-conditions');
	}
	public function privacyPolicy(){
		return view('privacy-policy');
	}
}
