<?php

namespace common\models;

use api\models\ApiLog;
use common\base\ActiveRecord;
use Yii;

/**
 * This is the model class for table "ip_block".
 *
 * @property int $id
 * @property string $ip
 * @property int $created_at
 */
class IpBlock extends ActiveRecord
{
    /**
     * Кол-во одинаковых запросов, которые воспринимаются как DDOS
     */
    const REPEAT_QUERY_COUNT = 10;
    /**
     * Период, в течение которого эти запросы должны произойти
     */
    const PERIOD = 300;
    /**
     * Время блокировки ip
     */
    const BLOCKED_TIME = 3600;

    /**
     * @brief Методы, которые проверяются на DDOS
     * @var array
     */
    public static $methods = [
        'auth/signup',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ip_block';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip', 'created_at'], 'required'],
            [['created_at'], 'integer'],
            [['ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'Ip',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @throws \Exception
     */
    public static function block()
    {
        foreach (self::$methods as $method) {
            $logs = ApiLog::find()
                ->select([
                    '*',
                    'c' => 'COUNT(*)',
                ])
                ->where(['method' => $method])
                ->andWhere(['>', 'created_at', time() - self::PERIOD])
                ->andWhere('ip IS NOT NULL')
                ->groupBy('ip')
                ->having(['>', 'c', self::REPEAT_QUERY_COUNT])
                ->all();

            foreach ($logs as $log) {
                if (IpBlock::findOne(['ip' => $log->ip])) {
                    continue;
                }

                $block = new IpBlock([
                    'ip' => $log->ip,
                    'created_at' => time(),
                ]);

                $block->saveStrict();
            }
        }

        IpBlock::deleteAll(['<', 'created_at', time() - self::BLOCKED_TIME]);
    }
}
