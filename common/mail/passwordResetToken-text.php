<?php

/** @var yii\web\View $this */
/** @var common\models\User $user */

use yii\helpers\Url;

$resetLink = 'https://' . Yii::$app->params['domainRoot'] . Url::to(['site/reset-password', 'token' => $user->password_reset_token]);
?>
Hello <?= trim($user->first_name . ' ' . $user->first_name) ?>,

Follow the link below to reset your password:

<?= $resetLink ?>
