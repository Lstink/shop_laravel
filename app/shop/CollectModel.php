<?php

namespace App\shop;

use Illuminate\Database\Eloquent\Model;

class CollectModel extends Model
{
    protected $table = 'tp_collect';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
