<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    //

    protected $table = 'shops';

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = ['shopname'];

    protected $hidden = ['created_at','updated_at'];

    public function products(){
        return $this->belongsToMany('App\Product', 'product_shop');;
    }

}
