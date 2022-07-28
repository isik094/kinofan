<?php
namespace console\workers\base;


abstract class WorkerTaskInstance
{
    /**
     * @brief Не выполнять задачу повторно, если одна попытка была неудачная
     * @var bool
     */
    public $blockAfterFail = false;

    /**
     * @brief Выполняет задачу воркера
     * @return bool
     */
    abstract public function run(): bool;

    /**
     * @brief Добавление задачи в воркер
     * @throws \Exception
     */
    public function addTaskToList()
    {
        $workerTask = new WorkerTask();
        $workerTask->class = get_class($this);
        $workerTask->properties = json_encode($this);
        $workerTask->created_at = time();
        $workerTask->saveStrict();
    }
}