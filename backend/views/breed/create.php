<?php

use common\models\Breed;
use yii\web\View;

/**
 * @var View  $this
 * @var Breed $model
 */

$this->title = 'Breed creation';
$this->params['breadcrumbs'][] = ['label' => 'Breeds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="text-page-create">
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->render(
                '_form',
                compact(
                    'model'
                )
            ) ?>
        </div>
    </div>
</div>
