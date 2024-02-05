<?php

use common\models\Pet;

/**
 * @var Pet $pet
 */

?>

New pet was added!<br>
You can <a href="https://<?= Yii::$app->params['domainAdmin'] ?>/pet/view?id=<?= $pet->id ?>">see this pet</a> in admin.
