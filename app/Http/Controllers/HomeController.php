<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use Illuminate\Http\Request;
//use Toastr;
//use Yuansir\Toastr\Toastr;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //略
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        Toastr::error('你好啊','标题');
        app('toastr')->error('你好啊','标题');
//        dd(app('toastr'));
        return view('home');
    }
}




