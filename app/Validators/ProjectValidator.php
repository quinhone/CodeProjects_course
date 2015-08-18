<?php
/**
 * Created by PhpStorm.
 * User: LuisCarlos
 * Date: 24/07/2015
 * Time: 18:29
 */

namespace CodeProject\Validators;


use Prettus\Validator\LaravelValidator;

class ProjectValidator extends LaravelValidator
{
    protected $rules = [
        'user_id' => 'required|integer',
        'client_id' => 'required|integer',
        'name' => 'required|max:100',
        'description' => 'required',
        'progress' => 'required',
        'status' => 'required',
        'due_date' => 'required'
    ];
}