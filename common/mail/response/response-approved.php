<?php

use common\models\ResponseLostPet;

/**
 * @var ResponseLostPet $response
 */

$petUserLink = 'https://' . Yii::$app->params['domainRoot'] . '/user/' . $response->lostPet->user->id . '/';
$petUserName = trim($response->lostPet->user->first_name . ' ' . $response->lostPet->user->last_name);
$requestUserName = trim($response->requestOwner->first_name . ' ' . $response->requestOwner->last_name);
?>
<p>
Hi <?= $requestUserName ?>!<br />
Your response to <b><?= $response->lostPet->nickname ?? 'the pet' ?></b> has been approved.
</p>
<p>
You can see the user profile data of <a href="<?= $petUserLink ?>"><?= $petUserName ?></a> to contact with.
</p>
