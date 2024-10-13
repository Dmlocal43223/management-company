<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\location\entities\Apartment $model */
/** @var backend\forms\ApartmentForm $apartmentForm */
/** @var array $regions */
/** @var array $localities */
/** @var array $streets */
/** @var array $houses */

$this->title = 'Обновить квартиры: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Квартиры', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="apartment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'apartmentForm' => $apartmentForm,
        'regions' => $regions,
        'localities' => $localities,
        'streets' => $streets,
        'houses' => $houses
    ]) ?>

</div>
