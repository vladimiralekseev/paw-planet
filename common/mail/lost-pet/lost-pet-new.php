<?php

use common\models\LostPet;

/**
 * @var LostPet $lostPet
 */

?>

New lost pet was added!<br>
You can <a href="https://<?= Yii::$app->params['domainAdmin'] ?>/lost-pet/view?id=<?= $lostPet->id ?>">see this
    lost pet</a> in admin.
