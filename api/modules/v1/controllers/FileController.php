<?php
namespace api\modules\v1\controllers;


use api\modules\v1\traits\FileToDownloadData;

class FileController extends ApiController
{
    public $isPrivate = false;

    use FileToDownloadData;

    /**
     * @param string $uuid
     * @throws \Throwable
     */
    public function actionIndex($uuid)
    {
        $this->findFileByUuid($uuid)->download();
    }
}