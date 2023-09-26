<?php

namespace api\modules\v1\controllers;

use api\modules\v1\traits\FileToDownloadData;

class FileController extends ApiController
{
    use FileToDownloadData;

    public bool $isPrivate = false;

    /**
     * @param string $uuid
     * @throws \Throwable
     */
    public function actionIndex(string $uuid): void
    {
        $this->findFileByUuid($uuid)->download();
    }
}