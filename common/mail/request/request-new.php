<?php

use common\models\UserRequestPet;

/**
 * @var UserRequestPet $request
 */

$petUserLink = 'https://' . Yii::$app->params['domainRoot'] . '/profile/';
$petUserName = trim($request->pet->user->first_name . ' ' . $request->pet->user->last_name);
?>
<p>
    Hi <?= $petUserName ?>!<br />
    Your has a request to <b><?= $request->type ?></b> with <b><?= $request->pet->nickname ?></b>.
</p>
<p>
    You can approve or reject this request on <a href="<?= $petUserLink ?>">your profile page</a>.
</p>
