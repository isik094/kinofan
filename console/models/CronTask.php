<?php

namespace console\models;

use common\models\ErrorLog;
use console\controllers\CronController;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "cron_task".
 *
 * @property int $id
 * @property string $class Класс задачи
 * @property string $properties Свойства задачи
 * @property int $time_limit Максимальное время выполнения задачи
 * @property int $created_at Время создания задачи
 * @property int $started_at Время начала выполения
 * @property int $completed_at Время завершения выполнения
 * @property int $fail_time Время возникновения ошибки
 */
class CronTask extends ActiveRecord
{
    /**
     * @brief Не выполнять задачу повторно, если одна попытка была неудачная
     * @var bool
     */
    public $blockAfterFail = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cron_task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['created_at', 'default', 'value' => time()],
            [['class', 'time_limit', 'created_at'], 'required'],
            [['properties'], 'safe'],
            [['time_limit', 'created_at', 'started_at', 'completed_at', 'fail_time'], 'integer'],
            [['class'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class' => 'Класс задачи',
            'properties' => 'Свойства задачи',
            'time_limit' => 'Максимальное время выполнения задачи',
            'created_at' => 'Время создания задачи',
            'started_at' => 'Время начала выполения',
            'completed_at' => 'Время завершения выполнения',
            'fail_time' => 'Время возникновения ошибки',
        ];
    }

    /**
     * @inheritdoc
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->class = $this->className();
        parent::__construct($config);
    }


    /**
     * @brief Вернет задачу
     * @param null $completed
     * @param bool|false $notBegin
     * @return CronTask
     */
    public function task($completed = null, $notBegin = false)
    {
        return self::getTask($this->class, $this->properties, $completed, $notBegin);
    }

    /**
     * @brief Вернет задачу
     * @param $class
     * @param array|false $properties
     * @param null $completed
     * @param bool|false $notBegin
     * @return static
     */
    public static function getTask($class, $properties = null, $completed = null, $notBegin = false)
    {
        if (is_array($properties)) {
            $properties = json_encode($properties);
        }
        $query = self::find()->where([
            'class' => $class,
            'properties' => $properties ?: null,
            'completed_at' => $completed,
        ]);

        if ($notBegin) {
            $query->andWhere('started_at IS NULL');
        }

        return $query->one();
    }

    /**
     * @brief Инициализирует кастомные свойства задачи
     * @return array|bool
     */
    public function initProperties()
    {
        if ($attributes = array_keys($this->attributeLabels())) {
            $properties = [];
            foreach ($attributes as $attribute) {
                $properties[$attribute] = $this->$attribute;
            }
            return $this->properties = $properties;
        }
        return false;
    }

    /**
     * @brief Инициализирует кастомные свойства задачи в объект задачи
     * @return static
     */
    public function getInitTask()
    {
        $childTask = new $this->class();
        $childTask->id = $this->id;
        if ($this->properties && $properties = json_decode($this->properties, true)) {
            foreach ($properties as $attribute => $value) {
                $childTask->$attribute = $value;
            }
        }
        return $childTask;
    }

    /**
     * @brief Добавление задачи в список выполнения
     * @param bool $canAddIfExistInstance Можно ли добавлять задачу, если есть другая задача этого же класса, которая еще не выполнена
     * @param int $timeLimit Лимит выполнения задачи (если не передать, будет установлен дефолтный для данной задачи)
     * @param $notBegin bool|false
     * @return bool
     */
    public function addTaskToList($canAddIfExistInstance = false, $timeLimit = null, $notBegin = false)
    {
        if ($timeLimit) {
            $this->time_limit = $timeLimit;
        }

        $this->beforeAdd();

        if ($this->validate()) {
            $task = new self([
                'class' => $this->className(),
                'time_limit' => $this->time_limit,
            ]);
            if ($this->initProperties()) {
                $task->properties = json_encode($this->properties);
            }

            if ($canAddIfExistInstance || !$task->task(null, $notBegin)) {
                return $task->save();
            }

            return true;
        }

        return false;
    }

    /**
     * @brief Вернет свойство задачи
     * @param $name
     * @return CronTaskProperty
     */
    public function getProperty($name)
    {
        $condition = [
            'task_id' => $this->id,
            'name' => $name,
        ];
        if (!$property = CronTaskProperty::findOne($condition)) {
            $property = new CronTaskProperty($condition);
        }
        return $property;
    }

    /**
     * @brief Вернет значение свойства задачи
     * @param $name
     * @return null|string
     */
    public function getValue($name)
    {
        return ($property = $this->getProperty($name)) ? $property->value : null;
    }

    /**
     * @brief Установит значение свойства
     * @param string $name имя свойства
     * @param string $value значение свойства
     * @return bool
     */
    public function setProperty($name, $value)
    {
        $property = $this->getProperty($name);
        return $property->setValue($value);
    }

    /**
     * @brief Сохранение свойств задачи в отдельную таблицу
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->properties && $properties = json_decode($this->properties, true)) {
            foreach ($properties as $attribute => $value) {
                $this->setProperty($attribute, $value);
            }
        }
    }

    /**
     * @brief Получает очередную задачу на выполнение
     * @param null $class
     * @return self
     */
    public static function getAnotherTask($class = null)
    {
        $task = self::find()->where(['completed_at' => null]);
        if ($class) {
            $task->andWhere(['class' => $class]);
        }

        $task = $task->one();
        /**
         * @var $task static
         */
        return $task;
    }

    /**
     * @brief Получает на выполнение очередную задачу, которую нужно выполнять вне очереди
     * @return self
     */
    public static function getAnotherOutOfTurnTask()
    {
        return self::getAnotherTask(CronController::$outOfTurn);
    }

    /**
     * @brief Выполнение задачи
     * Возвращает true, если задача выполнена успешно
     * @return bool
     */
    public function run()
    {
        try {
            if ($this->started_at //если сервер начал выполнять задачу
                && ($this->started_at + $this->time_limit) >= time() //и лимит на выполнение еще не истек
                && (!$this->fail_time || $this->fail_time < $this->started_at) //и задача не отвалилась по ошибке после последнего старта
            ) {
                die();
            }

            set_time_limit($this->time_limit);

            $childTask = $this->getInitTask();

            if ($this->started_at && $childTask->blockAfterFail) { //если задача уже пыталась выполниться, но этого не произошло, и такую задачу нельзя выполнять повторно
                die();
            }

            if (($this->started_at = time()) && $this->save() && $childTask->run()) {
                $this->completed_at = time();
                return $this->save();
            }

            return $this->fail();
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }

        return $this->fail();
    }

    /**
     * @brief Выполняет в случае ошибки и возвращает false
     * @return bool
     */
    protected function fail()
    {
        $this->fail_time = time();
        $this->save();
        return false;
    }

    /**
     * @brief Вернет последнюю задачу данного класса
     * @return self
     */
    public function getLastTask()
    {
        return self::find()->where([
            'class' => $this->class,
        ])
            ->orderBy(['created_at' => SORT_DESC])
            ->one();
    }

    /**
     * @brief Выполняется перед добавлением в список задач
     */
    public function beforeAdd() {}
}
