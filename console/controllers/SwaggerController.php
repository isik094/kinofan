<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\BaseConsole;
use OpenApi\Generator;

class SwaggerController extends Controller
{
    const API_DECLARATION = '@api/modules/v1/controllers';
    const SCAN_DIRECTORY = '@common/models';
    const COMPONENTS_DIRECTORY = '@common/components';
    const FRONTEND_MODELS = '@frontend/models';
    const API_MODELS = '@api/models';

    /**
     * @brief Сгенерировать автоматическую документацию для API
     * @return int
     */
    public function actionGenerate(): int
    {
        $file = Yii::getAlias('@api/web/api-doc/swagger.json');
        $this->stdout("Начата генерация документа \n");

        $openApi = Generator::scan([
            Yii::getAlias(self::API_DECLARATION),
            Yii::getAlias(self::SCAN_DIRECTORY),
            Yii::getAlias(self::COMPONENTS_DIRECTORY),
            Yii::getAlias(self::FRONTEND_MODELS),
            Yii::getAlias(self::API_MODELS),
        ]);

        $handle = fopen($file, 'wb');
        fwrite($handle, $openApi->toJson());
        fclose($handle);

        $this->stdout("Завершено! \r\n", BaseConsole::FG_YELLOW);
        return ExitCode::OK;
    }
}