<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'name';
    public $incrementing = false;

    protected $guarded = [];

    public function isDirectFriendOf($name){
        return in_array($name, $this->getFriends());
    }

    public function isIndirectFriendOf($name){
        $a = $this->getFriends();
        $b = User::find($name)->getFriends();
        return count(Array_intersect($a, $b)) != 0;
    }

    public function getFriends(){
        \DB::statement('SET @userName = ?;', [$this->name]);
        $qryResult = \DB::select('
            SELECT (CASE 
                WHEN f.user_a = @userName THEN f.user_b
                ELSE f.user_a
            END) AS name
            FROM friendships AS f
            WHERE f.user_a = @userName OR f.user_b = @userName
        ');

        return array_map(function($f){
            return $f->name;
        }, $qryResult);
    }
}
