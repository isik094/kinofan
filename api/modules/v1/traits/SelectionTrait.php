<?php

namespace api\modules\v1\traits;

use yii\web\NotFoundHttpException;
use common\models\Selection;
use api\components\ApiFunction;

trait SelectionTrait
{
    use CinemaData;

    public function selectionData(): array
    {
        return [
            'id',
            'name',
            'image_path' => new ApiFunction('uploadsLink', ['image_path'], null, false, null),
            'cinemas' => new ApiFunction('getCinemaList'),
        ];
    }

    /**
     * @param int $id
     * @return Selection
     * @throws NotFoundHttpException
     */
    public function findSelection(int $id): Selection
    {
        if (($model = Selection::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Not found');
    }
}