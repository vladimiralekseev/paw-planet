<?php

use common\models\Pet;
use yii\web\View;

/**
 * @var View $this
 * @var Pet  $model
 */

$this->title = $model->nickname;
$this->params['breadcrumbs'][] = ['label' => 'Pets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nickname, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editing';
?>

<div class="text-page-update">

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
