<?php

namespace App\shop;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    protected $table = 'tp_category';
    protected $primaryKey = 'cate_id';
    public $timestamps = false;

}
