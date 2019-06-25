<?php

namespace App\shop;

use Illuminate\Database\Eloquent\Model;

class OrderAddressModel extends Model
{
    protected $table = 'tp_order_address';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $dateFormat = 'U';
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}
