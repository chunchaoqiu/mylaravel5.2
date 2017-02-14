<?php
/**
 * Created by PhpStorm.
 * User: yunfan
 * Date: 2016/11/28
 * Time: 14:17
 */

namespace App\Exceptions;

class SqliteStore implements \Illuminate\Contracts\Cache\Store {


    public function get($key) {}
    public function put($key, $value, $minutes) {}
    public function increment($key, $value = 1) {}
    public function decrement($key, $value = 1) {}
    public function forever($key, $value) {}
    public function forget($key) {}
    public function flush() {}
    public function getPrefix() {}

    public function putMany(array $values, $minutes){}


    public function many(array $keys){}
}