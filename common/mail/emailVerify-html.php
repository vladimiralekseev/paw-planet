<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\SiteUser $user */

$verifyLink = 'https://' . Yii::$app->params['domainRoot'] . Url::to(['auth/verify-email', 'token' =>
$user->verification_token]);
?>
<div class="verify-email">
    <p>Hello <?= Html::encode(trim($user->first_name . ' ' . $user->first_name)) ?>,</p>

    <p>Follow the link below to verify your email:</p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div>
