<?php

namespace api\modules\v1\traits;

use api\components\ApiGetter;
use common\models\Person;
use yii\web\NotFoundHttpException;

trait PersonCinemaData
{
    use PersonData;

    /**
     * @brief Person Cinema Data
     * @return array
     */
    public function personCinemaData(): array
    {
        return [
            'person' => new ApiGetter('person', $this->personData()),
            'profession_text',
        ];
    }

    /**
     * @brief Поиск персонажа
     * @param int $id
     * @return Person
     * @throws NotFoundHttpException
     */
    public function findPerson(int $id): Person
    {
        if (($model = Person::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Not found');
    }
}