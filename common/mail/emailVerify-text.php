<?php

/** @var yii\web\View $this */
/** @var common\models\SiteUser $user */

use yii\helpers\Url;

//$verifyLink = 'https://' . Yii::$app->params['domainRoot'] . Url::to(['auth/verify-email', 'token' => $user->verification_token]);
$verifyLink = 'https://' . Yii::$app->params['domainRoot'] . Url::to(
        [
            'site/index',
            'token' => $user->verification_token
        ]
    );
?>
Hello <?= trim($user->first_name . ' ' . $user->first_name) ?>,

Token: <?= $user->verification_token ?>

You can copy and past this token on the site or follow the link below to verify your email:

<?= $verifyLink ?>

Ignore this email if you didn't register on <?= Yii::$app->params['domainRoot'] ?>
