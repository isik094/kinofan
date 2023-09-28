<?php

namespace common\models;

use Yii;
use common\base\ActiveRecord;
use common\helpers\FileHelper;

/**
 * This is the model class for table "file_to_download".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $uuid
 * @property string $path
 * @property string|null $name
 * @property int $created_at
 *
 * @property User $user
 */
class FileToDownload extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file_to_download';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at'], 'integer'],
            [['uuid', 'path', 'created_at'], 'required'],
            [['path'], 'string'],
            [['uuid', 'name'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'uuid' => 'Uuid',
            'path' => 'Path',
            'name' => 'Name',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @param string $path
     * @param string $name
     * @param User|null $user
     * @return FileToDownload
     * @throws \yii\base\Exception
     */
    public static function create($path, $name = null, User $user = null)
    {
        $model = new self([
            'user_id' => $user ? $user->id : null,
            'path' => $path,
            'uuid' => Yii::$app->security->generateRandomString(255),
            'name' => $name,
            'created_at' => time(),
        ]);

        $model->saveStrict();
        return $model;
    }

    /**
     * @brief Ссылка на скачивание
     * @return string
     * @throws \Exception
     */
    public function getLink(): string
    {
        if (!isset(Yii::$app->params['fileToDownloadUrl'])) {
            throw new \Exception('fileToDownloadUrl is not defined in params');
        }

        return Yii::$app->params['fileToDownloadUrl'] . '/file?uuid=' . $this->uuid;
    }

    /**
     * @throws \Throwable
     */
    public function download()
    {
        FileHelper::downloadFile($this->path, $this->name);
    }

    /**
     * @return mixed|string|null
     */
    private function _name()
    {
        if ($this->name) {
            return $this->name;
        } else {
            $explode = explode('/', $this->path);
            return array_pop($explode);
        }
    }
}
