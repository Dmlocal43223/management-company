<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\location\entities\Region $model */
/** @var backend\forms\RegionForm $regionForm */

$this->title = 'Обновить регион: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Регионы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="region-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'regionForm' => $regionForm,
    ]) ?>

</div>
