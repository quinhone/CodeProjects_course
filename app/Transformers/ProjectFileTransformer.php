<?php

namespace CodeProject\Transformers;

use League\Fractal\TransformerAbstract;
use CodeProject\Entities\ProjectFile;

/**
 * Class ProjectNoteTransformer
 * @package namespace CodeProject\Transformers;
 */
class ProjectFileTransformer extends TransformerAbstract
{

    /**
     * Transform the \ProjectNote entity
     * @param \ProjectNote $model
     *
     * @return array
     */
    public function transform(ProjectFile $model) {
        return [
            'id'            => (int)$model->id,
            'name'          => $model->name,
            'description'   => $model->description,
            'extension'     => $model->extension,
            'created_at'    => $model->created_at,
            'updated_at'    => $model->updated_at
        ];
    }
}