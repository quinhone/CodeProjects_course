<?php
/**
 * Created by PhpStorm.
 * User: LuisCarlos
 * Date: 24/07/2015
 * Time: 18:44
 */

namespace CodeProject\Repositories;


use CodeProject\Entities\User;
use CodeProject\Presenters\UserPresenter;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepositoryEloquent extends BaseRepository implements UserRepositoryInterface
{
    protected $fieldSearchable = [
        'name'
    ];

    public function model()
    {
        return User::class;
    }

    /*public function presenter()
    {
        return UserPresenter::class;
    }*/

    public function boot(){
        $this->pushCriteria(app(RequestCriteria::class));
    }
}