<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use Auth;

/**
 * @author Elias Falconi
 */
class HomeController extends Controller
{
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
     * Display home page if not authorized user, otherwise display work-done page.
     * @return view|redirect
     */
    public function index(){     
        if (Auth::check()) {
          return redirect('/work-done');
        }else{
          return view('pages.main'); 
        }      
    }
}
