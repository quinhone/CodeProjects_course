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
use CodeProject\Validators\ProjectValidator;
use Prettus\Validator\Exceptions\ValidatorException;

use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Filesystem\Filesystem;

class ProjectService
{
    /**
     * @var ProjectRepositoryInterface
     */
    protected $repository;

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

    public function __construct(ProjectRepositoryInterface $repository, ProjectFileRepositoryInterface $filerepository, ProjectValidator $validator, ProjectFileValidator $filevalidator, Filesystem $filesystem, Storage $storage)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->filesystem = $filesystem;
        $this->storage = $storage;
        $this->filevalidator = $filevalidator;
        $this->filerepository = $filerepository;
    }

    public function create(array $data)
    {
        try{
            $this->validator->with($data)->passesOrFail();
            return $this->repository->create($data);
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
            $this->validator->with($data)->passesOrFail();
            return $this->repository->create($data, $id);
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

    public function getProject($id)
    {
        try{
            return ["success" => $this->repository->find($id)];
        } catch(\Exception $e) {
            return ["success" => false, "message" => "Client ID: {$id} not found"];
        }
    }

    public function addMember()
    {

    }

    public function removeMember($id)
    {
        try{
            $project = $this->getProject($id);

            if( $project['success'] ){
                return ["success" => $this->repository->delete($id)];
            }

            return $project;

        }catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Could not delete the Client {$id}"
            ];
        }
    }

    public function isMember($projectId, $userId)
    {
        if(count($this->repository->findWhere(['id' => $projectId, 'user_id' => $userId ])))
        {
            return true;
        }
        return false;
    }

    public function createFile(array $data)
    {
        try{

           $this->filevalidator->with($data)->passesOrFail();

            $project = $this->repository->skipPresenter()->find($data['project_id']);
            $projectFile = $project->files()->create($data);

            $this->storage->disk('local_public')->put($projectFile->id.'.'.$data['extension'], $this->filesystem->get($data['file']));

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
                return ["success" => $this->filerepository->delete($id)];
            }

            return $projectfile;

        }catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Could not delete the File {$id}"
            ];
        }
    }

    public function getProjectFile($id)
    {
        try{
            return ["success" => $this->filerepository->find($id)];
        } catch(\Exception $e) {
            return ["success" => false, "message" => "File ID: {$id} not found"];
        }
    }

}