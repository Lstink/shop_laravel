<?php

namespace App\shop;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'tp_user';
    protected $primaryKey = 'u_id';
    public $timestamps = false;
}
