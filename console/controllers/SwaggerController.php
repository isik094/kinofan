<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class SwaggerController extends Controller
{
    /**
     * @brief Сгенерировать автоматическую документацию для API
     * @return int
     */
    public function actionGo(): int
    {
        $openApi = \OpenApi\Generator::scan([Yii::getAlias('@api/modules/v1/controllers')]);
        $file = Yii::getAlias('@api/web/api-doc/swagger.json');
        $handle = fopen($file, 'wb');
        fwrite($handle, $openApi->toJson());
        fclose($handle);
        echo $this->ansiFormat('Created \n", Console::FG_BLUE');
        return ExitCode::OK;
    }
}