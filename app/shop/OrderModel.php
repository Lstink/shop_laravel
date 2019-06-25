<?php

namespace App\shop;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    protected $table = 'tp_order';
    protected $primaryKey = 'order_id';
    public $timestamps = true;
    protected $dateFormat = 'U';
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}
