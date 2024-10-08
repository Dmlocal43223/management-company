<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\location\entities\Locality $model */
/** @var array $regions */

$this->title = 'Обновить населенный пункт: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Населенные пункты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="locality-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'regions' => $regions
    ]) ?>

</div>
