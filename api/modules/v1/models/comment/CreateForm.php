<?php

namespace api\modules\v1\models\comment;

use common\models\Cinema;
use common\models\Comment;
use common\models\ErrorLog;
use common\models\User;

class CreateForm extends \yii\base\Model
{
    /**
     * @var Cinema
     */
    public Cinema $cinema;
    /**
     * @var User
     */
    public User $user;
    /**
     * @var integer
     */
    public $parent_id;
    /**
     * @var string
     */
    public $text;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['parent_id', 'text'], 'required'],
            [['parent_id'], 'integer', 'min' => 0],
            [['text'], 'string', 'max' => 600],
            [['parent_id'], 'parentValidate'],
        ];
    }

    /**
     * @param $attribute
     * @return void
     */
    public function parentValidate($attribute)
    {
        if ($this->parent_id > 0 && !Comment::findOne($this->parent_id)) {
            $this->addError($attribute, 'Не существует комментария, на который вы отвечаете');
        }
    }

    /**
     * @return string[]
     */
    public function attributeLabels()
    {
        return [
            'parent_id' => 'ID родителя',
            'text' => 'Текст комментария',
        ];
    }

    /**
     * @brief Создать комментарий к фильму
     * @return Comment|bool
     */
    public function create(): Comment|bool
    {
        try {
            $model = new Comment();
            $model->cinema_id = $this->cinema->id;
            $model->user_id = $this->user->id;
            $model->parent_id = $this->parent_id;
            $model->text = $this->text;
            $model->created_at = time();
            $model->status = Comment::UNDER_CONSIDERATION;
            $model->saveStrict();

            return $model;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
            return false;
        }
    }
}