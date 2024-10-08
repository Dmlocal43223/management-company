<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\location\entities\Locality $model */
/** @var array $regions */

$this->title = 'Создать населенный пункт';
$this->params['breadcrumbs'][] = ['label' => 'Localities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="locality-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'regions' => $regions
    ]) ?>

</div>
