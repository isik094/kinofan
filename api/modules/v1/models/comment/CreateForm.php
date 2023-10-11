<?php

namespace api\modules\v1\models\comment;

use common\models\Cinema;
use common\models\Comment;
use common\models\ErrorLog;
use common\models\User;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="CreateForm",
 *      required={"cinema_id", "text"},
 *      @OA\Property(property="cinema_id", type="integer", example="1", description="ID кино"),
 *      @OA\Property(property="parent_id", type="integer", example="1", default="0", description="Если новый отзыв, то ничего не отправялем по умолчанию подставиться 0. Если создается ответ к отзыву, то отправляем ID отзыва"),
 *      @OA\Property(property="text", type="string", example="отличный фильм", description="Текст отзыва, проходит модерацию перед тем как появится в списке"),
 *  )
 */
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
    public function rules(): array
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
    public function parentValidate($attribute): void
    {
        if ($this->parent_id > 0 && !Comment::findOne($this->parent_id)) {
            $this->addError($attribute, 'Не существует комментария, на который вы отвечаете');
        }
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'parent_id' => 'ID родителя',
            'text' => 'Текст комментария',
        ];
    }

    /**
     * @brief Создать комментарий к фильму
     * @return Comment|bool|null
     */
    public function create(): Comment|bool|null
    {
        try {
            if (!$this->validate()) {
                return null;
            }

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