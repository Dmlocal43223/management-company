<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\RegionForm $regionForm */

$this->title = 'Создать регион';
$this->params['breadcrumbs'][] = ['label' => 'Регионы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'regionForm' => $regionForm,
    ]) ?>

</div>
