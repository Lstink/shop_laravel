<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WxUser extends Model
{
    protected $table = 'wx_user';
    protected $primaryKey = 'wx_id';
    public $timestamps = false;
}
