<?php
/**
 * Created by PhpStorm.
 * User: yunfan
 * Date: 2017/1/18
 * Time: 16:32
 */

namespace App\Filters;

use Hprose\Filter;

class LogFilter implements Filter {
    public function inputFilter($data, \stdClass $context) {
//        error_log($data);

        file_put_contents(storage_path('logs/myinput.log'), $data . "\r\n", FILE_APPEND);

        return $data;
    }
    public function outputFilter($data, \stdClass $context) {
//        error_log($data);

        file_put_contents(storage_path('logs/myoutput.log'), $data . "\r\n", FILE_APPEND);
        return $data;
    }
}