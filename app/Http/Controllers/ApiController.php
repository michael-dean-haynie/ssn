<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Friendship;


class ApiController extends Controller
{
    public function createUser(Request $req){

        $validator = \Validator::make($req->all(), [
            'name' => 'required|unique:users',
        ]);

        if($validator->fails()){
            return \Response::json($validator->errors(), 400);
        }

        $sJson = ['message' => "User '$req->name' created successfully."];
        $fJson = ['message' => "Error creating user '$req->name'."];

        $user = new User;
        $user->name = $req->name;
        $success = $user->save();

        return \Response::json($success ? $successJson : $failureJson, $success ? 200 : 500);
    }

    public function createFriendship(Request $req){

        $validator = \Validator::make($req->all(), [
            'user_a' => 'required',
            'user_b' => 'required',
        ]);

        // validate input
        if($validator->fails()){
            return \Response::json($validator->errors(), 400);
        }

        // check users exist
        $userA = User::where('name', $req->user_a)->get();
        $userB = User::where('name', $req->user_b)->get();

        if (!count($userA) || !count($userB)){
            return \Response::json(['message' => "One or more of those users do not exist."], 400);
        }

        // check for existing friendship
        $qResult = Friendship::where([
            ['user_a', $req->user_a],
            ['user_b', $req->user_b],
        ])
        ->orWhere([
            ['user_a', $req->user_b],
            ['user_b', $req->user_a],
        ])
        ->get();

        if(count($qResult)){
            return \Response::json(['message' => "Friendship between '$req->user_a' and '$req->user_b' already exists."], 400);
        }

        // create Friendship
        $friendship = new Friendship;
        $friendship->user_a = $req->user_a;
        $friendship->user_b = $req->user_b;
        $success = $friendship->save();

        $sJson = ['message' => "Friendship between '$req->user_a' and '$req->user_b' created successfully."];
        $fJson = ['message' => "Error creating friendship between '$req->user_a' and '$req->user_b'."];

        return \Response::json($success ? $sJson : $fJson, $success ? 200 : 500);
    }

    public function checkFriendship(Request $req){
        
    }
}