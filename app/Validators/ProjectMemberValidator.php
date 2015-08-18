<?php
/**
 * Created by PhpStorm.
 * User: LuisCarlos
 * Date: 24/07/2015
 * Time: 18:29
 */

namespace CodeProject\Validators;


use Prettus\Validator\LaravelValidator;

class ProjectMemberValidator extends LaravelValidator
{
    protected $rules = [
        'user_id' => 'required|integer',
        'project_id' => 'required|integer'
    ];
}