<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\UserRepositoryInterface;
use CodeProject\Services\UserService;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;
use CodeProject\Http\Controllers\Controller;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class UserController extends Controller
{
    private $repository;
    private $service;

    public function __construct(UserRepositoryInterface $repository, UserService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function authenticated()
    {
        $userId = Authorizer::getResourceOwnerId();
        return $this->repository->find($userId);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       return $this->repository->with(['projects'])->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return $this->repository->with(['projects'])->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        return $this->service->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}
