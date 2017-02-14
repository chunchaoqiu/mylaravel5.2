<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Mongodb;


class MongodbController extends Controller
{
    //

    public function test() {
        Mongodb::test();

    }


}
