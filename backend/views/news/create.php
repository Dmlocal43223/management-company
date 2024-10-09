<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\news\entities\News $newsModel */
/** @var backend\forms\NewsFileForm $fileModel */

$this->title = 'Создать новость';
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'newsModel' => $newsModel,
        'fileModel' => $fileModel,
    ]) ?>

</div>
