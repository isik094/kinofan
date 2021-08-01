<?php
namespace api\modules\v1\controllers;

class TestController extends ApiController
{
    public function actionTest()
    {
        return \Yii::$app->request->post();
    }
}