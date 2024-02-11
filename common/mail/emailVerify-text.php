<?php

/** @var yii\web\View $this */
/** @var common\models\SiteUser $user */

use yii\helpers\Url;

//$verifyLink = 'https://' . Yii::$app->params['domainRoot'] . Url::to(['auth/verify-email', 'token' => $user->verification_token]);
$verifyLink = 'https://' . Yii::$app->params['domain'] . Url::to(
        [
            'auth/verify-email',
            'token' => $user->verification_token
        ]
    );
?>
Hello <?= $user->fullName ?>,

Follow the link below to verify your email:

<?= $verifyLink ?>

Ignore this email if you don't register on <?= Yii::$app->params['domainRoot'] ?>
