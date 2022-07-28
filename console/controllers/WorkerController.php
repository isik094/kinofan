<?php
namespace console\controllers;


use console\workers\base\WorkerTask;
use yii\console\Controller;

class WorkerController extends Controller
{
    /**
     * @brief Воркер
     * @param int $interval
     * @throws \Exception
     */
    public function actionIndex($interval = 1)
    {
        while (true) {
            $instance = WorkerTask::getTaskToExecute();

            if ($instance) {
                $instance->execute();
            } else {
                sleep($interval);
            }
        }
    }
}