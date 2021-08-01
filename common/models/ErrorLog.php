<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "error_log".
 *
 * @property int $id
 * @property int $user_id
 * @property string $action
 * @property string $trace
 * @property string $message
 * @property int $created_at
 *
 * @property User $user
 */
class ErrorLog extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'error_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['created_at', 'default', 'value' => time()],
            [['action', 'created_at'], 'required'],
            [['user_id', 'created_at'], 'integer'],
            [['trace'], 'string'],
            [['message'], 'string'],
            [['action'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'action' => 'Action',
            'trace' => 'Trace',
            'created_at' => 'Created At',
            'message' => 'Message',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @brief Записывает лог пойманного исключения
     * @param \Exception $e
     * @return bool
     */
    public static function createLog(\Exception $e)
    {
        $log = new self([
            'user_id' => isset(Yii::$app->user) && !Yii::$app->user->isGuest
                ? Yii::$app->user->id
                : null,
            'message' => mb_convert_encoding(substr($e->getMessage(), 0, 10000), 'UTF-8'),
            'action' => isset(Yii::$app->controller->id) && isset(Yii::$app->controller->action->id)
                ? Yii::$app->controller->id . '/' . Yii::$app->controller->action->id
                : null,
            'trace' => mb_convert_encoding(substr($e->getTraceAsString(), 0, 10000), 'UTF-8'),
        ]);

        return $log->save();
    }

    /**
     * @brief Записывает ошибки, выявленные при вализации модели
     * @param Model $model
     * @return bool
     */
    public static function createValidateError(Model $model)
    {
        if (isset(Yii::$app->user->id) && isset(Yii::$app->controller->id) && isset(Yii::$app->controller->action->id)) {
            $log = new self([
                'user_id' => Yii::$app->user->id,
                'action' => Yii::$app->controller->id . '/' . Yii::$app->controller->action->id,
                'trace' => json_encode($model->errors),
            ]);
            return $log->save();
        }
    }

    /**
     * @brief Мой лог
     *
     * @param $action
     * @param $message
     * @return bool
     */
    public static function customLog($action, $message)
    {
        $log = new self([
            'action' => $action,
            'message' => $message,
            'created_at' => time()
        ]);
        return $log->save();
    }

    /**
     * @brief Записывает лог пойманной ошибки Throwable
     * @param \Throwable $t
     * @return bool
     */
    public static function createLogThrowable(\Throwable $t)
    {
        $log = new self([
            'user_id' => isset(Yii::$app->user) && !Yii::$app->user->isGuest
                ? Yii::$app->user->id
                : null,
            'message' => mb_convert_encoding(substr($t->getMessage(), 0, 10000), 'UTF-8'),
            'action' => isset(Yii::$app->controller->id) && isset(Yii::$app->controller->action->id)
                ? Yii::$app->controller->id . '/' . Yii::$app->controller->action->id
                : null,
            'trace' => mb_convert_encoding(substr($t->getTraceAsString(), 0, 10000), 'UTF-8'),
        ]);

        return $log->save();
    }
}
