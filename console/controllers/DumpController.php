<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class DumpController extends Controller
{
    /**
     * @brief Снятие дампа
     */
    public function actionIndex()
    {
        echo Yii::$app->dump->create();
        echo "\n";
    }

    /**
     * @brief Очищение хранилища от старых дампов
     */
    public function actionClean()
    {
        Yii::$app->dump->clean();
    }
}