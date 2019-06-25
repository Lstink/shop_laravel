<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TagList extends Model
{
    protected $table = 'tagList';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
