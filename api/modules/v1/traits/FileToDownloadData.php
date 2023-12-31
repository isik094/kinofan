<?php

namespace api\modules\v1\traits;

use common\models\FileToDownload;
use yii\web\NotFoundHttpException;

trait FileToDownloadData
{
    /**
     * @return array
     */
    public function fileToDownloadData(): array
    {
        return [
            'id',
            'link',
        ];
    }

    /**
     * @param string $uuid
     * @return FileToDownload|null
     * @throws NotFoundHttpException
     */
    public function findFileByUuid(string $uuid): ?FileToDownload
    {
        if ($model = FileToDownload::findOne(['uuid' => $uuid])) {
            return $model;
        }

        throw new NotFoundHttpException('File not found');
    }
}
