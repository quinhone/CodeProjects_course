<?php
/**
 * Created by PhpStorm.
 * User: LuisCarlos
 * Date: 02/08/2015
 * Time: 20:49
 */

namespace CodeProject\OAuth;

use \Auth;

class Verifier
{


    public function verify($username, $password)
    {
        $credentials = [
            'email'    => $username,
            'password' => $password,
        ];

        if (\Auth::once($credentials)) {
            return \Auth::user()->id;
        }

        return false;
    }

}