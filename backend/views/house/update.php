<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\HouseForm $houseForm */
/** @var src\location\entities\House $model */
/** @var array $regions */
/** @var array $localities */
/** @var array $streets */

$this->title = 'Обновить объект: ' . $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Объекты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="house-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'houseForm' => $houseForm,
        'regions' => $regions,
        'localities' => $localities,
        'streets' => $streets
    ]) ?>

</div>
