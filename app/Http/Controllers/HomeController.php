<?php namespace App\Http\Controllers;

class HomeController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        $email = \Auth::user()->email;

        if(\Auth::user()->type==0 || \Auth::user()->type==1)
        {
            return view('student.prva', compact('email'));
        }

        if(\Auth::user()->type==2)
        {
            return view('referent.prva', compact('email'));
        }

        if(\Auth::user()->type==3)
        {
            return view('ucitelj.prva', compact('email'));
        }

        if(\Auth::user()->type==4)
        {
            return view('skrbnik.prva', compact('email'));
        }
    }

}
