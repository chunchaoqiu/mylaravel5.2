<?php
/**
 * Created by PhpStorm.
 * User: yunfan
 * Date: 2017/2/13
 * Time: 11:08
 */


namespace Yuansir\Toastr\Facades;

use Illuminate\Support\Facades\Facade;

class Toastr extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'toastr';
    }
}



