<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserList extends Model
{
    protected $table = 'userList';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
