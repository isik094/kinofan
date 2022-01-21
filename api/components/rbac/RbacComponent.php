<?php
namespace api\components\rbac;

use common\models\User;
use api\models\UserRole;
use yii\helpers\FileHelper;
use yii\web\ForbiddenHttpException;

class RbacComponent
{
    /** @var User $_user */
    private $_user;

    /**
     * RbacComponent constructor.
     * @param User $user
     */
    public function __construct(User $user = null)
    {
        if ($user) {
            $this->_user = $user;
        }
    }

    /**
     * @brief Получить все роли у пользователя
     * @return array
     * @throws \Exception
     */
    public function roles():array
    {
        $roles = UserRole::findAll(['user_id' => $this->_user->id]);
        $arr = [];
        $rolesItems = $this->getOpenItems();
        foreach ($roles as $role) {
            if (isset($rolesItems[$role->role])) {
                array_push($arr, ['role' => $role->role, 'name' => $rolesItems[$role->role]['description']]);
            }
        }
        return $arr;
    }

    /**
     * @brief Проверить принадлежит роль данному пользователю
     * @param string $role
     * @return bool
     */
    public function isRole(string $role):bool
    {
        $role = UserRole::find()->where(['user_id' => $this->_user->id, 'role' => $role])->count();
        return !!$role;
    }

    /**
     * @brief Привязать пользователю роль
     * @param string $role
     * @return bool
     * @throws \Exception
     */
    public function addUserRole(string $role) :bool
    {
        $roles = $this->getOpenItems();
        if (empty($roles[$role])) {
            throw new \Exception('Нет такой роли');
        }

        if (UserRole::find()->where(['user_id' => $this->_user->id, 'role' => $role])->count()) {
            throw new \Exception('У данного пользователя уже имеется данная роль');
        }

        $userRole = new UserRole([
            'user_id' => $this->_user->id,
            'role' => $role,
        ]);

        return $userRole->saveStrict();
    }

    /**
     * @brief Отвязать пользователю роль
     * @param string $role
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function removeUserRole(string $role) :bool
    {
        $userRole = UserRole::find()->where(['user_id' => $this->_user->id, 'role' => $role])->one();
        if (!$userRole) {
            throw new \Exception('У данного пользователя нет такой роли');
        }

        return $userRole->delete() !== false;
    }

    /**
     * @brief Удаление роли
     * @param string $role
     * @return bool
     * @throws \Exception
     */
    public function removeRole(string $role) :bool
    {
        $roles = $this->getOpenItems();
        if (empty($roles[$role])) {
            throw new \Exception('Нет такой роли');
        }
        array_splice($roles, $role, 1);

        UserRole::deleteAll(['role' => $role]);
        return true;
    }

    /**
     * @brief Добавление роли
     * @param string $role
     * @param string $description
     * @return bool
     * @throws \Exception
     */
    public function addRole(string $role, string $description):bool
    {
        $roles = $this->getOpenItems();
        if (isset($roles[$role])) {
            throw new \Exception('Такая роль существует');
        }

        $roles[$role] = [
            'type' => 1,
            'description' => $description,
            'actions' => [],
        ];
        return true;
    }

    /**
     * @brief Проверить права доступа к экшену
     * @param string $action
     * @return bool
     * @throws \Exception
     */
    public function can(string $action):bool
    {
        list($version, $controller, $actionId) = preg_split('/\//', $action);
        if (!$action) {
            throw new \Exception('Неверный формат экшена');
        }

        $userRoles = UserRole::findAll(['user_id' => $this->_user->id]);
        $roles = $this->getOpenItems();
        $access = false;
        foreach ($userRoles as $userRole) {
            if (!array_key_exists($userRole->role, $roles)) {
                continue;
            }

            if (!isset($roles[$userRole->role]['actions'][$controller])) {
                throw new ForbiddenHttpException('Нет такого контроллера');
            }

            $controllerName = $roles[$userRole->role]['actions'][$controller];
            if (!isset($controllerName)) {
                continue;
            }

            if (isset($controllerName[$actionId])) {
                if ($controllerName[$actionId]) {
                    $access = true;
                }
            } else {
                throw new ForbiddenHttpException('Нет такого экшена');
            }
        }

        return $access;
    }

    /**
     * @brief Получить все роли
     * @return array
     * @throws \yii\base\Exception
     */
    public function getRolesList():array
    {
        $roles = $this->getOpenItems();
        $arr = [];
        foreach ($roles as $role => $item) {
            array_push($arr, ['role' => $role, 'name' => $item['description']]);
        }
        return $arr;
    }

    /**
     * @brief Получить все actions
     * @param string $role
     * @return mixed
     * @throws \Exception
     */
    public function getActions($role)
    {
        $roles = $this->getOpenItems();
        if (isset($roles[$role])) {
            return $roles[$role]['actions'];
        }
        throw new \Exception('Нет такой роли');
    }

    /**
     * @brief Получить массив с ролями
     * @return mixed
     * @throws \yii\base\Exception
     */
    private function getOpenItems()
    {
        $file = self::getItemPath();
        if (!file_exists($file)) {
            FileHelper::createDirectory($file);
        }
        return include $file;
    }

    /**
     * @brief Получить путь к items
     * @return string
     */
    private static function getItemPath()
    {
        return __DIR__ . '/items.php';
    }
}