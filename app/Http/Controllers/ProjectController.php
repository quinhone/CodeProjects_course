<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectRepositoryInterface;
use CodeProject\Services\ProjectService;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;

class ProjectController extends Controller
{

    private $repository;
    private $service;

    public function __construct(ProjectRepositoryInterface $repository, ProjectService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->repository->findWhere(['user_id' => \Authorizer::getResourceOwnerId()]);
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
        if($this->checkProjectPermissions($id) == false)
        {
            return ['error' => 'Access Forbidden'];
        }
        return $this->repository->find($id);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {

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
        if($this->checkProjectOwner($id) == false)
        {
            return ['error' => 'Access Forbidden'];
        }
       return  $this->service->update($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if($this->checkProjectOwner($id) == false)
        {
            return ['error' => 'Access Forbidden'];
        }
        $this->service->delete($id);
    }

    private function checkProjectOwner($projectId)
    {
        $userid = \Authorizer::getResourceOwnerId();
        return $this->repository->isOwner($projectId, $userid);

    }

    private function checkProjectMember($projectId)
    {
        $userid = \Authorizer::getResourceOwnerId();
        return $this->repository->hasMember($projectId, $userid);

    }

    private function checkProjectPermissions($projectId)
    {
        if($this->checkProjectOwner($projectId) || $this->checkProjectMember($projectId))
        {
            return true;
        }
        return false;
    }

}
