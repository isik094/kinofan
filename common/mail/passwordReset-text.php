<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $code string */

?>
Привет <?= $user->profile->name ?>,

Ваш код для восстановления пароля:

<?= $code ?>
