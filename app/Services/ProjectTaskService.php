<?php
/**
 * Created by PhpStorm.
 * User: LuisCarlos
 * Date: 24/07/2015
 * Time: 18:27
 */

namespace CodeProject\Services;

use CodeProject\Repositories\ProjectTaskRepositoryInterface;
use CodeProject\Validators\ProjectTaskValidator;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectTaskService
{
    /**
     * @var ProjectRepositoryInterface
     */
    protected $repository;
    /**
     * @var ProjectValidator
     */
    protected $validator;

    public function __construct(ProjectTaskRepositoryInterface $repository, ProjectTaskValidator $validator)
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
            $task = $this->getProjectTask($id);

            if( $task['success'] ){
                return ["success" => $this->repository->delete($id)];
            }

            return $task;

        }catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Could not delete the Task {$id}"
            ];
        }
    }

    public function getProjectTask($id)
    {
        try{
            return ["success" => $this->repository->find($id)];
        } catch(\Exception $e) {
            return ["success" => false, "message" => "Task ID: {$id} not found"];
        }
    }
}