<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $code string */

?>
<div class="password-reset">
    <p>Привет, <?= Html::encode($user->profile->name) ?>.</p>

    <p>Ваш код для восстановления пароля: <?php echo $code ?></p>
</div>
