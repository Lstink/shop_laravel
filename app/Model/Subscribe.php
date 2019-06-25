<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    protected $table = 'subscribe';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $dateFormat = 'U';
}
