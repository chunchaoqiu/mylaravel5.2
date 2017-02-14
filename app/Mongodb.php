<?php
/**
 * Created by PhpStorm.
 * User: yunfan
 * Date: 2017/1/12
 * Time: 10:35
 */


namespace App;

use Mongo;
use DB;

class Mongodb extends Mongo {

    protected $collection = '20170112';
    protected $connection = 'mongodb';

    public static function test() {
        $users = DB::collection('20170112')->get();
        var_dump($users);
    }
}

