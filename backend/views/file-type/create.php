<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\FileTypeForm $fileTypeForm */

$this->title = 'Создать тип файла';
$this->params['breadcrumbs'][] = ['label' => 'Типы файлов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'fileTypeForm' => $fileTypeForm,
    ]) ?>

</div>
