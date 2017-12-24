<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'name';
    public $incrementing = false;

    protected $guarded = [];
}
