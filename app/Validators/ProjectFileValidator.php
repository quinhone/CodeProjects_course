<?php
/**
 * Created by PhpStorm.
 * User: LuisCarlos
 * Date: 24/07/2015
 * Time: 18:29
 */

namespace CodeProject\Validators;


use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\LaravelValidator;

class ProjectFileValidator extends LaravelValidator
{
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'file' => 'required|mimes:jpeg,png,pdf',
            'extension' => 'required',
            'name' => 'required|max:100',
            'description' => 'required'
        ],
        ValidatorInterface::RULE_UPDATE => [
            'extension' => 'required',
            'name' => 'required|max:100',
            'description' => 'required'
        ]
    ];
}