<?php

/** @var yii\web\View $this */
/** @var common\models\SiteUser $user */

use yii\helpers\Url;

$resetLink = 'https://' . Yii::$app->params['domainRoot'] . Url::to(['site/reset-password', 'token' => $user->password_reset_token]);
?>
Hello <?= $user->fullName ?>,

Follow the link below to reset your password:

<?= $resetLink ?>
