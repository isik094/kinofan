<?php

namespace console\controllers;

use console\models\CronTask;
use yii\base\Exception;
use yii\console\Controller;

class CronController extends Controller
{
    const EVERY_MINUTE = 'every_minute';
    const EVERY_HOUR = 'every_hour';
    const EVERY_DAY = 'every_day';
    const EVERY_WEEK = 'every_week';
    const EVERY_MONTH = 'every_month';
    const EVERY_YEAR = 'every_year';
    const EVERY_DAY_AT_8_O_CLOCK = 'every_day_at_8_o_clock';
    const EVERY_10_MINUTES = 'every_10_minutes';
    const EVERY_MONDAY_AT_11_CLOCK = 'every_monday_at_11_clock';
    const EVERY_3_MINUTES = 'every_3_minutes';

    /**
     * @brief Список задач и время их выполнения
     * @return array
     */
    private static function taskList()
    {
        return [
            self::EVERY_MINUTE => [

            ],
            self::EVERY_HOUR => [

            ],
            self::EVERY_DAY => [

            ],
            self::EVERY_WEEK => [

            ],
            self::EVERY_MONTH => [

            ],
            self::EVERY_YEAR => [

            ],
            self::EVERY_DAY_AT_8_O_CLOCK => [

            ],
            self::EVERY_10_MINUTES => [

            ],
            self::EVERY_3_MINUTES => [

            ],
        ];
    }

    /**
     * @brief Добавление задач на выполнение
     * Данный экшн должен быть установлен на ежеминутное выполнение в планировщик задач
     * @throws Exception
     */
    public function actionIndex()
    {
        if ($taskList = self::taskList()) {
            foreach ($taskList as $periodicity => $taskClasses) {
                if ($taskClasses) {
                    foreach ($taskClasses as $taskClass) {
                        $task = new $taskClass();
                        /**
                         * @var $task CronTask
                         */
                        if ($this->isAddThisTask($task, $periodicity)) {
                            $task->addTaskToList();
                        }
                    }
                }
            }
        }
        shell_exec(dirname(dirname(__DIR__)) . '/yii cron-task'); //можно настроить крон на выполнение этого экшна отдельно, но при таком вызове обеспечивается максимальная оперативность выполнения задач
    }

    /**
     * @brief Очищение задач из списка
     * @throws \Exception
     */
    public function actionClean()
    {
        $notIn = [];
        $classes = [];
        if ($taskList = self::taskList()) {
            foreach ($taskList as $periodicity => $taskClasses) {
                if ($taskClasses) {
                    foreach ($taskClasses as $taskClass) {
                        $classes[] = '"' . addslashes($taskClass) . '"';
                        $task = new $taskClass();
                        /**
                         * @var $task CronTask
                         */
                        if ($lastTask = $task->getLastTask()) {
                            $notIn[] = $lastTask->id;
                        }
                    }
                }
            }
        }

        if ($classes && $notIn) {
            $count = CronTask::deleteAll('class IN (' . implode(',', $classes) . ') AND id NOT IN (' . implode(',', $notIn) . ')');
            echo 'Was deleted ' . $count . ' rows';
        } else {
            throw new \Exception('Clean can not be done');
        }
    }


    /**
     * @brief Проверит, нужно ли добавить задачу в список
     * @param CronTask $task
     * @param string $periodicity
     * @return bool
     * @throws Exception
     */
    private function isAddThisTask(CronTask $task, $periodicity)
    {
        if (!$lastTask = $task->getLastTask()) {
            $lastTask = $this->initTask($task, $periodicity);
        }

        switch ($periodicity) {
            case self::EVERY_MINUTE:
                return strtotime(date('Y-m-d H:i:00', $lastTask->started_at) . '+1 minute') <= time();
            case self::EVERY_HOUR:
                return strtotime(date('Y-m-d H:00:00', $lastTask->started_at) . '+1 hour') <= time();
            case self::EVERY_DAY:
                return strtotime(date('Y-m-d 00:00:00', $lastTask->started_at) . '+1 day') <= time();
            case self::EVERY_WEEK:
                return strtotime('Sunday', $lastTask->started_at) <= time();
            case self::EVERY_MONTH:
                return strtotime(date('Y-m-01 00:00:00', $lastTask->started_at) . '+1 month') <= time();
            case self::EVERY_YEAR:
                return strtotime(date('Y-01-01 00:00:00', $lastTask->started_at) . '+1 year') <= time();
            case self::EVERY_DAY_AT_8_O_CLOCK:
                return strtotime(date('Y-m-d 08:00:00', $lastTask->started_at) . '+1 day') <= time();
            case self::EVERY_10_MINUTES:
                return strtotime(date('Y-m-d H:i:00', $lastTask->started_at) . '+10 minute') <= time();
            case self::EVERY_3_MINUTES:
                return strtotime(date('Y-m-d H:i:00', $lastTask->started_at) . '+3 minute') <= time();
//            case self::EVERY_MONDAY_AT_11_CLOCK: @todo здесь косяк
//                return strtotime(date('Monday 11:00:00', $lastTask->started_at) . '+7 day') <= time();
        }
        return false;
    }

    /**
     * @brief Инициализация задачи
     * @param CronTask $task
     * @param string $periodicity
     * @return CronTask
     * @throws Exception
     */
    private function initTask(CronTask $task, $periodicity)
    {
        $started_at = self::determineStartedAtOfInitTask($periodicity);
        $firstTask = new CronTask([
            'class' => $task->className(),
            'time_limit' => $task->time_limit,
            'created_at' => time(),
            'started_at' => $started_at,
            'completed_at' => $started_at,
        ]);
        if (!$firstTask->save()) {
            throw new Exception('При инициализации задачи возникла ошибка валидации. ' . json_encode($firstTask->errors));
        }
        return $firstTask;
    }

    /**
     * @brief Определяет время начала выполнения задачи, которая инициализируется при первом использовании данного модуля
     * @param $periodicity
     * @return int
     */
    private static function determineStartedAtOfInitTask($periodicity)
    {
        switch ($periodicity) {
            case self::EVERY_MINUTE:
                return strtotime(date('Y-m-d H:i:00'));
            case self::EVERY_HOUR:
                return strtotime(date('Y-m-d H:00:00'));
            case self::EVERY_DAY:
                return strtotime(date('Y-m-d 00:00:00'));
            case self::EVERY_WEEK:
                return strtotime('Sunday');
            case self::EVERY_MONTH:
                return strtotime(date('Y-m-01 00:00:00'));
            case self::EVERY_YEAR:
                return strtotime(date('Y-01-01 00:00:00'));
            case self::EVERY_DAY_AT_8_O_CLOCK:
                return strtotime(date('Y-m-d 08:00:00'));
            case self::EVERY_10_MINUTES:
                return strtotime(date('Y-m-d H:i:00'));
            case self::EVERY_3_MINUTES:
                return strtotime(date('Y-m-d H:i:00'));
//            case self::EVERY_MONDAY_AT_11_CLOCK: @todo здесь косяк
//                return strtotime('Last Monday 11:00:00');
        }
        return null;
    }
}
