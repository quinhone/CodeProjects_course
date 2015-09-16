<?php
/**
 * Created by PhpStorm.
 * User: LuisCarlos
 * Date: 22/07/2015
 * Time: 22:22
 */

namespace CodeProject\Repositories;


use CodeProject\Entities\Client;
use CodeProject\Presenters\ClientPresenter;
use Prettus\Repository\Eloquent\BaseRepository;

class ClientRepositoryEloquent extends BaseRepository implements ClientRepositoryInterface
{
    public function model()
    {
        return Client::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria( app(RequestCriteria::class) );
    }

    public function presenter()
    {
        return ClientPresenter::class;
    }

}