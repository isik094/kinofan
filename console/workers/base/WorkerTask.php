<?php

namespace console\workers\base;

use common\base\ActiveRecord;
use common\models\ErrorLog;
use Yii;

/**
 * This is the model class for table "worker_task".
 *
 * @property int $id
 * @property string $class
 * @property string $properties
 * @property int $created_at
 * @property int|null $started_at
 * @property int|null $completed_at
 * @property int|null $fail_time
 */
class WorkerTask extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'worker_task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['class', 'properties', 'created_at'], 'required'],
            [['created_at', 'started_at', 'completed_at', 'fail_time'], 'integer'],
            [['class', 'properties'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class' => 'Class',
            'properties' => 'Properties',
            'created_at' => 'Created At',
            'started_at' => 'Started At',
            'completed_at' => 'Completed At',
            'fail_time' => 'Fail Time',
        ];
    }

    /**
     * @return WorkerTask
     */
    public static function getTaskToExecute()
    {
        return self::find()->where('completed_at IS NULL')->orderBy(['id' => SORT_ASC])->one();
    }

    /**
     * @brief Запуск задачи
     * @throws \Exception
     */
    public function execute()
    {
        try {
            $this->started_at = time();
            $this->saveStrict();

            $class = $this->class;
            $instance = new $class();

            if ($instance instanceof WorkerTaskInstance) {
                if ($this->fail_time && $instance->blockAfterFail) {
                    return;
                }

                $properties = json_decode($this->properties, true);
                foreach ($properties as $attribute => $value) {
                    if (property_exists($class, $attribute)) {
                        $instance->$attribute = $value;
                    } else {
                        throw new \Exception('Class ' . $class . ' has not property ' . $attribute);
                    }
                }

                if ($instance->run()) {
                    $this->completed_at = time();
                    $this->saveStrict();
                } else {
                    $this->fail_time = time();
                    $this->saveStrict();
                }
            } else {
                throw new \Exception('Worker task instance must be instance of ' . WorkerTaskInstance::class);
            }
        } catch (\Throwable $t) {
            ErrorLog::createLogThrowable($t);
            $this->fail_time = time();
            $this->saveStrict();
        }
    }
}
