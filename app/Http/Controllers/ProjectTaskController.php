<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectTaskRepositoryInterface;
use CodeProject\Services\ProjectTaskService;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;

class ProjectTaskController extends Controller
{

    private $repository;
    private $service;

    public function __construct(ProjectTaskRepositoryInterface $repository, ProjectTaskService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($id)
    {
        return $this->repository->with(['project'])->findWhere(['project_id'=>$id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        $data['project_id'] = $id;
        return $this->service->create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id, $taskId)
    {
        $result = $this->repository->with(['project'])->findWhere(['project_id'=>$id, 'id' => $taskId]);
        if(isset($result['data']) && count($result['data'] == 1))
        {
            $result = [
                'data' => $result['data'][0]
            ];
        }
        return $result;
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
    public function update(Request $request, $id, $idTask)
    {
        $data = $request->all();
        $data['project_id'] = $id;
        $this->service->update($data, $idTask);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id, $idTask)
    {
        $this->service->delete($idTask);
    }
}
