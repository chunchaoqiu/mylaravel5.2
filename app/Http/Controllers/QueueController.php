<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Jobs\LogWriteJob;

class QueueController extends Controller
{
    //

    public function queue(Request $request){


        $this->dispatch(new LogWriteJob());
    }


}









