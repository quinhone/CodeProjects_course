<?php
/**
 * Created by PhpStorm.
 * User: LuisCarlos
 * Date: 24/07/2015
 * Time: 18:27
 */

namespace CodeProject\Services;


use CodeProject\Repositories\ProjectFileRepositoryInterface;
use CodeProject\Repositories\ProjectRepositoryInterface;
use CodeProject\Validators\ProjectFileValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Filesystem\Filesystem;

class ProjectFileService
{
    protected $repository;
    /**
     * @var ProjectRepositoryInterface
     */
    protected $projectRepository;
    /**
     * @var ProjectFileRepositoryInterface
     */
    protected $filerepository;
    /**
     * @var ProjectValidator
     */
    protected $validator;
    /**
     * @var ProjectFileValidator
     */
    protected $filevalidator;
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Storage;
     */
    protected $storage;

    public function __construct(ProjectFileRepositoryInterface $repository,
                                ProjectRepositoryInterface $projectRepository,
                                ProjectFileValidator $validator,
                                Filesystem $filesystem,
                                Storage $storage)
    {
        $this->repository = $repository;
        $this->projectRepository = $projectRepository;
        $this->filevalidator = $validator;
        $this->filesystem = $filesystem;
        $this->storage = $storage;
    }

    public function create(array $data)
    {
        try{
            $project = $this->projectRepository->skipPresenter()->find($data['project_id']);
            $projectFile = $project->files()->create($data);

            $this->storage->put($projectFile->id.".".$data['extension'], $this->filesystem->get($data['file']));

            return $projectFile;

        }catch (ValidatorException $e)
        {
            return [
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        }
    }

    public function update(array $data, $id)
    {
        try{
            $this->filevalidator->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);
            return $this->repository->update($data, $id);
        }catch (ValidatorException $e)
        {
            return [
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        }
    }

    public function delete($id)
    {
        try{
            $client = $this->getProject($id);

            if( $client['success'] ){
                return ["success" => $this->repository->delete($id)];
            }

            return $client;

        }catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Could not delete the Client {$id}"
            ];
        }
    }

    public function createFile(array $data)
    {
        try{

           $this->filevalidator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);

            $project = $this->projectRepository->skipPresenter()->find($data['project_id']);
            $projectFile = $project->files()->create($data);

            $this->storage->disk('local_public')->put($projectFile->getFileName(), $this->filesystem->get($data['file']));

            return [
                'error' => false,
                'success' =>  true
            ];

        }
        catch(ValidatorException $e)
        {
            return [
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        }

    }

    public function removeFile($id)
    {
        try{
            $projectfile = $this->getProjectFile($id);

            if( $projectfile['success'] )
            {
                if(file_exists(public_path().'/uploads/'.$id.'.'.$projectfile['success']->extension))
                {
                    $this->storage->disk('local_public')->delete($id.'.'.$projectfile['success']->extension);
                }
                return ["success" => $this->repository->delete($id)];
            }

            return $projectfile;

        }catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Could not delete the File {$id}"
            ];
        }
    }

    public function getFileName($id)
    {
        $projectFile = $this->repository->skipPresenter()->find($id);
        return $projectFile->getFileName();
    }

    public function getFilePath($id)
    {
        $projectFile = $this->repository->skipPresenter()->find($id);
        return $this->getBaseURL($projectFile);
    }

    public function getBaseURL($projectFile)
    {
        switch($this->storage->getDefaultDriver())
        {
            case 'local':
                return $this->storage->getDriver()->getAdapter()->getPathPrefix()
                .'/'.$projectFile->getFileName();
        }
    }

    public function getProjectFile($id)
    {
        try{
            return ["success" => $this->repository->find($id)];
        } catch(\Exception $e) {
            return ["success" => false, "message" => "File ID: {$id} not found"];
        }
    }

    public function checkProjectOwner($projectFileId)
    {
        $userid = \Authorizer::getResourceOwnerId();
        $projectId = $this->repository->skipPresenter()->find($projectFileId)->project_id;

        return $this->projectRepository->isOwner($projectId, $userid);

    }

    public function checkProjectMember($projectFileId)
    {
        $userid = \Authorizer::getResourceOwnerId();
        $projectId = $this->repository->skipPresenter()->find($projectFileId)->project_id;

        return $this->projectRepository->hasMember($projectId, $userid);

    }

    public function checkProjectPermissions($projectFileId)
    {
        if($this->checkProjectOwner($projectFileId) || $this->checkProjectMember($projectFileId))
        {
            return true;
        }
        return false;
    }

}