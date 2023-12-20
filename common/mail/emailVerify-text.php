<?php

/** @var yii\web\View $this */
/** @var common\models\SiteUser $user */

use yii\helpers\Url;

$verifyLink = 'https://' . Yii::$app->params['domainRoot'] . Url::to(['auth/verify-email', 'token' => $user->verification_token]);
?>
Hello <?= trim($user->first_name . ' ' . $user->first_name) ?>,

Follow the link below to verify your email:

<?= $verifyLink ?>
