<?php

namespace App\shop;

use Illuminate\Database\Eloquent\Model;

class AddressModel extends Model
{
    protected $table = 'tp_address';
    protected $primaryKey = 'address_id';
    public $timestamps = true;
    protected $dateFormat = 'U';
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}
