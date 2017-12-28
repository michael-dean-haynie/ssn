<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\User;

class Friendship extends Model
{
    protected $table = 'friendships';

    protected $guarded = [];
}
