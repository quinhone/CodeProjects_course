<?php
/**
 * Created by PhpStorm.
 * User: LuisCarlos
 * Date: 24/07/2015
 * Time: 18:27
 */

namespace CodeProject\Services;

use CodeProject\Repositories\ProjectRepositoryInterface;
use CodeProject\Validators\ProjectValidator;
use Prettus\Validator\Exceptions\ValidatorException;


class ProjectService
{
    /**
     * @var ProjectRepositoryInterface
     */
    protected $repository;

    protected $validator;


    public function __construct(ProjectRepositoryInterface $repository, ProjectValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
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

    public function checkProjectOwner($projectId)
    {
        $userid = \Authorizer::getResourceOwnerId();
        return $this->repository->isOwner($projectId, $userid);

    }

    public function checkProjectMember($projectId)
    {
        $userid = \Authorizer::getResourceOwnerId();
        return $this->repository->hasMember($projectId, $userid);

    }

    public function checkProjectPermissions($projectId)
    {
        if($this->checkProjectOwner($projectId) || $this->checkProjectMember($projectId))
        {
            return true;
        }
        return false;
    }

}