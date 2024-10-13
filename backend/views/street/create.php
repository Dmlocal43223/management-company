<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\StreetForm $streetForm */
/** @var array $regions */
/** @var array $localities */

$this->title = 'Создать улицу';
$this->params['breadcrumbs'][] = ['label' => 'Улицы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="street-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'streetForm' => $streetForm,
        'regions' => $regions,
        'localities' => $localities
    ]) ?>

</div>
