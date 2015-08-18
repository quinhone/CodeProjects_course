<?php
/**
 * Created by PhpStorm.
 * User: LuisCarlos
 * Date: 24/07/2015
 * Time: 18:26
 */

namespace CodeProject\Services;


use CodeProject\Repositories\UserRepositoryInterface;
use CodeProject\Validators\UserValidator;
use Prettus\Validator\Exceptions\ValidatorException;

class UserService
{
    protected $repository;
    protected $validator;

    public function __construct(UserRepositoryInterface $repository, UserValidator $validator)
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
            $client = $this->getClient($id);

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

    public function getClient($id)
    {
        try{
            return ["success" => $this->repository->find($id)];
        } catch(\Exception $e) {
            return ["success" => false, "message" => "Client ID: {$id} not found"];
        }
    }
}