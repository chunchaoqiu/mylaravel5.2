<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //

    protected $table = 'products';

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = ['productname'];

    protected $hidden = ['created_at','updated_at'];

    public function shops(){
        return $this->belongsToMany('App\Product','product_shop');;
    }

}
