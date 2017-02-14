<?php
/**
 * Created by PhpStorm.
 * User: yunfan
 * Date: 2017/1/18
 * Time: 18:07
 */

namespace App\Handles;

use Hprose\Future;

$logHandler = function($name, array &$args, \stdClass $context, \Closure $next) {
//    error_log("before invoke:");
    file_put_contents(storage_path('logs/myinput.log'), "before invoke:" . "\r\n", FILE_APPEND);

//    error_log($name);
    file_put_contents(storage_path('logs/myinput.log'), $name . "\r\n", FILE_APPEND);

//    error_log(var_export($args, true));
    file_put_contents(storage_path('logs/myinput.log'), json_encode($args) . "\r\n", FILE_APPEND);

    $result = $next($name, $args, $context);
    error_log("after invoke:");
    if (Future\isFuture($result)) {
        $result->then(function($result) {
            error_log(var_export($result, true));
        });
    }
    else {
        error_log(var_export($result, true));
    }
    return $result;
};


