<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gate;

class SampleController extends Controller
{
   public function index(){
	   	if(!Gate::allows('isAdmin')){
	   		abort(403, 'Sorry, this page is for system administrator.');
	   	}
	   	else{
	   		return view('pages.typography');	
	   	}
   } 
}
