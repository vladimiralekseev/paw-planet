<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\SiteUser $user */

//$verifyLink = 'https://' . Yii::$app->params['domainRoot'] . Url::to(['auth/verify-email', 'token' =>
//$user->verification_token]);
$verifyLink = 'https://' . Yii::$app->params['domain'] . Url::to(
        [
            'auth/verify-email',
            'token' => $user->verification_token
        ]
    );
?>
<div class="verify-email">
    <p>Hello <?= Html::encode($user->fullName) ?>,</p>

    <p>Follow the link below to verify your email:</p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>

    <p>Ignore this email if you don't register on <?= Yii::$app->params['domainRoot'] ?></p>
</div>
