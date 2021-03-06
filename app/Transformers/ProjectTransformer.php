<?php

namespace CodeProject\Transformers;

use League\Fractal\TransformerAbstract;
use CodeProject\Entities\Project;

/**
 * Class ProjectTransformerTransformer
 * @package namespace CodeProject\Transformers;
 */
class ProjectTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
            'members',
            'client'
    ];

    /**
     * Transform the \ProjectTransformer entity
     * @param \ProjectTransformer $model
     *
     * @return array
     */
    public function transform(Project $project)
    {
        return [
            'id'   => (int)$project->id,
            'client_id' => (int)$project->client_id,
            'name'        => $project->name,
            'description' => $project->description,
            'progress'  => (int) $project->progress,
            'status' => $project->status,
            'due_date' => $project->due_date,
            'is_member' => $project->user_id != \Authorizer::getResourceOwnerId()
        ];
    }

    public  function includeMembers(Project $project)
    {
        return $this->collection($project->members, new MemberTransformer());

    }

    public  function includeClient(Project $project)
    {
        return $this->item($project->client, new ClientTransformer());
    }
}