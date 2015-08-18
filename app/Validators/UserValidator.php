<?php
/**
 * Created by PhpStorm.
 * User: LuisCarlos
 * Date: 24/07/2015
 * Time: 18:41
 */

namespace CodeProject\Validators;


use Prettus\Validator\LaravelValidator;

class UserValidator extends LaravelValidator
{
    protected $rules = [
        'name' => 'required|max:255',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|confirmed|min:6',
    ];
}