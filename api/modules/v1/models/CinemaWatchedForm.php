<?php

namespace api\modules\v1\models;

use common\base\Model;
use common\models\Cinema;
use common\models\CinemaWatched;
use common\models\ErrorLog;
use common\models\User;

class CinemaWatchedForm extends Model
{
    /**
     * @var User
     */
    public User $user;
    /**
     * @var array
     */
    public $cinema_ids;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['cinema_ids', 'required'],
            ['cinema_ids', 'countValidate'],
            ['cinema_ids', 'each', 'rule' => ['integer']],
            ['cinema_ids', 'existsValidate'],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'cinema_ids' => 'ID кино'
        ];
    }

    /**
     * @brief Валидация на сущетсвование кино
     * @param $attribute
     * @return void
     */
    public function existsValidate($attribute): void
    {
        $exist = false;
        foreach ($this->cinema_ids as $cinema_id) {
            if (!Cinema::findOne($cinema_id)) {
                $exist = true;
            }
        }

        if ($exist === true) {
            $this->addError($attribute, 'Нет такого фильма в списке');
        }
    }

    /**
     * @brief Валидация на существование id в массиве
     * @param $attribute
     * @return void
     */
    public function countValidate($attribute): void
    {
        if (count($this->cinema_ids) === 0) {
            $this->addError($attribute, 'Массив кино не может быть пустым');
        }
    }

    /**
     * @brief Создать записи о просмотренных фильмов пользователя
     * @return bool|null
     */
    public function create(): ?bool
    {
        try {
            if ($this->validate()) {
                return null;
            }

            foreach ($this->cinema_ids as $cinema_id) {
                $model = new CinemaWatched();
                $model->cinema_id = $cinema_id;
                $model->user_id = $this->user->id;
                $model->saveStrict();
            }

            return true;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
            return false;
        }
    }
}