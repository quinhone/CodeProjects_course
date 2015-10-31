<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectFileRepositoryInterface;
use CodeProject\Services\ProjectFileService;
use CodeProject\Services\ProjectService;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;
use CodeProject\Http\Controllers\Controller;

class ProjectFileController extends Controller
{

    private $repository;
    private $service;
    private $projectService;

    public function __construct(ProjectFileRepositoryInterface $repository, ProjectFileService $service, ProjectService $projectService)
    {
        $this->repository = $repository;
        $this->service = $service;
        $this->projectService = $projectService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($id)
    {
        return $this->repository->findWhere(['project_id'=>$id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        $data['file'] = $file;
        $data['extension'] = $extension;
        $data['name'] = $request->name;
        $data['project_id'] = $request->project_id;
        $data['description'] = $request->description;

        return $this->service->createFile($data);

        /* try{
         $file = $request->file('file');
         $data['file'] = $file;
         $data['extension'] = $file->getClientOriginalExtension();
         $data['name'] = $request->name;
         $data['project_id'] = $request->project_id;
         $data['description'] = $request->description;

         return $this->service->createFile($data);
     }catch (Exception $e)
     {
         return [
             'error' => true,
             'success' =>  false,
             'message' =>  $e->getMessage()
         ];
     }*/

    /*if($request->file('file'))
     {
       e = $request->file('file');
         $data['file'] = $file;
         $data['extension'] = $file->getClientOriginalExtension();
         $data['name'] = $request->name;
         $data['project_id'] = $request->project_id;
         $data['description'] = $request->description;

         return $this->service->createFile($data);
     }

     return [
         'error' => true,
         'success' =>  false,
         'message' => 'Select a file to send'
     ];*/

    }

    public function showFile($id, $fileId)
    {
        if($this->projectService->checkProjectPermissions($id) == false)
        {
            return ['error' => 'Access Forbidden'];
        }

        $filePath = $this->service->getFilePath($fileId);
        $fileContent = file_get_contents($filePath);
        $file64 = base64_encode($fileContent);
        return [
            'file' => $file64,
            'size' => filesize($filePath),
            'name' => $this->service->getFileName($fileId)
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id, $fileId)
    {

        if($this->projectService->checkProjectPermissions($id) == false)
        {
            return ['error' => 'Access Forbidden'];
        }
        return $this->repository->find($fileId);
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
    public function update(Request $request, $id, $fileId)
    {
        if($this->projectService->checkProjectPermissions($id) == false)
        {
            return ['error' => 'Access Forbidden'];
        }
        return $this->service->update($request->all(), $fileId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id, $fileId)
    {
        if($this->projectService->checkProjectPermissions($id) == false)
        {
            return ['error' => 'Access Forbidden'];
        }
        return $this->service->removeFile($fileId);
    }
}
