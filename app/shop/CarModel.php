<?php

namespace App\shop;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    protected $table = 'tp_car';
    protected $primaryKey = 'car_id';
    public $timestamps = true;
    protected $dateFormat = 'U';
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}
