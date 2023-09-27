<?php

namespace api\modules\v1\models;

use common\models\Favorites;
use common\models\User;
use yii\base\Model;
use common\models\Cinema;

class AddFavoritesForm extends Model
{
    /**
     * @var User
     */
    public User $user;
    /**
     * @var int
     */
    public $cinema_id;

    public function rules(): array
    {
        return [
            [['cinema_id'], 'required'],
            [['cinema_id'], 'integer'],
            [['cinema_id'], 'existValidate'],
            [['cinema_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cinema::className(), 'targetAttribute' => ['cinema_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'cinema_id' => 'ID кино',
        ];
    }

    /**
     * @brief Валидация на существование такой записи
     * @param $attribute
     * @return void
     */
    public function existValidate($attribute): void
    {
        $favorites = Favorites::findOne(['user_id' => $this->user->id, 'cinema_id' => $this->cinema_id]);
        if ($favorites) {
            $this->addError($attribute, 'Кино уже добавлено в избранное');
        }
    }

    /**
     * @brief Добавить в избранное фильм
     * @return bool|null
     * @throws \Exception
     */
    public function create(): ?bool
    {
        if (!$this->validate()) {
            return null;
        }

        $favorites = new Favorites();
        $favorites->user_id = $this->user->id;
        $favorites->cinema_id = $this->cinema_id;

        return $favorites->saveStrict();
    }
}
