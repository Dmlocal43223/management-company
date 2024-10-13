<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\ApartmentForm $apartmentForm */
/** @var array $regions */
/** @var array $localities */
/** @var array $streets */
/** @var array $houses */

$this->title = 'Создать квартиру';
$this->params['breadcrumbs'][] = ['label' => 'Квартиры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apartment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'apartmentForm' => $apartmentForm,
        'regions' => $regions,
        'localities' => $localities,
        'streets' => $streets,
        'houses' => $houses
    ]) ?>

</div>
