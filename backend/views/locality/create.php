<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\LocalityForm $localityForm */
/** @var array $regions */

$this->title = 'Создать населенный пункт';
$this->params['breadcrumbs'][] = ['label' => 'Населенные пункты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="locality-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'localityForm' => $localityForm,
        'regions' => $regions
    ]) ?>

</div>
