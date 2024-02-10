<?php

/** @var View $this view component instance */
/** @var MessageInterface $message the message being composed */
/** @var string $content main view render result */

use yii\mail\MessageInterface;
use yii\web\View;

?>
<?php $this->beginPage() ?>
<?php $this->beginBody() ?>
<?= $content ?>

The platform aims to connect people who share a passion for pets, facilitate joint pet walks, provide a space for discussions,
and assist in finding lost pets.
<?php $this->endBody() ?>
<?php $this->endPage() ?>
