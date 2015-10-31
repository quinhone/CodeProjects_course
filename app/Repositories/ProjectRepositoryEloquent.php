<?php
/**
 * Created by PhpStorm.
 * User: LuisCarlos
 * Date: 24/07/2015
 * Time: 18:21
 */

namespace CodeProject\Repositories;


use CodeProject\Entities\Project;
use CodeProject\Presenters\ProjectPresenter;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class ProjectRepositoryEloquent extends BaseRepository implements ProjectRepositoryInterface
{
	/**
	 * @var array
	 */
	protected $fieldSearchable = [
		'name'
	];

	/**
	 * Specify Model class name
	 *
	 * @return string
	 */
	public function model ()
	{
		return Project::class;
	}

	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot ()
	{
		return $this->pushCriteria ( app ( RequestCriteria::class ) );
	}

	public function isOwner ( $projectid, $userid )
	{
		if ( count ( $this->skipPresenter ()->findWhere ( [ 'id' => $projectid, 'user_id' => $userid ] ) ) ) {
			return true;
		}
		return false;
	}

	public function hasMember ( $projectid, $userid )
	{
		$project = $this->skipPresenter ()->find ( $projectid );

		foreach ( $project->members as $member ) {
			if ( $member->id == $userid ) {
				return true;
			}
		}
		return false;

	}

	public function findWithOwnerAndMember ( $userId )
	{
		return $this->scopeQuery ( function ( $query ) use ( $userId ) {
			return $query->select ( 'projects.*' )
				->leftJoin ( 'project_members', 'project_members.project_id', '=', 'projects.id' )
				->where ( 'project_members.user_id', '=', $userId )
				->union ( $this->model->query ()->getQuery ()->where ( 'user_id', '=', $userId ) );
		} )->all();
	}

	public function presenter ()
	{
		return ProjectPresenter::class;
	}

}