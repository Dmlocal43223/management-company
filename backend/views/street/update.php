<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\location\entities\Street $model */
/** @var backend\forms\StreetForm $streetForm */
/** @var array $regions */
/** @var array $localities */

$this->title = 'Обновить улицу: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Streets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="street-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'streetForm' => $streetForm,
        'regions' => $regions,
        'localities' => $localities
    ]) ?>

</div>
