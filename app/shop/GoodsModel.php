<?php

namespace App\shop;

use Illuminate\Database\Eloquent\Model;

class GoodsModel extends Model
{
    protected $table = 'tp_goods';
    protected $primaryKey = 'goods_id';
    public $timestamps = false;
}
