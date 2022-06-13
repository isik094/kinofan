<?php
namespace common\components;


use Yii;
use yii\base\Component;

class Dump extends Component
{
    /**
     * @brief Время жизни экземпляра дампа в секундах
     * @var int
     */
    public $lifeTime;

    /**
     * @brief Снятие дампа
     * @return string|null
     * @throws \Exception
     */
    public function create()
    {
        $dsn = Yii::$app->db->dsn;
        if (strpos($dsn, 'mysql:') !== false) {
            $result = explode('dbname=', $dsn);
            if (isset($result[1])) {
                $dbName = $result[1];
                $time = time();
                $filePath = dirname(dirname(__DIR__)) . '/dumps/' . $time . '.sql.gz';
                return shell_exec("mysqldump $dbName | gzip > $filePath");
            } else {
                throw new \Exception('Wrong db config');
            }
        } else {
            throw new \Exception('Supported mysql only');
        }
    }

    /**
     * @brief Очищение хранилища от старых дампов
     */
    public function clean()
    {
        $dumpsDir = dirname(dirname(__DIR__)) . '/dumps';
        $elements = scandir($dumpsDir);
        foreach ($elements as $element) {
            $extension = '.sql.gz';
            $explode = explode($extension, $element);
            if (isset($explode[1])) {
                $dumpTime = $explode[0];
                if ($dumpTime < (time() - $this->lifeTime)) {
                    unlink($dumpsDir . '/' . ($dumpTime . $extension));
                }
            }
        }
    }
}