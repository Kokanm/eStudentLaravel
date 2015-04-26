<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class TiskajController extends Controller {

	public function izpis($vp)
	{
        return view('tiskaj')->with('vp', $vp);
	}
}
