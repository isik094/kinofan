<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "api_log".
 *
 * @property int $id
 * @property string|null $method
 * @property string|null $get
 * @property string|null $post
 * @property string|null $files
 * @property string|null $headers
 * @property string|null $ip
 * @property int $created_at
 */
class ApiLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'api_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['get', 'post', 'headers', 'method', 'files', 'ip'], 'safe'],
            [['created_at'], 'required'],
            [['created_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'method' => 'Method',
            'get' => 'Get',
            'post' => 'Post',
            'files' => 'Files',
            'headers' => 'Headers',
            'ip' => 'IP',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @inheritDoc
     * @param bool $insert
     * @return bool|void
     */
    public function beforeSave($insert)
    {
        $this->get = substr($this->get, 0, 10000);
        $this->post = substr($this->post, 0, 10000);
        $this->headers = substr($this->headers, 0, 10000);
        $this->files = substr($this->files, 0, 10000);
        return parent::beforeSave($insert);
    }
}
