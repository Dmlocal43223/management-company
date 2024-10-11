<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\file\entities\FileType $model */
/** @var backend\forms\FileTypeForm $fileTypeForm */

$this->title = 'Обновить тип файла: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Типы файлов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="file-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'fileTypeForm' => $fileTypeForm,
    ]) ?>

</div>
