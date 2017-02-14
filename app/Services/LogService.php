<?php
/**
 * Created by PhpStorm.
 * User: yunfan
 * Date: 2016/11/25
 * Time: 10:14
 */

namespace App\Services;


class LogService {


    public function write($msg){
        file_put_contents(storage_path('logs/mylog.log'), $msg . "\r\n", FILE_APPEND);
    }

    public function sayHi(){
        return "hello hprose";
    }

    public function hello($msg){
        return "hello " . $msg;
    }

}

