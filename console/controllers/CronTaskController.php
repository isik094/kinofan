<?php
/**
 * Created by PhpStorm.
 * User: Renat
 * Date: 18.08.2016
 * Time: 11:28
 */

namespace console\controllers;
use console\models\CronTask;
use yii\console\Controller;
use Yii;


class CronTaskController  extends Controller
{
    /**
     * @brief Запуск поочередного выполнения задач
     */
    public function actionIndex()
    {
        if ($task = CronTask::getAnotherTask()) {
            if ($task->run()) {
                $this->actionIndex();
            }
        }
    }
}