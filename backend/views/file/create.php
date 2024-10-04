<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\file\entities\FileType $model */

$this->title = 'Create File Type';
$this->params['breadcrumbs'][] = ['label' => 'File Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
