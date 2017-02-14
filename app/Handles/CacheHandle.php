<?php


namespace App\Handles;

class CacheHandle {
    private $cache = array();
    function handle($name, array &$args, \stdClass $context, \Closure $next) {
        if (isset($context->userdata->cache)) {
            $key = hprose_serialize($args);
            if (isset($this->cache[$name])) {
                if (isset($this->cache[$name][$key])) {
                    file_put_contents(storage_path('logs/cache.log'), $key . "\r\n", FILE_APPEND);
                    return $this->cache[$name][$key];
                }
            }
            else {
                $this->cache[$name] = array();
            }
            $result = $next($name, $args, $context);
            $this->cache[$name][$key] = $result;
            return $result;
        }
        return $next($name, $args, $context);
    }
}

