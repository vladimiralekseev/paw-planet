<?php

use common\models\UserRequestPet;

/**
 * @var UserRequestPet $request
 */

$petUserLink = 'https://' . Yii::$app->params['domainRoot'] . '/user/' . $request->pet->user->id . '/';
$petUserName = trim($request->pet->user->first_name . ' ' . $request->pet->user->last_name);
$requestUserName = trim($request->requestOwner->first_name . ' ' . $request->requestOwner->last_name);
?>
<p>
Hi <?= $requestUserName ?>!<br />
Your request to <b><?= $request->type ?></b> with <b><?= $request->pet->nickname ?></b> has been approved.
</p>
<p>
You can see the user profile data of <a href="<?= $petUserLink ?>"><?= $petUserName ?></a> to contact with.
</p>
