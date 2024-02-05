<?php

use common\models\ResponseLostPet;

/**
 * @var ResponseLostPet $response
 */

$requestUserName = trim($response->requestOwner->first_name . ' ' . $response->requestOwner->last_name);
?>
<p>
    Hi <?= $requestUserName ?>!<br />
    Unfortunately, your response to <b><?= $response->lostPet->nickname ?? 'the pet' ?></b> has been rejected.
</p>
