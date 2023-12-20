<?php

/** @var yii\web\View $this */
/** @var common\models\SiteUser $user */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/verify-email', 'token' => $user->verification_token]);
?>
Hello <?= trim($user->first_name . ' ' . $user->first_name) ?>,

Follow the link below to verify your email:

<?= $verifyLink ?>
