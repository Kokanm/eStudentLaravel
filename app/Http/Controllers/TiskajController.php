<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TiskajController extends Controller {

	public function izpisReferent($vp)
	{
        return view('tiskaj')->with('vp', $vp);
	}
}
