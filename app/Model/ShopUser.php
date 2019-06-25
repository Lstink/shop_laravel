<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ShopUser extends Model
{
    protected $table = 'tp_user';
    protected $primaryKey = 'u_id';
    public $timestamps = false;
}
