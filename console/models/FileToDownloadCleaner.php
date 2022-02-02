<?php
namespace console\models;


use common\models\ErrorLog;
use common\models\FileToDownload;

class FileToDownloadCleaner extends CronTask
{
    public $time_limit = 86400;

    /**
     * @inheritDoc
     * @return array
     */
    public function attributeLabels()
    {
        return [];
    }

    /**
     * @return bool
     */
    public function run()
    {
        try {
            FileToDownload::deleteAll(['<', 'created_at', time() - 3600]);
            return true;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }

        return false;
    }
}