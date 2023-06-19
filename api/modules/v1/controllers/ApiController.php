<?php
namespace api\modules\v1\controllers;

use api\components\ApiFromList;
use api\components\ApiFunction;
use api\components\ApiGetter;
use api\components\ApiUnchanged;
use api\components\rbac\RbacComponent;
use api\models\ApiLog;
use common\models\ErrorLog;
use common\models\IpBlock;
use common\base\ActiveRecord;
use common\models\User;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use Yii;
use yii\web\ForbiddenHttpException;

class ApiController extends Controller
{
    /**
     * Требуется ли авторизация для работы с контроллером
     * @var bool
     */
    protected $isPrivate = true;

    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        if ($this->isPrivate) {
            $behaviors['authenticator'] = [
                'class' => \sizeg\jwt\JwtHttpBearerAuth::class,
                'except' => ['options'],
            ];
        }

        return $behaviors;
    }

    /**
     * @brief ID записей, которые подгружаются при сборке в makeObject|makeObjects
     * ID объектов находятся в ключах, соответствующих названию класса, экземпляром которого является объект
     * @var array
     */
    protected $ids = [];

    /**
     * @var array
     */
    private $_getters = [];

    /**
     * @var array
     */
    private $_funcResults = [];

    /**
     * @param $action
     * @return bool
     * @throws \Throwable
     */
    public function beforeAction($action)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Allow-Methods: *');

        try {
            $ip = Yii::$app->request->getUserIP();

            //раскомментировать в случае атак
//            if (IpBlock::findOne(['ip' => $ip])) {
//                throw new ForbiddenHttpException();
//            }

            if (ArrayHelper::getValue(Yii::$app->params, 'api_log')) {
                $log = new ApiLog([
                    'method' => $action->controller->id . '/' . $action->id,
                    'get' => json_encode(Yii::$app->request->get()),
                    'post' => json_encode(Yii::$app->request->post()),
                    'files' => json_encode($_FILES),
                    'headers' => json_encode(Yii::$app->request->headers->toArray()),
                    'ip' => $ip,
                    'created_at' => time(),
                ]);

                $log->save();
            }

            $beforeAction = parent::beforeAction($action);
            //раскомментировать, если используется rbac
//            if ($this->isPrivate && !\Yii::$app->request->isOptions) {
//                $component = new RbacComponent(User::getCurrent());
//                if (!$component->can(\Yii::$app->controller->route)) {
//                    throw new ForbiddenHttpException('У Вас нет прав доступа');
//                }
//            }

            return $beforeAction;
        } catch (\Throwable $t) {
            ErrorLog::createLogThrowable($t);
            throw $t;
        }
    }

    /**
     * @brief Костыль для обхода CORS
     * @return bool
     */
    public function actionOptions()
    {
        return true;
    }

    /**
     * @brief убирает лишние данные из строки base64
     * @param string $base64
     * @return string
     */
    public function base64formatter($base64)
    {
        $exp = explode(',', $base64);
        return array_pop($exp);
    }

    /**
     * @brief Массив атрибутов для множества объектов одного класса.
     * Может быть ассоциативным и нет. Если массив ассоциативный, то ключи будут использовать в качестве названий аргументов результирующего объекта
     * @param ActiveRecord[] $objects
     * @param array $attributes
     * @return array
     */
    protected function makeObjects(array $objects, array $attributes)
    {
        $result = [];
        foreach ($objects as $object) {
            $result[] = $this->makeObject($object, $attributes);
        }
        return $result;
    }

    /**
     * @brief Массив атрибутов. Может быть ассоциативным и нет. Если массив ассоциативный, то ключи будут использовать в качестве названий аргументов результирующего объекта
     * @param ActiveRecord $object
     * @param array $attributes
     * @return \stdClass
     */
    protected function makeObject(ActiveRecord $object, array $attributes)
    {
        $object->formatter();

        if (!isset($this->ids[get_class($object)])) {
            $this->ids[get_class($object)] = [];
        }

        if (isset($object->id) && !in_array($object->id, $this->ids[get_class($object)])) { //записываем ID объекта в ids
            $this->ids[get_class($object)][] = $object->id;
        }

        $result = new \stdClass();
        foreach ($attributes as $attrResult => $attrObject) {
            if (is_string($attrResult)) {
                $result->$attrResult = $this->_makeData($attrObject, $object);
            } else {
                $result->$attrObject = $this->_makeData($attrObject, $object);
            }
        }

        return $result;
    }

    /**
     * @brief Соберет данные для указанного ключа в зависимости от того, что передано
     * @param $attrObject
     * @param ActiveRecord $object
     * @return array|\stdClass|string
     */
    private function _makeData($attrObject, ActiveRecord $object)
    {
        if ($attrObject instanceof ApiFunction) {
            if ($attrObject->wayToObject) {
                $result = $this->_runFunc($this->getFiniteObject($attrObject->wayToObject, $object), $attrObject);
            } else {
                $result = $this->_runFunc($object, $attrObject);
            }

            if ($attrObject->attributes !== null) {
                if (is_array($result)) {
                    return $this->makeObjects($result, $attrObject->attributes);
                } elseif (is_object($result)) {
                    return $this->makeObject($result, $attrObject->attributes);
                }
            }

            return $result;
        } elseif ($attrObject instanceof ApiGetter) {
            $getterResult = $this->getFiniteObject($attrObject->getterName, $object);

            if (is_array($getterResult)) {
                return $this->makeObjects($getterResult, $attrObject->attributes);
            } elseif (is_object($getterResult)) {
                return $this->makeObject($getterResult, $attrObject->attributes);
            } else {
                return $getterResult;
            }
        } elseif ($attrObject instanceof ApiUnchanged) {
            return $attrObject->data;
        } elseif ($attrObject instanceof ApiFromList) {
            $val = $this->getFiniteObject($attrObject->attribute, $object);

            if ($attrObject->required) {
                return $attrObject->list[$val];
            } else {
                return $val ? $attrObject->list[$val] : null;
            }
        } else {
            return is_string($attrObject) ? $object->$attrObject : $attrObject;
        }
    }

    /**
     * @brief Получает конечный объект через геттеры
     * @param array|string $way
     * @param ActiveRecord $object
     * @return mixed|ActiveRecord
     */
    private function getFiniteObject($way, ActiveRecord $object)
    {
        if (is_array($way)) {
            $result = $object;
            foreach ($way as $name) {
                $result = $this->_get($result, $name);
                if (is_null($result)) {
                    break;
                }
            }
        } else {
            $result = $this->_get($object, $way);
        }

        return $result;
    }

    /**
     * @brief Получает атрибут из кэша или делает запрос и кэширует
     * @param object $object
     * @param string $attr
     * @return mixed
     */
    private function _get($object, $attr)
    {
        if (!isset($object->id)) {
            return $object->$attr;
        }

        if (isset($this->_getters[get_class($object)][$object->id][$attr])) {
            return $this->_getters[get_class($object)][$object->id][$attr];
        } else {
            return $this->_getters[get_class($object)][$object->id][$attr] = $object->$attr;
        }
    }

    /**
     * @brief Получает атрибут из кэша или делает запрос и кэширует
     * @param object $object
     * @param ApiFunction $apiFunction
     * @return mixed
     */
    private function _runFunc($object, ApiFunction $apiFunction)
    {
        if (!isset($object->id) || !$apiFunction->enableCache) {
            return call_user_func_array([$object, $apiFunction->function], $apiFunction->args);
        }

        if (isset($this->_funcResults[get_class($object)][$object->id][$apiFunction->function])) {
            return $this->_funcResults[get_class($object)][$object->id][$apiFunction->function];
        } else {
            return $this->_funcResults[get_class($object)][$object->id][$apiFunction->function] = call_user_func_array([$object, $apiFunction->function], $apiFunction->args);
        }
    }
}