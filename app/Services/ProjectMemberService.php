<?php
/**
 * Created by PhpStorm.
 * User: LuisCarlos
 * Date: 24/07/2015
 * Time: 18:27
 */

namespace CodeProject\Services;


use CodeProject\Repositories\ProjectMemberRepositoryInterface;
use CodeProject\Validators\ProjectMemberValidator;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectMemberService
{
    /**
     * @var ProjectRepositoryInterface
     */
    protected $repository;
    /**
     * @var ProjectValidator
     */
    protected $validator;

    public function __construct(ProjectMemberRepositoryInterface $repository, ProjectMemberValidator $validator)
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

    public function delete($projectId, $userId)
    {
        try{
            $member = $this->getProjectMember($projectId, $userId);

            if( $member['success'] ){
                return ["success" => $this->repository->delete($projectId, $userId)];
            }

            return $member;

        }catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Could not delete the Member {$userId}"
            ];
        }
    }

    public function getProjectMember($projectId, $userId)
    {
        try{
            return ["success" => $this->repository->findWhere(['user_id' => $userId, 'project_id' => $projectId])];
        } catch(\Exception $e) {
            return ["success" => false, "message" => "Member ID: {$userId} not found"];
        }
    }

    public function isMember($projectId, $userId)
    {
        if(count($this->repository->findWhere(['project_id' => $projectId, 'user_id' => $userId ])))
        {
            return true;
        }
        return false;
    }

}