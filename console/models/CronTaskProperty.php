<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "cron_task_property".
 *
 * @property int $id
 * @property int $task_id
 * @property string $name
 * @property string $value
 *
 * @property CronTask $task
 */
class CronTaskProperty extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cron_task_property';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'name', 'value'], 'required'],
            [['task_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['value'], 'safe'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => CronTask::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'name' => 'Name',
            'value' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(CronTask::className(), ['id' => 'task_id']);
    }

    /**
     * @brief Установит значение свойству
     * @param $value
     * @return bool
     */
    public function setValue($value)
    {
        $this->value = is_array($value) ? json_encode($value) : $value;
        return $this->save();
    }
}
