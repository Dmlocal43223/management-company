<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\HouseForm $houseForm */
/** @var array $regions */
/** @var array $localities */
/** @var array $streets */

$this->title = 'Создать объект';
$this->params['breadcrumbs'][] = ['label' => 'Объекты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="house-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'houseForm' => $houseForm,
        'regions' => $regions,
        'localities' => $localities,
        'streets' => $streets
    ]) ?>

</div>
