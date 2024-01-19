<?php

use common\models\UserRequestPet;

/**
 * @var UserRequestPet $request
 */

$requestUserName = trim($request->requestOwner->first_name . ' ' . $request->requestOwner->last_name);
?>
<p>
    Hi <?= $requestUserName ?>!<br />
    Unfortunately, your request to <b><?= $request->type ?></b> with <b><?= $request->pet->nickname ?></b> has been
    rejected.
</p>
