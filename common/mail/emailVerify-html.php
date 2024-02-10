<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\SiteUser $user */

//$verifyLink = 'https://' . Yii::$app->params['domainRoot'] . Url::to(['auth/verify-email', 'token' =>
//$user->verification_token]);
$verifyLink = 'https://' . Yii::$app->params['domainRoot'] . Url::to(
        [
            'site/index',
            'token' => $user->verification_token
        ]
    );
?>
<div class="verify-email">
    <p>Hello <?= Html::encode(trim($user->first_name . ' ' . $user->first_name)) ?>,</p>

    <p>Token: <b><?= $user->verification_token ?></b></p>

    <p>You can copy and past this token on the site or follow the link below to verify your email:</p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>

    <p>Ignore this email if you didn't register on <?= Yii::$app->params['domainRoot'] ?></p>
</div>
