<?php

namespace App\shop;

use Illuminate\Database\Eloquent\Model;

class OrderDetailModel extends Model
{
    protected $table = 'tp_order_detail';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $dateFormat = 'U';
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}
