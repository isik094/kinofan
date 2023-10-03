<?php

namespace console\models;

use api\modules\components\Code;
use common\models\ConfirmEmailCode;
use common\models\ErrorLog;

class CodeCleanup extends CronTask
{
    public $time_limit = 86400;

    /**
     * @inheritDoc
     * @return array
     */
    public function attributeLabels(): array
    {
        return [];
    }

    /**
     * @return bool
     */
    public function run(): bool
    {
        try {
            ConfirmEmailCode::deleteAll(['<', 'created_at', time() - Code::LIFE_TIME]);
            return true;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }

        return false;
    }
}