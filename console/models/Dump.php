<?php

namespace console\models;

use Yii;

class Dump extends CronTask
{
    /**
     * @var int
     */
    public $time_limit = 86400;

    /**
     * @var bool
     */
    public $blockAfterFail = true;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [];
    }

    /**
     * @brief Снятие дампа
     * @return bool
     */
    public function run()
    {
        Yii::$app->dump->clean(); //очищаем хранилище от старых дампов
        Yii::$app->dump->create(); //снимаем новый дамп
        return true;
    }
}