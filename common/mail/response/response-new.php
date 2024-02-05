<?php

use common\models\ResponseLostPet;

/**
 * @var ResponseLostPet $response
 */

$petUserLink = 'https://' . Yii::$app->params['domainRoot'] . '/profile/responses/';
$petUserName = trim($response->lostPet->user->first_name . ' ' . $response->lostPet->user->last_name);
?>
<p>
    Hi <?= $petUserName ?>!<br />
    You have a response to <b><?= $response->lostPet->nickname ?? 'the pet' ?></b>.
</p>
<p>
    You can approve or reject this response on <a href="<?= $petUserLink ?>">your profile page</a>.
</p>
